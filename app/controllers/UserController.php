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
            $user->setPhotoBase64($data['photoBase64']);
            $user->setCountry($data['place']);
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
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
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
                    $file_path_photo = \app\common\Utils::base64ToFile($dataUserVerification->photoBase64, \app\common\Utils::getCurrentMilliseconds());
                    $user->setPhoto_url($file_path_photo);
                }

                $user->setPlace($dataUserVerification->place);
                $user->setToken_push($dataUserVerification->token_push);
                if ($this->insertIntoUserTable($user)) {
                    $verification->deleteExistUserVerification($user->getEmail());
                    $messageResponse = '<h3>Bienvenido a SeekRaces</h3>';
                    $this->sendMailWelcome($user->getEmail(), $user->getUsername());
                }
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
            $messageResponse = '<h3>Error en la verificación</h3>';
        } catch (\PDOException $pex) {
            print $pex->getMessage();
            $messageResponse = '<h3>Error en la verificación</h3>';
        }
        return $messageResponse;
    }

    public function insertIntoUserTable($user) {
        $result = FALSE;
        try {
            $query = "INSERT INTO user(email, username, photo_url)"
                    . " VALUES"
                    . " (:email, :username, :photo_url)";

            $dataQuery = array('email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'photo_url' => $user->getPhoto_url());
            $pwd = $user->getPwd();
            if (!empty($pwd) && isset($pwd)) {
                $query = "INSERT INTO user(email, pwd, username, photo_url, place, token_push)"
                        . " VALUES"
                        . " (:email, :pwd, :username,:photo_url, :place, :token_push)";

                $dataQuery = array('email' => $user->getEmail(),
                    'pwd' => $user->getPwd(),
                    'username' => $user->getUsername(),
                    'photo_url' => $user->getPhoto_url(),
                    'place' => $user->getPlace(),
                    'token_push' => $user->getToken_push());
            }

            $result = $this->connectionDb->executeQueryWithData($query, $dataQuery);
        } catch (Exception $exc) {
            print $exc->getMessage();
        }

        return $result;
    }

    public function login($data) {
        $response = new \app\entities\Response();
        $messageResponse = 'Error en el servidor';
        $isOk = FALSE;
        $user = new \app\entities\User();
        $idTokenGoogle = $data['idTokenGoogle'];
        if (isset($idTokenGoogle) && !empty($idTokenGoogle)) {
            $response = $this->loginGoogle($data);
        } else {
            try {
                $email = $data['email'];
                $pwd = $data['pwd'];
                $token_push = $data['token_push'];
                $userFromDb = $this->checkExistUser($email);
                if (!$userFromDb) {
                    $messageResponse = "Este usuario no existe.";
                } else {

                    if ($userFromDb) {
                        $this->updatetoken_push($email, $token_push);
                        $user->setEmail($email);
                        $pwdhash = $userFromDb->pwd;
                        $messageResponse = "Las credenciales son incorrectas";
                        if (password_verify($pwd, $pwdhash)) {
                            $isOk = TRUE;
                            $messageResponse = "Bienvenido";
                            $user->setUsername($userFromDb->username);
                            $filename = $userFromDb->photo_url;
                            $user->setPhoto_url($filename);
                            $user->setPlace($userFromDb->place);
                            //$base64= \app\common\Utils::fileToBase64($filename);
                            //$user->setPhotoBase64($base64);
                            $response->setContent(json_encode($user->getArray()));
                        }
                    }
                }
            } catch (Exception $ex) {
                print $ex->getMessage();
            } catch (\PDOException $pex) {
                print $pex->getMessage();
            }

            $response->setMessage($messageResponse);
            $response->setIsOk($isOk);
        }

        return $response;
    }

    public function loginGoogle($data) {
        $response = new \app\entities\Response();
        $messageResponse = 'Error en el servidor';
        $isOk = FALSE;
        $user = new \app\entities\User();
        try {
            $email = $data['email'];
            $username = $data['username'];
            $token_push = $data['token_push'];
            $photoBase64 = $data['photoBase64'];
            $userFromDb = $this->checkExistUser($email);
            if (!$userFromDb) {
                $file_path_photo = "";
                if (isset($photoBase64) && !empty($photoBase64)) {
                    $file_path_photo = \app\common\Utils::base64ToFile($photoBase64, \app\common\Utils::getCurrentMilliseconds());
                }
                $user->setPhoto_url($file_path_photo);
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setToken_push($token_push);
                if ($this->insertIntoUserTable($user)) {
                    $isOk = TRUE;
                    $messageResponse = "Bienvenido";
                }
            } else {
                if ($userFromDb) {
                    $this->updatetoken_push($email, $token_push);
                    $isOk = TRUE;
                    $messageResponse = "bienvenido";
                    $user->setEmail($email);
                    $user->setUsername($userFromDb->username);
                    $filename = $userFromDb->photo_url;
                    $user->setPhoto_url($filename);
                }
            }
            if ($isOk) {
                $response->setContent(json_encode($user->getArray()));
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }

        $response->setMessage($messageResponse);
        $response->setIsOk($isOk);
        return $response;
    }

    public function forgotPwd($email) {
        $response = new \app\entities\Response();
        $messageResponse = 'Problemas para recuperar la contraseña';
        $isOk = FALSE;
        try {

            $userFromDb = $this->checkExistUser($email);
            if (!$userFromDb) {
                $messageResponse = "Este usuario puede que no exista";
            } else {
                $verification = new \app\controllers\VerificationController($this->connectionDb);
                if ($verification->insertUserVerificationToRestorePwd($email)) {
                    if ($verification->sendMailVerificationRestPwd($email, $userFromDb->username)) {
                        $isOk = true;
                        $messageResponse = "Se le ha enviado un correo para restaurar la contraseña.";
                    }
                }
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }

        $response->setMessage($messageResponse);
        $response->setIsOk($isOk);
        return $response;
    }

    public function editUser($data) {
        $response = new \app\entities\Response();
        $messageResponse = "No se ha podido editar tu perfil";
        $isOk = FALSE;
        try {
            $updateQuery = "UPDATE user SET username = :username, place= :place";
            $whereQuery = " WHERE "
                    . "email = :email";

            $dataQuery = array(
                "email" => $data["email"],
                "username" => $data["username"],
                "place" => $data["place"]);

            $imageName = "";
            if (isset($data["photoBase64"]) && !empty($data["photoBase64"])) {
                $file_path_photo = \app\common\Utils::base64ToFile($data["photoBase64"], $data["photo_url"]);
                $imageName = $file_path_photo;
                $updateQuery .= ", photo_url = :photo_url";
                $dataQuery["photo_url"] = $imageName;
            }
            $query = $updateQuery . $whereQuery;


            if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                $isOk = TRUE;
                $messageResponse = "Tu perfil ha sido editado con éxito";
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }


        $response->setIsOk($isOk);
        $response->setMessage($messageResponse);

        return $response;
    }

    private function checkExistUser($email) {
        $query = 'select * from user where email like :email';
        $dataQuery = array(':email' => $email);

        $result = $this->connectionDb->executeQueryWithDataFetch($query, $dataQuery);

        return $result;
    }

    private function updatetoken_push($email, $token_push) {
        if (!isset($token_push) && empty($token_push)) {
            return TRUE;
        }
        $query = "UPDATE user SET token_push = :token_push"
                . " WHERE"
                . " email=:email";

        $dataQuery = array('email' => $email,
            'token_push' => $token_push);
        $result = $this->connectionDb->executeQueryWithData($query, $dataQuery);

        return $result;
    }

    private function sendMailWelcome($addressTo, $nameTo) {
        $mail = new \app\common\Mail();
        $subject = "Confirmacion Cuenta SeekRaces";
        $body = "Bienvenido " . $nameTo . " a SeekRaces.";
        return $mail->sendMail($addressTo, $nameTo, $subject, $body);
    }

    public function resetPwd($token, $pwd) {
        $messageResponse = "No se ha podido cambiar tu contraseña";
        try {
            $verificationController = new \app\controllers\VerificationController($this->connectionDb);
            $dataUserVerificationResetPwd = $verificationController->getDataVerificationUser($token);
            if ($dataUserVerificationResetPwd) {
                $query = "UPDATE user SET pwd = :pwd WHERE "
                        . "email = :email";

                $dataQuery = array(
                    "email" => $dataUserVerificationResetPwd->email,
                    "pwd" => \app\common\Utils::cifrarBCrypt($pwd));

                if ($this->connectionDb->executeQueryWithData($query, $dataQuery)) {
                    $verificationController->deleteExistUserVerification($dataUserVerificationResetPwd->email);
                    $messageResponse = "<h3>Tu contraseña se ha cambiado con éxito</h3>";
                    $mail = new \app\common\Mail();
                    $subject = "Cambia tu contraseña en SeekRaces";
                    $body = "Tu contraseña se ha cambiado con éxito";
                    $mail->sendMail($dataUserVerificationResetPwd->email, $dataUserVerificationResetPwd->email, $subject, $body);
                }
            }
        } catch (Exception $ex) {
            print $ex->getMessage();
        } catch (\PDOException $pex) {
            print $pex->getMessage();
        }

        echo $messageResponse;
    }

}
