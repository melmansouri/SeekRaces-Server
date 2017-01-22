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

/*$app->get('/user/forgotPwd/{email}', 'forgotPwd');

$app->post('/user/restPwd', 'restPwd');*/

$app->get('/event', 'getEvents');

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
    $userDao=new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userDao->signIn($data);
    return json_encode($result->getArray());
}

function verificationSignIn(Request $request, Response $response){
    $array=$request->getQueryParams();
    $token=$array["token"];
    $userDao=new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userDao->verificationSignIn($token);
    return $result;
}

function login(Request $request, Response $response){
    $data = $request->getParsedBody();
    $userDao=new app\controllers\UserController(app\connection\ConnectionPDO::getInstance());
    $result=$userDao->login($data);
    return json_encode($result->getArray());
}

/*function forgotPwd(Request $request, Response $response,$args){
    $email = $args['email'];
    $userDao=new app\dao\UserDao(app\connection\ConnectionPDO::getInstance());
    $result=$userDao->sendMailToRestorePwd($email);
    return json_encode($result->getArray());
}*/

function addNewEvent(Request $request, Response $response){
    $data = $request->getParsedBody();
    $eventDao=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->addNewEvent($data);
    return json_encode($result->getArray());
}

function getEvents(Request $request, Response $response) {
    //$data =$request->getQueryParams();
    $data = $request->getParsedBody();
    $eventDao=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->getEvents($data);
    return json_encode($result->getArray());
}

function editEvent(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $eventDao=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->editEvent($args,$data);
    return json_encode($result->getArray());
}

function deleteEvent(Request $request, Response $response,$args) {
    $eventDao=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->deleteEvent($args);
    return json_encode($result->getArray());
}

function addNewOpinionEvent(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $opinionDao=new app\controllers\OpinionController(app\connection\ConnectionPDO::getInstance());
    $result=$opinionDao->addNewOpinionEvent($args,$data);
    return json_encode($result->getArray());
}

function getEventReviews(Request $request, Response $response,$args) {
    $opinionDao=new app\controllers\OpinionController(app\connection\ConnectionPDO::getInstance());
    $result=$opinionDao->getEventReviews($args);
    return json_encode($result->getArray());
}

function editEventOpinion(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $eventDao=new app\controllers\OpinionController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->updateOpinion($args,$data);
    return json_encode($result->getArray());
}

function addEventToFavorite(Request $request, Response $response){
    $data = $request->getParsedBody();
    $eventDao=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->addNewEvent($data);
    return json_encode($result->getArray());
}

function getEventsFavorites(Request $request, Response $response) {
    $data =$request->getQueryParams();
    $eventDao=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->getEvent($data);
    return json_encode($result->getArray());
}

function deleteEventFromFavorites(Request $request, Response $response,$args) {
    $eventDao=new app\controllers\EventController(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->deleteEvent($args);
    return json_encode($result->getArray());
}