<?php
    require_once '../../lib/utils.php';

    if(!isPost()){
        raiseMethodNotAllowed();
    }

    $data = getJsonPost();

    if(!isset($data['email'])){
        raiseBadRequest();
    }

    $email = $data['email'];

    // check types
    if(!is_string($email)){
        raiseBadRequest();
    }

    require_once '../../lib/DB.php';

    // check if email exists
    $db = DB::getInstance();
    $ans = $db->exec('SELECT * FROM `user` WHERE `email` = :email', [
        'email' => $email
    ]);

    if(count($ans) === 0){
        // to avoid email enumeration
        raiseOK();
    }

    $user = $ans[0];

    // create token
    $token = bin2hex(random_bytes(32));

    // send code via email
    $ans = send_mail($email, 'Recover account', "The token to recover your account is: {$token}" );

    if (!$ans) {
        exitWithJson([
            'error' => "couldn't send email, please try again later",
        ], INTERNAL_SERVER_ERROR);
    }

    $ans = $db->exec('INSERT INTO `user_recover` (`user_id`, `token`) VALUES (:user_id, :token)', [
        'user_id' => $user['id'],
        'token' => $token
    ]);

    // send token via email

    raiseOK();
?>
