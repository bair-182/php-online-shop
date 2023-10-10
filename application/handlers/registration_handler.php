<?php
require_once './../html/registration.phtml';
if ($nameEmptyErr === ""
    && $surnameEmptyErr === ""
    && $emailEmptyErr === ""
    && $genderEmptyErr === ""
    && $nameErr === ""
    && $emailErr === ""
    && $surnameErr ===""
    && $cpasswordErr ===""
    && $passwordErr ===""
) {
    if ($method === 'POST') {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $gender = $_POST['gender'];
        $countryOption = $_POST['country'];

        $pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");

        $stmt = $pdo->prepare("INSERT INTO users (name, surname, email, password, gender, country) VALUES (:name, :surname, :email, :password, :gender, :country)");
        $stmt->execute(['name' => $name, 'surname' => $surname, 'email' => $email, 'password' => $hashedPassword, 'gender' => $gender, 'country' => $countryOption]);

        $_POST = array();

        header('Location: /login');
        exit();
    }
}