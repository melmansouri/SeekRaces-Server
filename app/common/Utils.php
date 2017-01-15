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

    public static function base64ToFile($base64, $type, $name) {
        $filepath = "";
        try {
            $root_path_project = dirname(dirname(__DIR__)) . "\\pictures" . "\\".$type . "\\";
            self::createDirectory($root_path_project);
            $data = base64_decode($base64);

            $filepath = $type . "\\" . $name . ".png";

            file_put_contents($root_path_project . $name . ".png", $data);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $filepath;
    }

    public static function createDirectory($path) {
        if (!is_dir($path)) {
            //Directory does not exist, so lets create it.
            mkdir($path, 0755);
        }
    }

    public static function getCurrentMilliseconds() {
        return round(microtime(true) * 1000);
    }

}
