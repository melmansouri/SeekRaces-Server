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

            file_put_contents($root_path_project . $nameFile, $data);
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

    public static function send_notification($tokens, $message) {
        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => $tokens,
                'data' =>$message
            );
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key=AAAAxvbkOFU:APA91bGMWDoz1xiWT-vWs6RsbBK2bt_ZX4RxjRXw7FnLnQjGvNaHLupXxhqi2ULNtH3F9yzKzcoGb89SEs2l03Ex3Zi3WeZvxTi-qHK-RcJ3qV2FNjYTNd-EECVtNfZtb69nUdtgX3TB'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (Exception $ex) {
            print $ex->getMessage();
            throw $ex;
        }
    }

}
