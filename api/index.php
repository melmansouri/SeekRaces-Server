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
            $pathname.="\\".$classname;
        }else{
           $countNameSpacesApp=1;
           $pathname="\\".$classname;
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

$app->get('/event', 'getEvent');

$app->post('/event', 'addNewEvent');

$app->delete('/event/{id}', 'deleteEvent');

$app->post('/event/{id}/reviews', 'addNewOpinionEvent');

$app->get('/event/{id}/reviews', 'getEventReviews');

//$app->put('/event/{id}/reviews', 'editEventOpinion');

$app->run();

function index() {
    echo "
        <h1>SEEKRACES</h1>
    ";
}

function signIn(Request $request, Response $response){ 
    $data = $request->getParsedBody();
    $userDao=new app\dao\UserDao(app\connection\ConnectionPDO::getInstance());
    $result=$userDao->signIn($data);
    return json_encode($result->getArray());
}

function verificationSignIn(Request $request, Response $response){
    $array=$request->getQueryParams();
    $token=$array["token"];
    $userDao=new app\dao\UserDao(app\connection\ConnectionPDO::getInstance());
    $result=$userDao->verificationSignIn($token);
    return $result;
}

function login(Request $request, Response $response){
    $data = $request->getParsedBody();
    $userDao=new app\dao\UserDao(app\connection\ConnectionPDO::getInstance());
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
    $eventDao=new app\dao\EventDao(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->addNewEvent($data);
    return json_encode($result->getArray());
}

function getEvent(Request $request, Response $response) {
    $data =$request->getQueryParams();
    $eventDao=new app\dao\EventDao(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->getEvent($data);
    return json_encode($result->getArray());
}

function addNewOpinionEvent(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $eventDao=new app\dao\OpinionDao(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->addNewOpinionEvent($args,$data);
    return json_encode($result->getArray());
}

function getEventReviews(Request $request, Response $response,$args) {
    $eventDao=new app\dao\OpinionDao(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->getEventReviews($args);
    return json_encode($result->getArray());
}

/*function editEventOpinion(Request $request, Response $response,$args) {
    $data = $request->getParsedBody();
    $eventDao=new app\dao\OpinionDao(app\connection\ConnectionPDO::getInstance());
    $result=$eventDao->updateOpinion($args,$data);
    return json_encode($result->getArray());
}*/

