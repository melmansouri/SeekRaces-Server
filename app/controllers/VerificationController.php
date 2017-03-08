<?php

namespace app\controllers;

class VerificationController {

    private $connectionDb;
    private $token_verification;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function insertSignInUserVerification($user) {
        try {
            $this->deleteExistUserVerification($user->getEmail());

            $this->token_verification = \app\common\Utils::generateTokenVerification();
            $creation_dateTime = \app\common\Utils::nowDateTime();

            $query = "INSERT INTO verification (email, pwd, username, photoBase64, place, token_push, token_verification, creation_datetime)"
                    . " VALUES "
                    . "(:email, :pwd, :username, :photoBase64, :place, :token_push,:token_verification, :creation_datetime)";

            $dataQuery = array('email' => $user->getEmail(),
                'pwd' => \app\common\Utils::cifrarBCrypt($user->getPwd()),
                'username' => $user->getUsername(),
                'photoBase64' => $user->getPhotoBase64(),
                'place' => $user->getPlace(),
                'token_push' => $user->getToken_push(),
                'token_verification' => $this->token_verification,
                'creation_datetime' => $creation_dateTime);

            $result = $this->connectionDb->executeQueryWithData($query, $dataQuery);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        } catch (\PDOException $pex) {
            throw $pex;
        }
    }

    public function insertUserVerificationToRestorePwd($email) {
        try {
            $this->deleteExistUserVerification($email);

        $this->token_verification = \app\common\Utils::generateTokenVerification();
        $creation_dateTime = \app\common\Utils::nowDateTime();

        $query = "INSERT INTO verification(email, token_verification, creation_datetime)"
                . " VALUES"
                . " (:email,:token_verification, :creation_datetime)";

        $dataQuery = array("email" => $email,
            'token_verification' => $this->token_verification,
            'creation_datetime' => $creation_dateTime);

        $result = $this->connectionDb->executeQueryWithData($query, $dataQuery);

        return $result;
        } catch (Exception $ex) {
            throw $ex;
        }catch (\PDOException $pex) {
            throw $pex;
        }
    }

    public function deleteExistUserVerification($email) {
        try{
            $query = "delete from verification where email like :email";
        $dataQuery = array('email' => $email);

        $result = $this->connectionDb->executeQueryWithData($query, $dataQuery);

        return $result;
        } catch (Exception $ex) {
            throw $ex;
        }catch (\PDOException $pex) {
            throw $pex;
        }
    }

    public function getDataVerificationUser($token) {
        try {
            $query = "select * from verification where token_verification = :token_verification";

            $dataQuery = array(':token_verification' => $token);

            $result = $this->connectionDb->executeQueryWithDataFetch($query, $dataQuery);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        } catch (\PDOException $pex) {
            throw $pex;
        }
    }

    public function sendMailVerificationRestPwd($addressTo, $nameTo) {
        try{
            $mail = new \app\common\Mail();
        $subject = "Cambia tu password en SeekRaces";
        $url_confirmacion = "https://seekraces.tk/SeekRaces/reset.php?token=" . $this->token_verification;
        $body = $this->generateBodyToSendMailToVerificationResetPwd($url_confirmacion);
        return $mail->sendMail($addressTo, $nameTo, $subject, $body);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function sendMailVerification($addressTo, $nameTo) {
        try{
            $mail = new \app\common\Mail();
        $subject = "Confirmar registro en SeekRaces";
        $url_confirmacion = "https://seekraces.tk/SeekRaces/user/verification?token=" . $this->token_verification;
        //$url_confirmacion = "http://192.168.105.18:8080/SeekRaces/user/verification?token=" . $this->token_verification;
        $body = $this->generateBodyToSendMailToVerificationSignIn($url_confirmacion);
        return $mail->sendMail($addressTo, $nameTo, $subject, $body);
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }

    private function generateBodyToSendMailToVerificationResetPwd($urlConfimation) {
        $stringBoton=htmlentities("botón", ENT_QUOTES,'UTF-8');
        $body = "Presiona el siguiente $stringBoton para ir al formulario de cambio de contrase&ntilde;a:<br><br>"
                . "<a style=\"white-space:nowrap;display:block;padding:10px 25px;background:#00BCD4;"
                . "color:#ffffff;font-family:Helvetica Neue, Arial, sans-serif;"
                . "font-size:15px;line-height:15px;font-weight:bold;"
                . "text-decoration:none;border-collapse:collapse;"
                . "border-color:#82a313;border-style:1px solid;border-radius:3px;\" "
                . "href=\"$urlConfimation\" target=\"_blank\">
                        Cambiar la contrase&ntilde;a</a>";

        return $body;
    }

    private function generateBodyToSendMailToVerificationSignIn($urlConfimation) {
        $stringVerificacion=htmlentities("verificación", ENT_QUOTES,'UTF-8');
        $stringBoton=htmlentities("botón", ENT_QUOTES,'UTF-8');
        $body = "<br />Tienes 24 horas para completar la $stringVerificacion "
                . "de la cuenta en 'SeekRaces'."
                . "<br>Para completar tu registro debes de pulsar en el siguiente $stringBoton:<br/>"
                . "<a style=\"white-space:nowrap;display:block;padding:10px 25px;background:#00BCD4;"
                . "color:#ffffff;font-family:Helvetica Neue, Arial, sans-serif;"
                . "font-size:15px;line-height:15px;font-weight:bold;"
                . "text-decoration:none;border-collapse:collapse;"
                . "border-color:#82a313;border-style:1px solid;border-radius:3px;\" "
                . "href=\"$urlConfimation\" target=\"_blank\">
                        Confirmar Email</a>";

        return $body;
    }

}
