<?php

use Controller\IndexController;
use Controller\UserController;
use Controller\CartController;

spl_autoload_register(function (string $className) {
    $path = str_replace("\\","/", $className);

    if (file_exists("./../$path.php")) {
        require_once "./../$path.php";
    }
    return false;
});


$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/registration') {
    $obj = new UserController();
    $obj->registrate();
} elseif ($uri === '/login') {
    $obj = new UserController();
    $obj->login();
} elseif ($uri === '/home') {
    $obj = new IndexController();
    $obj->homePageControl();
} elseif ($uri === '/add-product') {
    $obj = new CartController();
    $obj->addProduct();
} elseif ($uri === '/remove-product') {
    $obj = new CartController();
    $obj->removeOneProduct();
} elseif ($uri === '/add-one-product') {
    $obj = new CartController();
    $obj->addOneProduct();
}elseif ($uri === '/cart') {
    $obj = new CartController();
    $obj->cartPageControl();
}

else header('Location: /home');

