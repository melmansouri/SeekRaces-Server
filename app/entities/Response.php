<?php
namespace app\entities;

class Response{
    
    private $message;
    private $content;
    private $isOk;


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

        
    public function getIsOk() {
        return $this->isOk;
    }

    public function setIsOk($isOk) {
        $this->isOk = $isOk;
    }

        
    public function getArray(){
        return array("message"=> $this->message,
            "content"=> $this->content,
            "isOk"=> $this->isOk);
    }
    
}

