<?php
require_once './lib/utils.php';
$user = getLoggedUser();

if ($user != null) {
    redirect_authenticated();
}

function ask_recover_account()
{
    $data = [
        "email" => $_POST["email"],
    ];

    if (!isset($data['email'])) {
        return [
            "error" => "Invalid email",
        ];
    }

    $email = $data['email'];

    // check types
    if (!is_string($email)) {
        return [
            "error" => "Invalid email",
        ];
    }

    require_once './lib/DB.php';

    // check if email exists
    $db = DB::getInstance();
    $ans = $db->exec('SELECT * FROM `user` WHERE `email` = :email', [
        'email' => $email
    ]);

    if (count($ans) === 0) {
        return [
            "msg" => "Check your email for the password recovery link",
        ];
    }

    $user = $ans[0];

    // create token
    $token = bin2hex(random_bytes(32));
    $DEPLOYED_DOMAIN = getenv('DEPLOYED_DOMAIN');

    // send code via email
    $ans = send_mail(
        $email,
        'Recover account',
        "Click <a href=\"{$DEPLOYED_DOMAIN}/recover_password.php?token={$token}\">here</a> to reset your password!",
        'text/html'
    );

    if (!$ans) {
        return [
            "error" => "Couldn't send email, please try again later",
        ];
    }

    $ans = $db->exec('INSERT INTO `user_recover` (`user_id`, `token`) VALUES (:user_id, :token)', [
        'user_id' => $user['id'],
        'token' => $token
    ]);

    return [
        "msg" => "Check your email for the password recovery link",
    ];
}

function recover_account()
{
    $data = [
        "token" => $_POST["token"],
        "password" => $_POST["password"],
        "confirm_password" => $_POST["confirm_password"],
    ];

    if (!isset($data['token']) || !isset($data['password']) || !isset($data['confirm_password'])) {
        return [
            "error" => "Invalid token or password"
        ];
    }

    $token = $data['token'];
    $new_password = $data['password'];
    $confirm_password = $data['confirm_password'];


    // check types
    if (!is_string($token) || !is_string($new_password) || !is_string($confirm_password)) {
        return [
            "error" => "Invalid token or password"
        ];
    }

    if ($new_password !== $confirm_password) {
        return [
            "error" => "Passwords don't match"
        ];
    }

    require_once './lib/DB.php';

    // check if token actually exists
    $db = DB::getInstance();
    $ans = $db->exec('SELECT * FROM `user_recover` WHERE `token` = :token', [
        'token' => $token
    ]);

    if (count($ans) === 0) {
        return [
            "error" => "Invalid token"
        ];
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

    header("Location: /login.php");
    die();
}

if (isPost()) {
    if (isset($_POST["email"])) {
        $out = ask_recover_account();
    } else {
        $out = recover_account();
    }

    $msg = $out["msg"];
    $error_msg = $out["error"];
}

// TODO: show error message

$description = "just b00k password recover page";
$title = "Recover account";
require_once "template/header.php"; ?>

<div class="flex flex-col items-center justify-center px-6 py-8 mx-auto my-auto lg:py-0">
    <a href="#" class="flex items-center my-6 text-2xl font-semibold text-gray-900 ext-white">
        <img class="w-8 h-8 mr-2" src="static/icon.png" alt="logo" />
        Just b00k
    </a>
    <div class="w-full bg-white rounded-lg shadow order md:mt-0 sm:max-w-md xl:p-0 g-gray-800 order-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl ext-white">
                Recover account
            </h1>
            <form class="space-y-4 md:space-y-6" action="" method="POST">
                <?php if (isset($_GET["token"])) { ?>
                    <label for="text" class="block mb-2 text-sm font-medium text-gray-900 ext-white">Recover code</label>
                    <input type="text" name="token" id="token" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 g-gray-700 order-gray-600 laceholder-gray-400 ext-white ocus:ring-blue-500 ocus:border-blue-500" placeholder="1234567890" required="" value="<?php echo p($_GET["token"]); ?>" />
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ext-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 g-gray-700 order-gray-600 laceholder-gray-400 ext-white ocus:ring-blue-500 ocus:border-blue-500" required="" />
                        <p class="mt-2 text-sm text-red-600 ext-red-500 hidden" id="password_error_box">
                            Invalid password format: at least an uppercase, a
                            lowercase, a number and a symbol
                        </p>
                    </div>
                    <div>
                        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 ext-white">Confirm password</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 g-gray-700 order-gray-600 laceholder-gray-400 ext-white ocus:ring-blue-500 ocus:border-blue-500" required="" />
                        <p class="mt-2 text-sm text-red-600 ext-red-500 hidden" id="password_confirm_error_box">
                            Password mismatch
                        </p>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center g-blue-600 over:bg-blue-700 ocus:ring-blue-800">Recover</button>
                <?php } else if (isset($msg)) { ?>
                    <p class="mt-2 text-sm" id="msg">
                        <?php echo $msg; ?>
                    </p>
                <?php } else { ?>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ext-white">Your email</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 g-gray-700 order-gray-600 laceholder-gray-400 ext-white ocus:ring-blue-500 ocus:border-blue-500" placeholder="name@company.com" required="" />
                        <p class="mt-2 text-sm text-red-600 ext-red-500 hidden" id="email_error_box">
                            Invalid email format
                        </p>
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center g-blue-600 over:bg-blue-700 ocus:ring-blue-800">Recover</button>
                <?php } ?>
            </form>
        </div>
    </div>
</div>

<?php require_once "template/footer.php"; ?>