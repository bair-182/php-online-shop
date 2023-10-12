<?php

$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/registration') {
    require_once "./../Controller/UserController.php";
    $obj = new UserController();
    $obj->registrate();
} elseif ($uri === '/login') {
    require_once "./../Controller/UserController.php";
    $obj = new UserController();
    $obj->login();
} elseif ($uri === '/home') {
    require_once "./../Controller/IndexController.php";
    $obj = new indexController();
    $obj->homePageControl();
}

