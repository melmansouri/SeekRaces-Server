<?php
namespace app\common;

class Utils{
    
    
    public static function generateTokenVerification(){
        $token_verification = bin2hex(openssl_random_pseudo_bytes(16));
        return $token_verification;
    }
    
    /**
     * Devuelve la fecha-hora actual
     * con formato yyyy-MM-dd HH:mm:ss
     * @return type
     */
    public static function now(){
        return date("Y-m-d H:i:s");
    }
    
    public static function cifrarBCrypt($pwd) {
    $opciones = [
            'cost' => 12,//el coste del algoritmo que debería usarse
        ];
        $pwdHash = password_hash($pwd, PASSWORD_BCRYPT, $opciones);
        return $pwdHash;
}
}
