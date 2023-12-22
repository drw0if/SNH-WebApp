<?php
    require_once '../../lib/utils.php';

    if(!isGet()){
        raiseMethodNotAllowed();
    }

    if(!isset($_GET['token'])){
        raiseBadRequest();
    }

    $token = $_GET['token'];

    // check types
    if(!is_string($token)){
        raiseBadRequest();
    }

    require_once '../../lib/DB.php';

    // retrieve user associated with the token
    $db = DB::getInstance();
    $ans = $db->exec('SELECT * FROM `user_verification` WHERE `token` = :token', [
        'token' => $token
    ]);

    if(count($ans) === 0){
        exitWithJson([
            'error' => 'Invalid token'
        ], BAD_REQUEST);
    }

    $user = $ans[0];
    $user_id = $user['user_id'];
    
    // set user as verified
    $db->exec('UPDATE `user` SET `verified` = 1 WHERE `id` = :user_id', [
        'user_id' => $user_id
    ]);

    // delete token from db
    $db->exec('DELETE FROM `user_verification` WHERE `user_id` = :user_id', [
        'user_id' => $user_id
    ]);

    header("Location: /");
    raiseOK();
?>
