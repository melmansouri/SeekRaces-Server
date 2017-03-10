<?php
namespace app\entities;

class User{
    private $email;
    private $pwd;
    private $username;
    private $photo_url;
    private $photoBase64;
    private $place;
    private $token_push;
    private $isFollowed;
    private $isSentNotificacion;
    
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

    function getPhotoBase64() {
        return $this->photoBase64;
    }

    function getPlace() {
        return $this->place;
    }

    function getToken_push() {
        return $this->token_push;
    }

    function getIsFollowed() {
        return $this->isFollowed;
    }

    function getIsSentNotificacion() {
        return $this->isSentNotificacion;
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

    function setPhotoBase64($photoBase64) {
        $this->photoBase64 = $photoBase64;
    }

    function setPlace($place) {
        $this->place = $place;
    }

    function setToken_push($token_push) {
        $this->token_push = $token_push;
    }

    function setIsFollowed($isFollowed) {
        $this->isFollowed = $isFollowed;
    }

    function setIsSentNotificacion($isSentNotificacion) {
        $this->isSentNotificacion = $isSentNotificacion;
    }

    
    
                    
    public function getArray(){
        return array("email"=> $this->email,
            "pwd"=> $this->pwd,
            "username"=> $this->username,
            "photo_url"=> $this->photo_url,
            "photoBase64"=> $this->photoBase64,
            "place"=> $this->place,
            "token_push"=> $this->token_push,
            "isFollowed"=>$this->isFollowed,
            "isSentNotificacion"=>$this->isSentNotificacion);
    }
}

