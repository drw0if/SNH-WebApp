<?php
require_once './lib/utils.php';
$user = getLoggedUser();

if ($user == null) {
    raiseNotFound();
}

function profilePost()
{
    $data = [
        "current_password" => $_POST['current_password'],
        "new_password" => $_POST['new_password'],
        "confirm_password" => $_POST['confirm_password'],
    ];

    if (!isset($data['current_password']) || !isset($data['new_password']) || !isset($data['confirm_password'])) {
        raiseBadRequest();
    }

    $current_password = $data['current_password'];
    $new_password = $data['new_password'];
    $confirm_password = $data['confirm_password'];

    // check types
    if (!is_string($current_password) || !is_string($new_password) || !is_string($confirm_password)) {
        raiseBadRequest();
    }

    if ($new_password !== $confirm_password) {
        return [
            "error" => "The two passwords does not match",
        ];
    }

    // check new password
    if (!checkPassword($new_password)) {
        return [
            "error" => "Invalid password format: at least an uppercase, a lowercase, a number and a symbol",
        ];
    }

    // check current password
    if (!password_verify($current_password, $user['password'])) {
        return [
            "error" => "Wrong password",
        ];
    }

    // Change password
    require_once './lib/DB.php';

    $db = DB::getInstance();
    $ans = $db->exec('UPDATE `user` SET `password` = :password WHERE `id` = :id', [
        'password' => password_hash($new_password, PASSWORD_DEFAULT),
        'id' => $user['id']
    ]);

    return [
        "msg" => "Password changed successfully",
    ];
}

if (isPost()) {
    $ans = profilePost();
    $msg = $ans["msg"];
    $error_msg = $ans["error"];
}

$description = "just b00k profile page";
$title = "Change password";
require_once "template/header.php"; ?>

<div class="flex flex-col items-center justify-center px-6 py-8 mx-auto my-auto lg:py-0">
    <a href="#" class="flex items-center my-6 text-2xl font-semibold text-gray-900 ext-white">
        <img class="w-8 h-8 mr-2" src="/static/icon.png" alt="logo" />
        Just b00k
    </a>
    <div class="w-full bg-white rounded-lg shadow order md:mt-0 sm:max-w-md xl:p-0 g-gray-800 order-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl ext-white">
                Change password
            </h1>
            <form class="space-y-4 md:space-y-6" action="" method="POST">
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ext-white">Old password</label>
                    <input type="password" name="current_password" id="current_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 g-gray-700 order-gray-600 laceholder-gray-400 ext-white ocus:ring-blue-500 ocus:border-blue-500" required="" />
                    <p class="mt-2 text-sm text-red-600 ext-red-500 hidden" id="current_password_error_box">
                        Invalid password format: at least an uppercase, a
                        lowercase, a number and a symbol
                    </p>
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ext-white">New password</label>
                    <input type="password" name="new_password" id="new_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 g-gray-700 order-gray-600 laceholder-gray-400 ext-white ocus:ring-blue-500 ocus:border-blue-500" required="" />
                    <p class="mt-2 text-sm text-red-600 ext-red-500 hidden" id="new_password_error_box">
                        Invalid password format: at least an uppercase, a
                        lowercase, a number and a symbol
                    </p>
                </div>
                <div>
                    <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 ext-white">Confirm password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 g-gray-700 order-gray-600 laceholder-gray-400 ext-white ocus:ring-blue-500 ocus:border-blue-500" required="" />
                    <p class="mt-2 text-sm text-red-600 ext-red-500 hidden" id="confirm_password_error_box">
                        Password mismatch
                    </p>
                </div>
                <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center g-blue-600 over:bg-blue-700 ocus:ring-blue-800">Change</button>

                <?php if (isset($error_msg)) { ?>
                    <p class="mt-2 text-sm text-red-600 ext-red-500 hidden" id="current_password_error_box">
                        <?php echo $error_msg; ?>
                    </p>
                <?php } ?>
            </form>
        </div>
    </div>
</div>


<?php require_once "template/footer.php"; ?>