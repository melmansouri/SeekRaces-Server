<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\entities;

/**
 * Description of City
 *
 * @author void
 */
class City {
    private $name;
    private $countryCode;
    
    public function __construct() {
        
    }
    
    public function getName() {
        return $this->name;
    }

    public function getCountryCode() {
        return $this->countryCode;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
    }

    public function getArray(){
        return array(
            "name"=> $this->name,
            "countryCode"=> $this->countryCode);
    }

}
