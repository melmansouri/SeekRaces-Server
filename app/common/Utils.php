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

    public static function base64ToFile($base64, $name) {
        $nameFile = "";
        try {
            $root_path_project = dirname(dirname(__DIR__)) . "/pictures/";
            self::createDirectory($root_path_project);
            $data = base64_decode($base64);

            $nameFile = $name . ".png";

            file_put_contents($root_path_project ."/". $nameFile, $data);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $nameFile;
    }

    public static function fileToBase64($filename) {
        $base64 = "";
        try {
            if (isset($filename) && !empty($filename)) {
                $root_path_project = dirname(dirname(__DIR__)) . "/pictures/" . $filename;
                $data = file_get_contents($root_path_project);
                $base64 = base64_encode($data);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $base64;
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
