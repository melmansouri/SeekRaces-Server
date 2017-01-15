<?php

namespace app\controllers;

class UserController {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function signIn($data) {
        $response = new \app\entities\Response();
        $messageResponse = 'Error al registrarse';
        $isOk = FALSE;
        try {
            $user = new \app\entities\User();
            $user->setEmail($data['email']);
            $user->setPwd($data['pwd']);
            $user->setUsername($data['username']);
            $user->setPhotoBas64($data['photoBase64']);
            $user->setCountry($data['country']);
            $user->setToken_push($data['token_push']);

            if ($this->checkExistUser($user->getEmail())) {
                $messageResponse = "Ya existe un usuario con este correo";
            } else {
                $verification = new \app\controllers\VerificationController($this->connectionDb);
                if ($verification->insertSignInUserVerification($user)) {
                    if ($verification->sendMailVerification($user->getEmail(), $user->getUsername())) {
                        $isOk = TRUE;
                        $messageResponse = "Se le ha enviado un correo de confirmación.";
                    } else {
                        $messageResponse = "No se ha podido enviar el correo de verificación";
                    }
                }
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }

        $response->setMessage($messageResponse);
        $response->setIsOk($isOk);
        return $response;
    }

    public function verificationSignIn($token) {
        $messageResponse = '<h3>Esta verificacion ya no es válida</h3>';
        try {
            $verification = new \app\controllers\VerificationController($this->connectionDb);
            $user = new \app\entities\User();

            $dataUserVerification = $verification->getDataVerificationUser($token);
            if ($dataUserVerification) {
                $user->setEmail($dataUserVerification->email);
                $user->setPwd($dataUserVerification->pwd);
                $user->setUsername($dataUserVerification->username);
                if (!empty($dataUserVerification->photoBase64) && isset($dataUserVerification->photoBase64)) {
                    $file_path_photo = \app\common\Utils::base64ToFile($dataUserVerification->photoBase64, TYPE_PROFILE, microtime());
                    $user->setPhoto_url($file_path_photo);
                }

                $user->setCountry($dataUserVerification->country);
                $user->setToken_push($dataUserVerification->token_push);
                if ($this->insertIntoUserTable($user)) {
                    $verification->deleteExistUserVerification($user->getEmail());
                    $messageResponse = '<h3>Bienvenido a SeekRaces</h3>';
                    $this->sendMailWelcome($user->getEmail(), $user->getUsername());
                }
            }
        } catch (Exception $ex) {
            $messageResponse = '<h3>Error en la verificación</h3>';
        } catch (\PDOException $pex) {
            $messageResponse = '<h3>Error en la verificación</h3>';
        }
        return $messageResponse;
    }

    public function insertIntoUserTable($user) {
        $query = "INSERT INTO user(email, pwd, username, photo_url, country, token_push)"
                . " VALUES"
                . " (:email, :pwd, :username, :photo_url, :country, :token_push)";

        $dataQuery = array('email' => $user->getEmail(),
            'pwd' => $user->getPwd(),
            'username' => $user->getUsername(),
            'photo_url' => $user->getPhoto_url(),
            'country' => $user->getCountry(),
            'token_push' => $user->getToken_push());
        $result = $this->connectionDb->executeQueryWithData($query, $dataQuery);

        return $result;
    }

    public function login($data) {
        $response = new \app\entities\Response();
        $messageResponse = 'Error en el servidor';
        $isOk = FALSE;
        $user = new \app\entities\User();
        try {
            $email = $data['email'];
            $pwd = $data['pwd'];

            if (!$this->checkExistUser($email)) {
                $messageResponse = "Este usuario no existe.";
            } else {
                $userFromDb = $this->checkExistUser($email);
                $messageResponse = "Las credenciales son incorrectas";
                if ($userFromDb) {
                    $isOk = TRUE;
                    $user->setEmail($email);
                    $pwdhash = $userFromDb->pwd;
                    if (password_verify($pwd, $pwdhash)) {
                        $messageResponse = "Bienvenido";
                        $user->setUsername($userFromDb->username);
                        $user->setPhoto_url($userFromDb->photo_url);
                        $user->setPhotoBas64($userFromDb->photoBase64);
                        $response->setContent($user->getArray());
                    }
                }
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }

        $response->setMessage($messageResponse);
        $response->setIsOk($isOk);
        return $response;
    }

    public function sendMailToRestorePwd($email) {
        $response = new \app\entities\Response();
        $messageResponse = 'Problemas para recuperar la contraseña';
        $isOk = FALSE;
        try {

            if (!$this->checkExistUser($email)) {
                $messageResponse = "Este usuario puede que no exista.";
            } else {
                $verification = new \app\controllers\VerificationController($this->connectionDb);
                if ($verification->insertUserVerificationToRestorePwd($email)) {
                    if ($verification->sendMailVerificationRestPwd($addressTo, $nameTo)) {
                        $isOk = true;
                        $messageResponse = "Se le ha enviado un correo para restaurar la contraseña.";
                    }
                }
            }
        } catch (Exception $ex) {
            
        } catch (\PDOException $pex) {
            
        }

        $response->setMessage($messageResponse);
        $response->setIsOk($isOk);
        return $response;
    }

    private function checkExistUser($email) {
        $query = 'select * from user where email like :email';
        $dataQuery = array(':email' => $email);

        $result = $this->connectionDb->executeQueryWithDataFetch($query, $dataQuery);

        return $result;
    }

    private function sendMailWelcome($addressTo, $nameTo) {
        $mail = new \app\common\Mail();
        $subject = "Confirmacion Cuenta SeekRaces";
        $body = "Bienvenido " . $nameTo . " a SeekRaces.";
        return $mail->sendMail($addressTo, $nameTo, $subject, $body);
    }

}
