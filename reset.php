<?php

require_once './app/controllers/UserController.php';
require_once './app/common/Mail.php';
require_once './app/common/Utils.php';
require_once './app/connection/ConnectionPDO.php';
require_once './app/controllers/VerificationController.php';
require './vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

if (isset($_GET['token'])) {
    if (isset($_POST['newPassword'])) {
        $userController = new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
        $result = $userController->resetPwd($_GET['token'],$_POST['newPassword']);
    } else {

        $verificationController = new app\controllers\VerificationController(app\connection\ConnectionPDO::getInstance());
        $result = $verificationController->getDataVerificationUser($_GET['token']);
        if (!$result) {

            echo '<h3>Tu vínculo de reinicio de la contraseña no es válido, o ya se ha utilizado</h3>';
        } else {
            echo '<html>
    <head>
        <title>Cambio de contraseña SeekRaces</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/reset.css" />
    </head>
    <body>
        <form method="post" name="frmChange" action="reset.php?token=' . $_GET['token'] . ' "onSubmit="return validatePassword()">
            <table border="0" cellpadding="10" cellspacing="0" width="500" align="center" class="tblSaveForm">
                <tr class="tableheader">
                    <td colspan="2">Formulario de cambio de contraseña</td>
                </tr>
                <tr>
                    <td><label>la nueva contraseña</label></td>
                    <td><input type="password" name="newPassword" class="txtField" /><span id="newPassword" class="required"></span></td>
                </tr>
                <td><label>Confirmar la nueva contraseña</label></td>
                <td><input type="password" name="confirmPassword" class="txtField" /><span id="confirmPassword" class="required"></span></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" value="Guardar" class="btnSubmit"></td>
                </tr>
            </table>
        </form>

        <script>
            function validatePassword() {
                var newPassword, confirmPassword, output = true;

                newPassword = document.frmChange.newPassword;
                confirmPassword = document.frmChange.confirmPassword;

                if (!newPassword.value) {
                    newPassword.focus();
                    document.getElementById("newPassword").innerHTML = "No puede estar vacío";
                    output = false;
                }else{
                    if (newPassword.value.length < 6) {
                        document.getElementById("newPassword").innerHTML = "La longitud mínima son 6 caracteres alfanuméricos";
                        output = false;
                    }else{
                        document.getElementById("newPassword").innerHTML = "";
                        output = true;
                    }
                } 
                
                if (!confirmPassword.value) {
                    confirmPassword.focus();
                    document.getElementById("confirmPassword").innerHTML = "No puede estar vacío";
                    output = false;
                }else{
                    if (confirmPassword.value.length < 6) {
                        document.getElementById("confirmPassword").innerHTML = "La longitud mínima son 6 caracteres alfanuméricos";
                        output = false;
                    }else{
                        document.getElementById("confirmPassword").innerHTML = "";
                        output = true;
                    }
                }
                if ((output) && (newPassword.value !== confirmPassword.value)) {
                    newPassword.value = "";
                    confirmPassword.value = "";
                    newPassword.focus();
                    document.getElementById("confirmPassword").innerHTML = "Las contraseñas deben coincidir";
                    output = false;
                }else if ((output) && (newPassword.value !== confirmPassword.value)){
                    document.getElementById("confirmPassword").innerHTML = "";
                    output = true;
                }
                return output;
            }
        </script>
    </body>
</html>';
        }
    }
}else{
    echo '<h3>No puedes estar aqui</h3>';
}
