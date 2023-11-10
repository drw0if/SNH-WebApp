<?php
    require_once '../../lib/utils.php';

    if(!isPost()){
        raiseMethodNowAllowed();
    }

    $data = getJsonPost();

    if(!isset($data['token']) || !isset($data['new_password'])){
        raiseBadRequest();
    }

    $token = $data['token'];
    $new_password = $data['new_password'];

    // check types
    if(!is_string($token) || !is_string($new_password)){
        raiseBadRequest();
    }

    require_once '../../lib/DB.php';

    // check if token actually exists
    $db = DB::getInstance();
    $ans = $db->exec('SELECT * FROM `user_recover` WHERE `token` = :token', [
        'token' => $token
    ]);

    if(count($ans) === 0){
        exitWithJson([
            'error' => 'Invalid token'
        ], BAD_REQUEST);
    }

    $user_recover = $ans[0];

    // delete token from requests
    $db->exec('DELETE FROM `user_recover` WHERE `id` = :id', [
        'id' => $user_recover['id']
    ]);

    // update password
    $db->exec('UPDATE `user` SET `password` = :password WHERE `id` = :id', [
        'password' => password_hash($new_password, PASSWORD_DEFAULT),
        'id' => $user_recover['user_id']
    ]);

    raiseOK();
?>
