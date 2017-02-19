<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
/**
 * Permite la inyeccion de nuestros propios ficheros .php
 * ahorrandonos usar require_one or require or include or include_once 
 * 'className.php' por cada clase
 */
spl_autoload_register(function ($classname) {
    $array = explode("\\", $classname);
    $countNameSpacesApp=0;
    $pathname='';
    for ($i = 0; $i < count($array); $i++) {
        $classname=$array[$i];
        if ($classname == "app") {
            $countNameSpacesApp++;
        }
        if ($countNameSpacesApp==1) {
            $pathname.="/".$classname;
        }else{
           $countNameSpacesApp=1;
           $pathname="/".$classname;
        }
    }
    $pathname = $pathname . ".php";
    $dir = dirname(__DIR__);
    require_once( $dir . $pathname);
});

$config['displayErrorDetails'] = true;


$app = new \Slim\App(["settings" => $config]);

$app->get('/', 'index');

$app->post('/user/signIn', 'signIn');

$app->get('/user/verification', 'verificationSignIn');

$app->post('/user/login', 'login');

$app->put('/user', 'editUser');

$app->get('/user/{email}/forgotPassword', 'forgotPwd');

$app->get('/event', 'getEvents');

$app->get('/user/{email}/event', 'getOwnEvents');

$app->post('/event', 'addNewEvent');

$app->delete('/event/{id}', 'deleteEvent');

$app->put('/event/{id}', 'editEvent');

$app->post('/event/{id}/reviews', 'addNewOpinionEvent');

$app->get('/event/{id}/reviews', 'getEventReviews');

$app->put('/user/{email}/event/{id}/reviews', 'editEventOpinion');

$app->post('/user/event/favorites', 'addEventToFavorite');

$app->get('/user/{email}/event/favorites', 'getEventsFavorites');

$app->delete('/user/{email}/event/{id}/favorites', 'deleteEventFromFavorites');

$app->run();

function index() {
    echo "
        <h1>SEEKRACES</h1>
    ";
}

function signIn(Request $request, Response $response){ 
    $data = $request->getParsedBody();
    $userController=new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userController->signIn($data);
    return json_encode($result->getArray());
}

function verificationSignIn(Request $request, Response $response){
    $array=$request->getQueryParams();
    $token=$array["token"];
    $userController=new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userController->verificationSignIn($token);
    return $result;
}

function login(Request $request, Response $response){
    $data = $request->getParsedBody();
    $userController=new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userController->login($data);
    return json_encode($result->getArray());
}

function editUser(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $userController=new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userController->editUser($data);
    return json_encode($result->getArray());
}

function forgotPwd(Request $request, Response $response,$args){
    $email = $args['email'];
    $userController=new app\Controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userController->forgotPwd($email);
    return json_encode($result->getArray());
}

function addNewEvent(Request $request, Response $response){
    $data = $request->getParsedBody();
    $eventController=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventController->addNewEvent($data);
    return json_encode($result->getArray());
}

function getEvents(Request $request, Response $response) {
    $data =$request->getQueryParams();
    //$data = $request->getParsedBody();
    $eventController=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventController->getEvents($data);
    return json_encode($result->getArray());
}

function getOwnEvents(Request $request, Response $response,$args) {
    $eventController=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventController->getOwnEvents($args);
    return json_encode($result->getArray());
}

function editEvent(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $eventController=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventController->editEvent($args,$data);
    return json_encode($result->getArray());
}

function deleteEvent(Request $request, Response $response,$args) {
    $eventController=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventController->deleteEvent($args);
    return json_encode($result->getArray());
}

function addNewOpinionEvent(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $opinionController=new app\controllers\OpinionController(app\connection\ConnectionPDO::getInstance());
    $result=$opinionController->addNewOpinionEvent($args,$data);
    return json_encode($result->getArray());
}

function getEventReviews(Request $request, Response $response,$args) {
    $opinionController=new app\controllers\OpinionController(app\connection\ConnectionPDO::getInstance());
    $result=$opinionController->getEventReviews($args);
    return json_encode($result->getArray());
}

function editEventOpinion(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $eventController=new app\controllers\OpinionController(app\connection\ConnectionPDO::getInstance());
    $result=$eventController->updateOpinion($args,$data);
    return json_encode($result->getArray());
}

function addEventToFavorite(Request $request, Response $response){
    $data = $request->getParsedBody();
    $favoriteController=new app\controllers\FavoriteController(app\connection\ConnectionPDO::getInstance());
    $result=$favoriteController->addEventToFavorite($data);
    return json_encode($result->getArray());
}

function getEventsFavorites(Request $request, Response $response,$args) {
    $favoriteController=new app\controllers\FavoriteController(app\connection\ConnectionPDO::getInstance());
    $result=$favoriteController->getEventsFavorites($args);
    return json_encode($result->getArray());
}

function deleteEventFromFavorites(Request $request, Response $response,$args) {
    $favoriteController=new app\controllers\FavoriteController(app\connection\ConnectionPDO::getInstance());
    $result=$favoriteController->deleteEventFromFavorites($args);
    return json_encode($result->getArray());
}