<?php

    /*
     * This file is used to store common methods and constant value across
     * all the application
     */

    mb_internal_encoding('UTF-8');
    mb_http_output('UTF-8');

    // if server is in DEV mode, on preflight requests return some CORS headers to
    // enable separate backend and frontend container
    if(getenv('DEV')){
        header('Access-Control-Allow-Origin: http://localhost:5173');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 86400');

        if(strcmp($_SERVER['REQUEST_METHOD'], 'OPTIONS') === 0){
            die();
        }
    }

    //This should be created in an offline folder
    define('STORAGE', __DIR__ . '/../upload/');
    define('OK', 200);
    define('BAD_REQUEST', 400);
    define('UNAUTHORIZED', 401);
    define('METHOD_NOT_ALLOWED', 405);
    define('NOT_FOUND', 404);

    //Exit if the page is requested directly instead of import
    function exitIfRequested($callingFile){
        if (strcasecmp(str_replace('\\', '/', $callingFile), $_SERVER['SCRIPT_FILENAME']) == 0) {
            http_response_code(NOT_FOUND);
            exit();
        }
    }
    exitIfRequested(__FILE__);

    function throwDatabaseError(){
        http_response_code(500);
        exit('Database Error, please contact the administrator');
    }

    function raiseOK(){
        http_response_code(OK);
        die();
    }

    function raiseUnauthorized(){
        http_response_code(UNAUTHORIZED);
        die();
    }

    function raiseMethodNotAllowed(){
        http_response_code(METHOD_NOT_ALLOWED);
        die();
    }

    function raiseBadRequest(){
        http_response_code(BAD_REQUEST);
        die();
    }

    function raiseNotFound(){
        http_response_code(NOT_FOUND);
        die();
    }

    //Check if user is logged, REMEMBER TO START THE SESSION
    function getLoggedUser(){
        // get authorization header
        if(!isset($_SERVER['HTTP_AUTHORIZATION'])){
            error_log("Missing authorization header");
            raiseUnauthorized();
        }
        $auth = $_SERVER['HTTP_AUTHORIZATION'];

        if(!is_string($auth)){
            error_log("Authorization header is not a string");
            raiseUnauthorized();
        }

        // check for token in db
        require_once 'DB.php';

        $db = DB::getInstance();
        $ans = $db->exec('SELECT * FROM `session` WHERE `token` = :token', [
            'token' => $auth
        ]);

        if(count($ans) === 0){
            error_log("No session with the token: {$auth}");
            raiseUnauthorized();
        }

        $session = $ans[0];

        // fetch user data
        $user = $db->exec('SELECT * FROM `user` WHERE `id` = :id', [
            'id' => $session['user_id']
        ]);

        if(count($user) === 0){
            error_log("Session found with no user attached: {$session['token']} - {$session['user_id']}");
            raiseUnauthorized();
        }

        return $user[0];
    }

    function checkPassword($password){
        if(strlen($password) < 8 || !preg_match("/[a-z]/", $password) ||
            !preg_match("/[A-Z]/", $password) || !preg_match("/\d/", $password) ||
            !preg_match("/\W|_/", $password)){
            return false;
        }
        return true;
    }

    function checkCreditCardNumber($number){
        return is_string($number) && preg_match("/^\d{16}$/", $number);
    }

    function checkCreditCardExpirationDate($date){
        return is_string($date) && preg_match("/^\d{2}\/\d{2}$/", $date);
    }

    function checkCreditCardCVV($cvv){
        return is_string($cvv) && preg_match("/^\d{3}$/", $cvv);
    }

    function checkEmail($email){
        return is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function checkCart($cart){
        if(!is_array($cart)){
            return false;
        }

        if(count($cart) === 0){
            return false;
        }

        if(count($cart) > 10){
            // to avoid DoS
            return false;
        }

        foreach($cart as $item){
            if(!is_array($item) || !isset($item['book_id']) || !isset($item['quantity'])){
                return false;
            }
            if(!is_numeric($item['book_id']) || !is_numeric($item['quantity']) || $item['quantity'] < 1){
                return false;
            }
        }

        return true;
    }

    function performPayment($total, $credit_card_number, $credit_card_expiration_date, $credit_card_cvv){
        return true;
    }

    function isPost(){
        return strcmp($_SERVER['REQUEST_METHOD'], 'POST') === 0;
    }

    function isGet(){
        return strcmp($_SERVER['REQUEST_METHOD'], 'GET') === 0;
    }

    //Use this function to print user provided content to avoid XSS
    function p($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    //returns a json and set a status code - default is 200
    function exitWithJson($obj, $code = 200){
        header("Content-type: application/json; charset=utf-8");
        http_response_code($code);
        echo json_encode($obj, JSON_UNESCAPED_UNICODE);
        exit();
    }

    //Check if a string is an integer number
    function isNumber($str){
        return is_string($str) && preg_match("/^-?\d{1,}$/", $str);
    }

    //Common procedure to serve file via HTTP
    function serveFile($book){
        //Open the file and suppress warnings
        $fp = @fopen(STORAGE . $book->local_name, 'r');

        //Check if fopen worked
        if($fp === FALSE){
            http_response_code(404);
            exit();
        }

        //Set content type to serve the file
        header('Content-Type: ' . $GLOBALS['contentTypes'][$book->file_type]);
        header('Content-Disposition: attachment; filename="' . $book->name . $book->file_type . '"');

        //Send the file content
        fpassthru($fp);
        fclose($fp);
    }

    // Get POST data from json and check content-type
    function getJsonPost(){
        if(strcmp($_SERVER['CONTENT_TYPE'], 'application/json') !== 0){
            exitWithJson([
                'error' => 'Invalid content-type'
            ], BAD_REQUEST);
        }

        $post = json_decode(file_get_contents('php://input'), true);

        if($post === null){
            exitWithJson([
                'error' => 'Invalid json'
            ], BAD_REQUEST);
        }

        return $post;
    }

?>