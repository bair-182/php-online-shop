<?php
// Error messages
$nameEmptyErr = "";
$surnameEmptyErr = "";
$emailEmptyErr = "";
$genderEmptyErr = "";
$nameErr = "";
$surnameErr = "";
$emailErr = "";

$cpasswordErr = "";
$passwordErr = "";

if (isset($_POST["foo"])) {
    // Set form variables

        $name = checkInput($_POST["name"]) ?? null;
        $surname = checkInput($_POST["surname"]) ?? null;
        $email = checkInput($_POST["email"]) ?? null;
        $gender = checkInput($_POST["gender"]);
        $password = checkInput($_POST["password"]);

    // Name validation
    if (empty($name)) {
        $nameEmptyErr = '<div class="error">
                Имя нельзя оставлять пустым.
            </div>';
    } // Allow letters and white space
    else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $nameErr = '<div class="error">
                Допускаются только буквы и пробелы.
            </div>';
    } else {
        $_POST['name'] = $name;
    }

    // Surname validation
    if (empty($surname)) {
        $surnameEmptyErr = '<div class="error">
                Фамилию нельзя оставлять пустым.
            </div>';
    } // Allow letters and white space
    else if (!preg_match("/^[a-zA-Z ]*$/", $surname)) {
        $surnameErr = '<div class="error">
                Допускаются только буквы и пробелы.
            </div>';
    } else {
        $_POST['surname'] = $surname;
    }

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
    if(!empty($_POST["password"]) && ($_POST["password"] == $_POST["cpassword"])) {
        if (strlen($_POST["password"]) <= '8') {
            $passwordErr = '<div class="error">
                Ваш пароль должен содержать не менее 8 символов!
            </div>';
        }
        elseif(!preg_match("#[0-9]+#",$password)) {
            $passwordErr = '<div class="error">Ваш Пароль Должен Содержать Как Минимум 1 Цифру!</div>';
        }
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $passwordErr = '<div class="error">Ваш Пароль Должен Содержать Как Минимум 1 Заглавную Букву!</div>';

        }
        elseif(!preg_match("#[a-z]+#",$password)) {
            $passwordErr = '<div class="error">Ваш Пароль Должен Содержать Как Минимум 1 Строчную Букву!</div>';
        }
    }
    elseif(!empty($_POST["password"])) {
        $cpasswordErr = '<div class="error">Пароли должны совпадать!</div>';
    } else {
        $passwordErr = '<div class="error">Пожалуйста, введите пароль</div>';
    }

    // Radio button validation
    if (empty($gender)) {
        $genderEmptyErr = '<div class="error">
                Укажите свой пол.
            </div>';
    } else {
        $_POST['gender'] = $gender;
    }
}
function checkInput($input): string
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}