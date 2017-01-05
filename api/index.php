<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

/**
 * Permite la inyeccion de nuestros propios ficheros .php
 * ahorrandonos usar require_one or require or include or include_once 
 * 'className.php' por cada clase
 */
spl_autoload_register(function ($classname) {
    $namespace = explode("\\" , $classname)[0];
    $filename = $classname .".php";
    $dir = dirname(__DIR__);
    require_once( $dir . '\\' .  $filename);
  
});

$config['displayErrorDetails'] = true;


$app = new \Slim\App(["settings" => $config]);

$app->get('/', 'index');

$app->run();

function index() {
    echo "
        <h1>BIKEHELPER</h1>
    ";
}

/*require_once "config.php";
require_once 'Slim/Slim.php';
require_once './lib/mailer_smtp/class.phpmailer.php';
require_once './lib/mailer_smtp/class.smtp.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/', 'index');

//usuario
$app->post('/user/signIn', 'signIn');
$app->get('/user/login', 'login');

//ruta
$app->get('/ruta/ObtenerRutas/:idUsuario', 'getRutas');
$app->post('/ruta/InsertarRuta', 'insertarRuta');
$app->put('/ruta/ActualizarRuta/:idRuta', 'actualizarRuta');
$app->delete('/ruta/BorrarRuta/:idRuta', 'eliminarRuta');

//publicacion
$app->get('/publicacion/todasPublicaciones/:idUsuario', 'getPublicacionesNoPropias');
$app->get('/publicacion/publicacionPropia/:idUsuario', 'getPublicacionesPropias');
$app->post('/publicacion/publicar', 'publicar');
//$app->put('/publicacion/:idRuta', 'actualizarRuta');
$app->delete('/publicacion/borrarPublicacion/:idPublicacion', 'eliminarPublicacion');

//desperfecto
$app->get('/desperfecto/obtenerDesperfectos/:idRuta', 'getDesperfectosRuta');
$app->post('/desperfecto/insertarDesperfecto', 'insertarDesperfecto');

//coordenada
$app->get('/coordenada/obtenerCoordenada/:idRuta', 'getCoordenadasRuta');
$app->post('/coordenada/insertarCoordenada', 'insertarCoordenada');

$app->run();



// <editor-fold defaultstate="collapsed" desc="USUARIO">
//POST
function registrarse() {
    $dbHandler_usuario = new DBHandler_Usuario();
    $request = \Slim\Slim::getInstance()->request();
    $usuario = json_decode($request->getBody());
    $response = $dbHandler_usuario->createUser($usuario);
    echo json_encode($response);
}

//GET
function login() {
    $dbHandler_usuario = new DBHandler_Usuario();
    $request = \Slim\Slim::getInstance()->request();
    $usuario = json_decode($request->getBody());
    $response = $dbHandler_usuario->checkLogin($usuario->getEmail(), $usuario->getPassword());

    echo json_encode($response);
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="RUTA">
////GET
function getRutas($idUsuario) {
    $dbHandler_ruta = new DBHandler_Ruta();
    $response = $dbHandler_ruta->obtenerRutas($idUsuario);
    echo json_encode($response);
}

//POST
function insertarRuta() {
    $dbHandler_ruta = new DBHandler_Ruta();
    $request = \Slim\Slim::getInstance()->request();
    $usuario = json_decode($request->getBody());
    $response = $dbHandler_ruta->createRuta($usuario->getId());

    echo json_encode($response);
}

//PUT
function actualizarRuta($IdRuta) {
    $dbHandler_ruta = new DBHandler_Ruta();
    $request = \Slim\Slim::getInstance()->request();
    $ruta = json_decode($request->getBody());
    $response = $dbHandler_ruta->updateRuta($IdRuta,$ruta);

    echo json_encode($response);
}

//DELETE
function eliminarRuta($IdRuta) {
    $dbHandler_ruta = new DBHandler_Ruta();
    $response = $dbHandler_ruta->deleteRuta($IdRuta);
    echo json_encode($response);
}
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="PUBLICACION">
////GET
function getPublicacionesPropias($idUsuario) {
    $dbHandler_publicacion = new DBHandler_Publicacion();
    $response = $dbHandler_publicacion->obtenerPublicacionesPropias($idUsuario);

    echo json_encode($response);
}

////GET
function getPublicacionesNoPropias($idUsuario) {
    $dbHandler_publicacion = new DBHandler_Publicacion();
    $response = $dbHandler_publicacion->obtenerTodasMenosPropias($idUsuario);

    echo json_encode($response);
}

//POST
function publicar() {
    $dbHandler_publicacion = new DBHandler_Publicacion();
    $request = \Slim\Slim::getInstance()->request();
    $publicacion = json_decode($request->getBody());
    $response = $dbHandler_publicacion->insertpublicacion($publicacion);

    echo json_encode($response);
}

//PUT
/*function actualizarPublicacion($IdRuta) {
    $dbHandler_publicacion = new DBHandler_Publicacion();
    $request = \Slim\Slim::getInstance()->request();
    $ruta = json_decode($request->getBody());
    $response = $dbHandler_ruta->updateRuta($IdRuta, $ruta->velocidadMedia, $ruta->distancia, $ruta->fecha, $ruta->duracion);

    echo json_encode($response);
}*/

//DELETE
/*function eliminarPublicacion($IdPublicacion) {
    $dbHandler_publicacion = new DBHandler_Publicacion();
    $response = $dbHandler_publicacion->deletePublicacion($IdPublicacion);
    echo json_encode($response);
}*/
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="DESPERFECTOS">
////GET
/*function getDesperfectosRuta($idRuta) {
    $dbHandler_desperfecto = new DBHandler_Desperfecto();
    $response = $dbHandler_desperfecto->obtenerDesperfectos($idRuta);

    echo json_encode($response);
}*/

//POST
/*function insertarDesperfecto() {
    $dbHandler_desperfecto = new DBHandler_Desperfecto();
    $request = \Slim\Slim::getInstance()->request();
    $desperfecto = json_decode($request->getBody());
    $response = $dbHandler_desperfecto->insertDesperfecto($desperfecto);
    echo json_encode($response);
}*/


//DELETE
/*function eliminarDescperfecto($IdPublicacion) {
    $dbHandler_publicacion = new DBHandler_Publicacion();
    $response = $dbHandler_publicacion->deletePublicacion($IdPublicacion);
    echo json_encode($response);
}*/
// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="COORDENADAS">
////GET
/*function getCoordenadasRuta($idRuta) {
    $dbHandler_coordenada = new DBHandler_Coordenada();
    $response = $dbHandler_coordenada->obtenerCoordenadas($idRuta);

    echo json_encode($response);
}*/
//POST
/*function insertarCoordenada() {
    $dbHandler_coordenada = new DBHandler_Coordenada();
    $request = \Slim\Slim::getInstance()->request();
    $coordenada = json_decode($request->getBody());
    $response = $dbHandler_coordenada->insertCoordenada($coordenada);

    echo json_encode($response);
}*/
// </editor-fold>

