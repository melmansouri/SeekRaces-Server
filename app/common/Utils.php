<?php

namespace app\common;

class Utils {

    public static function generateTokenVerification() {
        $token_verification = bin2hex(openssl_random_pseudo_bytes(16));
        return $token_verification;
    }

    /**
     * Devuelve la fecha-hora actual
     * con formato yyyy-MM-dd HH:mm:ss
     * @return type
     */
    public static function nowDateTime() {
        return date("Y-m-d H:i:s");
    }

    
    public static function cifrarBCrypt($pwd) {
        $opciones = [
            'cost' => 12, //el coste del algoritmo que debería usarse
        ];
        $pwdHash = password_hash($pwd, PASSWORD_BCRYPT, $opciones);
        return $pwdHash;
    }
    
    public static function base64ToFile($base64,$type,$name){
        $filepath="";
        try{
            $data = base64_decode($base64);
        
        $filepath=$type."/".$name.".png";
        
        file_put_contents(PATH_PROJECT.$filepath, $data);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $filepath;
        
    }
    
    

}
