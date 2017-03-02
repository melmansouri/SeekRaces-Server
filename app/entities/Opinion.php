<?php
namespace app\entities;

class Opinion{
    private $username;
    private $user;
    private $photo_name;
    private $score;
    private $comment;
    private $dateOpinion;
    
    public function __construct() {
        
    }
    
    function getUsername() {
        return $this->username;
    }

    function getUser() {
        return $this->user;
    }

    function getPhoto_name() {
        return $this->photo_name;
    }

    function getScore() {
        return $this->score;
    }

    function getComment() {
        return $this->comment;
    }

    function getDateOpinion() {
        return $this->dateOpinion;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setPhoto_name($photo_name) {
        $this->photo_name = $photo_name;
    }

    function setScore($score) {
        $this->score = $score;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setDateOpinion($dateOpinion) {
        $this->dateOpinion = $dateOpinion;
    }

        
    
    public function getArray(){
        return array(
            "userName"=> $this->username,
            "user"=> $this->user,
            "photo_name"=> $this->photo_name,
            "score"=> $this->score,
            "comment"=> $this->comment,
            "dateOpinion"=> $this->dateOpinion
        );
    }
    
}


