<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    require_once './../handlers/login_form_validation.php';
    if ($method === 'POST') {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");

        $stmt = $pdo->prepare("SELECT * FROM users  WHERE email=:email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (isset($user['email'])) {
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = 1;
                echo 'pass is VALID!!!';
                header('Location: /home');
                exit();
            } else {
                $passwordErr = '<div class="error">Пароль неправильный</div>';
            }
        } else {
            $emailErr = '<div class="error">
                    Электронная почта не зарегистрирована.
            </div>';
        }
    }
    require_once './../html/login.phtml';
} else {
    header('Location: /home');
    exit();
}
