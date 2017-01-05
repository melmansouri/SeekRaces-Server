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
    
    public function login($data){
        
    }
    
    private function checkExistUser($email){
        $query='select * from user where email like :email';
        $dataQuery=array(':email'=>$email);
        
        $result=$this->connectionDb->executeQueryWithDataFetch($query,$dataQuery);
        
        return $result;
    }
}

