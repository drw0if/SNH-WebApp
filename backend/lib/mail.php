<?php

$to = "aleandro.prudenzano@gmail.com";
$username = getenv('GMAIL_EMAIL');
$pssword = getenv('GMAIL_PASSWORD');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/OAuth.php';
require 'PHPMailer-master/src/Exception.php';
require 'oauth2-google/src/Provider/Google.php';


function sendMail($recipient, $subject, $content) {
    $mail = new PHPMailer(true);

    try {
        $email = getenv('GMAIL_EMAIL');
        $clientId = getenv('GMAIL_CLIENT_ID');
        $clientSecret = getenv('GMAIL_CLIENT_SECRET');
        $refreshToken = 'XXX';

        $provider = new Google(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
            ]
        );

        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';
        $mail->setOAuth(
            new OAuth(
                [
                    'provider' => $provider,
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'refreshToken' => $refreshToken,
                    'userName' => $email,
                ]
            )
        );

        $mail->setFrom($email);
        $mail->addAddress($recipient);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $content;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->send();

        return TRUE;
    }
    catch(Exception $e) {
        return FALSE;
    }
}


sendMail("aleandro.prudenzano@gmail.com", "OGGETTO TI PREGO", "BODY TI PREGO DIOMERDA DI DIOCANE MADONNA PUTTANA")
?>