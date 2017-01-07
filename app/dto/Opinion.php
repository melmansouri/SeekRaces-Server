<?php
namespace app\dto;

class Opinion{
    private $username;
    private $photo;
    private $score;
    private $comment;
    private $dateTimeVote;
    
    public function __construct() {
        
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function getScore() {
        return $this->score;
    }

    public function getComment() {
        return $this->comment;
    }

    public function getDateTimeVote() {
        return $this->dateTimeVote;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function setDateTimeVote($dateTimeVote) {
        $this->dateTimeVote = $dateTimeVote;
    }


    public function getArray(){
        return array(
            "username"=> $this->username,
            "photo"=> $this->photo,
            "score"=> $this->score,
            "comment"=> $this->comment,
            "dateTimeVote"=> $this->dateTimeVote
        );
    }
    
}


