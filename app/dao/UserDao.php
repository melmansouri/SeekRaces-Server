<?php

namespace app\dao;

class UserDao{
    private $connectionDb;
    
    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }
    
    public function signIn($data){
        $user=new \app\dto\User();
        $response=new \app\dto\Response();
        $messageResponse='Error al registrarse';
        
        $user->setEmail($data['email']);
        $user->setPwd($data['pwd']);
        $user->setUsername($data['username']);
        $user->setPhoto($data['photo']);
        
        if ($this->checkExistUser($user->getEmail())) {
            $messageResponse="Ya existe un usuario con este correo";
        }else{
            $verification=new \app\dao\VerificationDao($this->connectionDb);
            if($verification->insertSignInUserVerification($user)){
                if($verification->sendMailVerification($user->getEmail(), $user->getUsername())){
                    $messageResponse="Se le ha enviado un correo de confirmación.";
                }
            }
        }
        $response->setMessage($messageResponse);
        return $response;
    }
    
    public function verificationSignIn($token){
        $messageResponse='<h3>Esta verificacion ya se ha completado</h3>';
        $verification=new \app\dao\VerificationDao($this->connectionDb);
        $user=new \app\dto\User();
        
        $dataUserVerification=$verification->getDataVerificationUser($token);
        if ($dataUserVerification) {
            $user->setEmail($dataUserVerification["email"]);
            $user->setPwd($dataUserVerification["pwd"]);
            $user->setUsername($dataUserVerification["username"]);
            $user->setPhoto($dataUserVerification["photo"]);
            if ($this->insertIntoUserTable($user)) {
                $verification->deleteExistUserVerification($user->getEmail());
                $messageResponse='<h3>Bienvenido a SeekRaces</h3>';
            }
        }
        return $messageResponse;
    }
    
    public function insertIntoUserTable($user){
        $query="INSERT INTO user(email, pwd, username, photo)"
                . " VALUES"
                . " (:email, :pwd, :username, :photo)";
        
        $dataQuery=array('email'=>$user->getEmail(),
            'pwd'=> \app\common\Utils::cifrarBCrypt($user->getPwd()),
            'username'=>$user->getUsername(),
            'photo'=>$user->getPhoto());
        $result=$this->connectionDb->executeQueryWithData($query,$dataQuery);
        
        return $result;
        
    }
    
    public function login($data){
        
    }
    
    private function checkExistUser($email){
        $query='select * from user where email like :email';
        $dataQuery=array(':email'=>$email);
        
        $result=$this->connectionDb->executeQueryWithDataFetch($query,$dataQuery);
        
        return $result;
    }
}

