<?php
    require_once '../../lib/utils.php';

    if(!isPost()){
        raiseMethodNowAllowed();
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

    // add user to db
    $db->exec('INSERT INTO `user` (`email`, `username`, `password`) VALUES (:email, :username, :password)', [
        'email' => $email,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    $user_id = $db->lastInsertId();

    // add verification token to db
    $token = bin2hex(random_bytes(32));
    $db->exec('INSERT INTO `user_verification` (`user_id`, `token`) VALUES (:user_id, :token)', [
        'user_id' => $user_id,
        'token' => $token
    ]);

    // should send an email with the token, but for PoC
    // purpose, let's just automatically verify the user

    // set user as verified
    $db->exec('UPDATE `user` SET `verified` = 1 WHERE `id` = :user_id', [
        'user_id' => $user_id
    ]);

    // delete token from db
    $db->exec('DELETE FROM `user_verification` WHERE `user_id` = :user_id', [
        'user_id' => $user_id
    ]);

    raiseOK();
?>