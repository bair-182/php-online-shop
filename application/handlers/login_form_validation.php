<?php
// Error messages

$emailErr = "";
$passwordErr = "";

if (isset($_POST["foo"])) {
    // Set form variables

        $email = checkInput($_POST["email"]) ?? null;
        $password = checkInput($_POST["password"]);

    // Email validation
    if (empty($email)) {
        $emailEmptyErr = '<div class="error">
                Адрес электронной почты нельзя оставлять пустым.
            </div>';
    } // E-mail format validation
    else if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
        $emailErr = '<div class="error">
                    Формат электронной почты недопустим.
            </div>';
    } else {
        $_POST['email'] = $email;
    }

    // PasswordValidation
    if(empty($_POST["password"])) {
        $passwordErr = '<div class="error">Пожалуйста, введите пароль</div>';
    }
}
function checkInput($input): string
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}