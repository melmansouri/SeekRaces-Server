<?php
namespace app\entities;

class Opinion{
    private $username;
    private $photo;
    private $score;
    private $comment;
    private $dateTimeOpinion;
    
    public function __construct() {
        
    }
    function getUsername() {
        return $this->username;
    }

    function getPhoto() {
        return $this->photo;
    }

    function getScore() {
        return $this->score;
    }

    function getComment() {
        return $this->comment;
    }

    function getDateTimeOpinion() {
        return $this->dateTimeOpinion;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPhoto($photo) {
        $this->photo = $photo;
    }

    function setScore($score) {
        $this->score = $score;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setDateTimeOpinion($dateTimeOpinion) {
        $this->dateTimeOpinion = $dateTimeOpinion;
    }

    
    public function getArray(){
        return array(
            "username"=> $this->username,
            "photo"=> $this->photo,
            "score"=> $this->score,
            "comment"=> $this->comment,
            "dateTimeOpinion"=> $this->dateTimeOpinion
        );
    }
    
}


