<?php
    require_once '../../lib/utils.php';

    if(!isPost()){
        raiseMethodNotAllowed();
    }

    $data = getJsonPost();

    if(!isset($data['email']) || !isset($data['username']) || !isset($data['password'])){
        raiseBadRequest();
    }

    $email = $data['email'];
    $username = $data['username'];
    $password = $data['password'];

    // check types
    if(!is_string($email) || !is_string($username) || !is_string($password)){
        raiseBadRequest();
    }

    if (!checkPassword($password)) {
        exitWithJson([
            'error' => "password doesn't meet requirements",
        ], BAD_REQUEST);
    }

    if (!checkEmail($email)) {
        exitWithJson([
            'error' => "invalid email",
        ], BAD_REQUEST);
    }

    require_once '../../lib/DB.php';

    $db = DB::getInstance();

    // Check if user is already registered
    $ans = $db->exec('SELECT * FROM `user` WHERE `email` = :email OR `username` = :username', [
        'email' => $email,
        'username' => $username
    ]);

    if(count($ans) !== 0){
        // return 200 to avoid email/username enumeration
        raiseOK();
    }

    // Before adding stuff to the DB let's try to send the verification email
    $token = bin2hex(random_bytes(32));
    $DEPLOYED_DOMAIN = getenv('DEPLOYED_DOMAIN');

    $ans = send_mail($email,
        'Verify your account',
        "Click <a href=\"{$DEPLOYED_DOMAIN}/api/v1/verify.php?token={$token}\">here</a> in order to verify your account!",
        'text/html'
    );
    
    if (!$ans) {
        exitWithJson([
            'error' => "couldn't send verification email",
        ], INTERNAL_SERVER_ERROR);
    }

    // add user to db
    $db->exec('INSERT INTO `user` (`email`, `username`, `password`) VALUES (:email, :username, :password)', [
        'email' => $email,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    $user_id = $db->lastInsertId();

    // add verification token to db
    $db->exec('INSERT INTO `user_verification` (`user_id`, `token`) VALUES (:user_id, :token)', [
        'user_id' => $user_id,
        'token' => $token
    ]);

    raiseOK();
?>