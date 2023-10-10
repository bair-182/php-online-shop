<?php
ob_start();

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


if ($uri === '/registration')
{
    require_once "./../handlers/registration_handler.php"; //Registration page handler
}
elseif ($uri === '/login')
{
    require_once "./../handlers/login_handler.php"; //Login page handler
}
elseif ($uri === '/home')
{
    require_once "./../handlers/homepage_handler.php"; //Home page handler
}

