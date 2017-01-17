<?php
namespace app\entities;

class User{
    private $email;
    private $pwd;
    private $username;
    private $photo_url;
    private $photoBase64;
    private $country;
    private $token_push;
    
    public function __construct() {
        
    }
    public function getEmail() {
        return $this->email;
    }

    public function getPwd() {
        return $this->pwd;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPhoto_url() {
        return $this->photo_url;
    }

    public function getPhotoBase64() {
        return $this->photoBase64;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getToken_push() {
        return $this->token_push;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPwd($pwd) {
        $this->pwd = $pwd;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPhoto_url($photo_url) {
        $this->photo_url = $photo_url;
    }

    public function setPhotoBase64($photoBase64) {
        $this->photoBase64 = $photoBase64;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function setToken_push($token_push) {
        $this->token_push = $token_push;
    }

                    
    public function getArray(){
        return array("email"=> $this->email,
            "pwd"=> $this->pwd,
            "username"=> $this->username,
            "photo_url"=> $this->photo_url,
            "photoBase64"=> $this->photoBase64,
            "country"=> $this->country,
            "token_push"=> $this->token_push);
    }
}

