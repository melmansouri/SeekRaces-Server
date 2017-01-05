<?php
namespace app\dto;

class Response{
    
    private $message;
    private $content;


    public function __construct() {
        
    }
    
    public function getMessage() {
        return $this->message;
    }

    public function getContent() {
        return $this->content;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setContent($content) {
        $this->content = $content;
    }
    
    public function getArray(){
        return array("message"=> $this->message,
            "content"=> $this->content);
    }
    
}

