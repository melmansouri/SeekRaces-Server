<?php
namespace app\dto;

class EventVote{
    private $username;
    private $score;
    private $comment;
    private $dateTimeVote;
    
    public function __construct() {
        
    }
    
    function getUsername() {
        return $this->username;
    }

    function getScore() {
        return $this->score;
    }

    function getComment() {
        return $this->comment;
    }

    function getDateTimeVote() {
        return $this->dateTimeVote;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setScore($score) {
        $this->score = $score;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setDateTimeVote($dateTimeVote) {
        $this->dateTimeVote = $dateTimeVote;
    }
}

?>

