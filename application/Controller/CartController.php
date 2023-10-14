<?php

namespace Controller;

use Model\Cart;

class CartController
{
    private Cart $cartModel;

    public function __construct()
    {
        require_once "./../Model/Cart.php";
        $this->cartModel = new Cart();
    }

    public function addProduct(): void
    {
        session_start();
        if (isset($_SESSION['user_id']))
        {
            $cart = $this->cartModel->getCart();
                if ($cart == null) {
                    $cart = $this->cartModel->createCart();
                }
                $this->cartModel->addProduct($cart['id']);
        }
        header('Location: /home');
    }
}