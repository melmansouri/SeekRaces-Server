<?php
namespace app\dto;

class User{
    private $email;
    private $pwd;
    private $username;
    private $photo;
    private $api_key;
    
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

    function getApi_key() {
        return $this->api_key;
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

    function setApi_key($api_key) {
        $this->api_key = $api_key;
    }
    
    public function getArray(){
        return array("email"=> $this->email,
            "pwd"=> $this->pwd,
            "username"=> $this->username,
            "photo"=> $this->photo,
            "api_key"=>$this->api_key);
    }
}

?>

