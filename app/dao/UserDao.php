<?php

namespace app\dao;

class UserDao {

    private $connectionDb;

    public function __construct($connectionDb) {
        $this->connectionDb = $connectionDb;
    }

    public function signIn($data) {
        $response = new \app\dto\Response();
        $messageResponse = 'Error al registrarse';
        $isOk = FALSE;
        try {
            $user = new \app\dto\User();
            $user->setEmail($data['email']);
            $user->setPwd($data['pwd']);
            $user->setUsername($data['username']);
            $user->setPhoto($data['photo']);

            if ($this->checkExistUser($user->getEmail())) {
                $messageResponse = "Ya existe un usuario con este correo";
            } else {
                $verification = new \app\dao\VerificationDao($this->connectionDb);
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
            
        }
        $response->setMessage($messageResponse);
        $response->setIsOk($isOk);
        return $response;
    }

    public function verificationSignIn($token) {
        $messageResponse = '<h3>Esta verificacion ya no es válida</h3>';
        try {
            $verification = new \app\dao\VerificationDao($this->connectionDb);
            $user = new \app\dto\User();

            $dataUserVerification = $verification->getDataVerificationUser($token);
            if ($dataUserVerification) {
                $user->setEmail($dataUserVerification->email);
                $user->setPwd($dataUserVerification->pwd);
                $user->setUsername($dataUserVerification->username);
                $user->setPhoto($dataUserVerification->photo);
                if ($this->insertIntoUserTable($user)) {
                    $verification->deleteExistUserVerification($user->getEmail());
                    $messageResponse = '<h3>Bienvenido a SeekRaces</h3>';
                    $this->sendMailWelcome($user->getEmail(), $user->getUsername());
                }
            }
        } catch (Exception $ex) {
            $messageResponse = '<h3>Error en la verificación</h3>';
        }

        return $messageResponse;
    }

    public function insertIntoUserTable($user) {
        $query = "INSERT INTO user(email, pwd, username, photo)"
                . " VALUES"
                . " (:email, :pwd, :username, :photo)";

        $dataQuery = array('email' => $user->getEmail(),
            'pwd' => $user->getPwd(),
            'username' => $user->getUsername(),
            'photo' => $user->getPhoto());
        $result = $this->connectionDb->executeQueryWithData($query, $dataQuery);

        return $result;
    }

    public function login($data) {
        $response = new \app\dto\Response();
        $messageResponse = 'Error en el servidor';
        $isOk = FALSE;
        $user = new \app\dto\User();
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
                        $user->setPhoto($userFromDb->photo);
                        $response->setContent($user->getArray());
                    }
                }
            }
        } catch (Exception $ex) {
        }
        $response->setMessage($messageResponse);
        $response->setIsOk($isOk);
        return $response;
    }

    /* private function getUserWithCredentials($email,$pwd) {
      $pwdHash=\app\common\Utils::cifrarBCrypt($pwd);
      $query = 'select * from user where email like :email and pwd = :pwd';
      $dataQuery = array("email" => $email,
      "pwd"=> $pwdHash);

      $result = $this->connectionDb->executeQueryWithDataFetch($query, $dataQuery);

      return $result;
      } */

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
