<?php
namespace app\data;

class User{
    private $email;
    private $pwd;
    private $username;
    private $photo_url;
    private $photoBas64;
    private $country;
    private $token_push;
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

    function getPhoto_url() {
        return $this->photo_url;
    }

    function getPhotoBas64() {
        return $this->photoBas64;
    }

    function getCountry() {
        return $this->country;
    }

    function getToken_push() {
        return $this->token_push;
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

    function setPhoto_url($photo_url) {
        $this->photo_url = $photo_url;
    }

    function setPhotoBas64($photoBas64) {
        $this->photoBas64 = $photoBas64;
    }

    function setCountry($country) {
        $this->country = $country;
    }

    function setToken_push($token_push) {
        $this->token_push = $token_push;
    }

    function setApi_key($api_key) {
        $this->api_key = $api_key;
    }

                
    public function getArray(){
        return array("email"=> $this->email,
            "pwd"=> $this->pwd,
            "username"=> $this->username,
            "photo_url"=> $this->photo_url,
            "photoBas64"=> $this->photoBas64,
            "country"=> $this->country,
            "token_push"=> $this->token_push,
            "api_key"=>$this->api_key);
    }
}

