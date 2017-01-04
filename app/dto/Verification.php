<?php
namespace app\dto;

class Verification{
    private $email;
    private $pwd;
    private $username;
    private $photo;
    private $token_verification;
    private $creation_time;
    
    public function __construct() {
        
    }
    function getEmail() {
        return $this->email;
    }

    function getPwd() {
        return $this->pwd;
    }

    function getUsername() {
        return $this->username;
    }

    function getPhoto() {
        return $this->photo;
    }

    function getToken_verification() {
        return $this->token_verification;
    }

    function getCreation_time() {
        return $this->creation_time;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPwd($pwd) {
        $this->pwd = $pwd;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPhoto($photo) {
        $this->photo = $photo;
    }

    function setToken_verification($token_verification) {
        $this->token_verification = $token_verification;
    }

    function setCreation_time($creation_time) {
        $this->creation_time = $creation_time;
    }
}

?>

