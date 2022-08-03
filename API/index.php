<?php
declare(strict_types=1);

session_start();

require_once "../utils/APICall.php";
require_once "../utils/manager.php";
require_once __DIR__."/src/controllers/AccountController.php";
require_once __DIR__."/src/controllers/LoginController.php";
require_once __DIR__."/src/controllers/SearchController.php";
require_once __DIR__."/src/handelers/ErrorHandeler.php";

set_exception_handler('ErrorHandeler::handelException');

header('Content-type: application/json; charset=UTF-8');

$uri = explode("?", $_SERVER['REQUEST_URI']);
$parts = explode('/', $uri[0]);
$request_method = $_SERVER['REQUEST_METHOD'];
$route = $parts[3];

if ( $route == 'account' ) {
    $token = $parts[4] ?? null;
    $endboint = $parts[5] ?? null;
    $params = ($_GET) ? $_GET : null;
    
    $controller = new AccountController($token);
    $controller->processRequest($request_method, $endboint, $params);
} else if ( $route == 'login' ) {
    $controller = new LoginController;
    $controller->requestUrl();
} else if ( $route == 'search' ) {
    $username = $parts[4] ?? null;

    $controller = new SearchController;
    $controller->searchRequest($username);
} else if ( $route == 'token' ) {
    if ( isset($_SESSION['data']) ) {
        echo json_encode([ 'token' => $_SESSION['data']['token'] ]);
    } else {
        echo json_encode( [ 'error' => "please connect your facebook account to get the token https://".$_SERVER['SERVER_NAME']."/igapi/main.php" ] );
    }
} else {
    http_response_code(404);
    exit;
} 