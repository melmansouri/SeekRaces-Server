<?php

namespace app\common;

class Mail {

    private $mail;
    private $username = "email";
    private $pwd = "pwd";

    public function __construct() {
        $this->mail = new \PHPMailer();
        //$this->mail->SMTPDebug = 2;
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->mail->IsSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->Host = 'smtp.gmail.com'; // "ssl://smtp.gmail.com" didn't worked
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tls';

        $this->mail->Username = $this->username;
        $this->mail->Password = $this->pwd;

        $this->mail->IsHTML(true); // if you are going to send HTML formatted emails 

        $this->mail->setFrom($this->username);
    }

    public function sendMail($addressTo, $nameTo, $subject, $body) {
        try{
            $this->mail->addAddress($addressTo, $nameTo);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;

        return $this->mail->send();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
