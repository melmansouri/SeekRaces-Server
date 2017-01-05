<?php

namespace app\config;

class Config{
    public function __construct() {
        
    }
    
    public static function genericAutoLoader($classname){
        $namespace = explode("\\" , $classname)[0];
    $filename = $classname .".php";
    $dir = dirname(__DIR__);
    require_once( $dir . '\\' .  $filename);
    }

}
