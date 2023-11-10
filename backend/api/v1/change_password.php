<?php
    require_once '../../lib/utils.php';

    if(!isPost()){
        raiseMethodNowAllowed();
    }

    $user = getLoggedUser();
    $data = getJsonPost();

    if(!isset($data['current_password']) || !isset($data['new_password'])){
        raiseBadRequest();
    }

    $current_password = $data['current_password'];
    $new_password = $data['new_password'];

    // check types
    if(!is_string($current_password) || !is_string($new_password)){
        raiseBadRequest();
    }

    // check new password
    if(!checkPassword($new_password)){
        exitWithJson([
            'error' => "new password doesn't meet requirements",
        ], BAD_REQUEST);
    }

    // check current password
    if(!password_verify($current_password, $user['password'])){
        exitWithJson([
            'error' => "wrong password",
        ], BAD_REQUEST);
    }

    // Change password
    require_once '../../lib/DB.php';

    $db = DB::getInstance();
    $ans = $db->exec('UPDATE `user` SET `password` = :password WHERE `id` = :id', [
        'password' => password_hash($new_password, PASSWORD_DEFAULT),
        'id' => $user['id']
    ]);

    raiseOK();
?>
