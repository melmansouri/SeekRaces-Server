<?php

namespace app\dao;

class VerificationDao{
    private $connectionDb;
    private $token_verification;
    
    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }
    
    public function insertSignInUserVerification($user){
        $this->deleteExistUserVerification($user->getEmail());
        
        $this->token_verification= \app\common\Utils::generateTokenVerification();
        $creation_dateTime= \app\common\Utils::nowDateTime();
        
        $query="INSERT INTO verification(email, pwd, username, photo, token_verification, creation_datetime)"
                . " VALUES"
                . " (:email, :pwd, :username, :photo, :token_verification, :creation_datetime)";
        
        $dataQuery=array('email'=>$user->getEmail(),
            'pwd'=> \app\common\Utils::cifrarBCrypt($user->getPwd()),
            'username'=>$user->getUsername(),
            'photo'=>$user->getPhoto(),
            'token_verification'=>$this->token_verification,
            'creation_datetime'=>$creation_dateTime);
        
        $result=$this->connectionDb->executeQueryWithData($query,$dataQuery);
        
        return $result;
        
    }
    
    public function deleteExistUserVerification($email){
        $query="delete from verification where email like :email";
        $dataQuery=array('email'=>$email);
        
        $result=$this->connectionDb->executeQueryWithData($query,$dataQuery);
        
        return $result;
    }
    
    public function getDataVerificationUser($token){
        $query="select * from verification where token_verification = :token_verification";
        
        $dataQuery=array(':token_verification'=>$token);
        
        $result=$this->connectionDb->executeQueryWithDataFetch($query,$dataQuery);
        
        return $result;
    }
    
    public function sendMailVerification($addressTo,$nameTo){
        $mail=new \app\common\Mail();
            $subject="Confirmar registro en SeekRaces";
            $url_confirmacion = "http://localhost:8080/SeekRaces/api/verification?token=" . $this->token_verification;
            $body= $this->generateBodyToSendMailToVerificationSignIn($url_confirmacion);
            return $mail->sendMail($addressTo, $nameTo, $subject, $body);
    }
    
    private function generateBodyToSendMailToVerificationSignIn($urlConfimation){
        $body="<br />Tienes 24 horas para completar la verificacion "
                . "de la cuenta en 'SeekRaces'."
                . "<br>Para completar tu registro debes de pulsar el siguiente boton:<br/>"
                . "<a style=\"white-space:nowrap;display:block;padding:10px 25px;background:#87AA14;"
                . "color:#ffffff;font-family:Helvetica Neue, Arial, sans-serif;"
                . "font-size:15px;line-height:15px;font-weight:bold;"
                . "text-decoration:none;border-collapse:collapse;"
                . "border-color:#82a313;border-style:1px solid;border-radius:3px;\" "
                . "href=\"$urlConfimation\" target=\"_blank\">
                        Confirmar Email</a>";
        
        return $body;
    }

}
