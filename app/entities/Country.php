<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\entities;

/**
 * Description of Country
 *
 * @author void
 */
class Country {
    private $code;
    private $name;
    
    public function __construct() {
        
    }
    
    public function getCode() {
        return $this->code;
    }

    public function getName() {
        return $this->name;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getArray(){
        return array(
            "code"=> $this->code,
            "name"=> $this->name);
    }


}
