<?php
namespace app\entities;

class Opinion{
    private $username;
    private $photo_name;
    private $score;
    private $comment;
    private $dateOpinion;
    
    public function __construct() {
        
    }
    public function getUsername() {
        return $this->username;
    }

    public function getPhoto_name() {
        return $this->photo_name;
    }

    public function getScore() {
        return $this->score;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDateOpinion() {
        return $this->dateOpinion;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPhoto_name($photo_name) {
        $this->photo_name = $photo_name;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function setDateOpinion($dateOpinion) {
        $this->dateOpinion = $dateOpinion;
    }

    
    
    public function getArray(){
        return array(
            "userName"=> $this->username,
            "photo_name"=> $this->photo_name,
            "score"=> $this->score,
            "comment"=> $this->comment,
            "dateOpinion"=> $this->dateOpinion
        );
    }
    
}


