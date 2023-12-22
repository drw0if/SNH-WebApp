<?php
    require_once '../../lib/utils.php';

    if(!isPost()){
        raiseMethodNotAllowed();
    }

    $data = getJsonPost();

    if(!isset($data['username']) || !isset($data['password'])){
        raiseBadRequest();
    }

    $username = $data['username'];
    $password = $data['password'];

    // check types
    if(!is_string($username) || !is_string($password)){
        raiseBadRequest();
    }

    // Check if user is already registered
    require_once '../../lib/DB.php';

    $db = DB::getInstance();
    $ans = $db->exec('SELECT * FROM `user` WHERE `username` = :username', [
        'username' => $username
    ]);

    if(count($ans) === 0){
        exitWithJson([
            'error' => "wrong username or password",
        ], UNAUTHORIZED);
    }

    $user = $ans[0];
    // check password
    if(!password_verify($password, $user['password'])){
        exitWithJson([
            'error' => "wrong username or password",
        ], UNAUTHORIZED);
    }

    // Check if user is verified
    if($user['verified'] === 0){
        exitWithJson([
            'error' => "user is not verified, please check your email first",
        ], UNAUTHORIZED);
    }

    // create session
    $token = bin2hex(random_bytes(32));
    $db->exec('INSERT INTO `session` (`user_id`, `token`) VALUES (:user_id, :token)', [
        'user_id' => $user['id'],
        'token' => $token
    ]);

    exitWithJson([
        'token' => $token
    ]);
?>