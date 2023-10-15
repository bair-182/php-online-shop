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

    public function cartPageControl(): void
    {
        session_start();

        if (isset($_SESSION['user_id'])) {

            $cart = $this->cartModel->getCart();

            $cartProducts = $this->cartModel->getProduct($cart['id']);
            $cartTotal = $this->cartModel->getTotalOfCart($cart['id']);

            require_once './../View/cart.phtml';
        } else {
            header('Location: /login');
        }
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

    public function removeOneProduct(): void
    {
        session_start();
        if (isset($_SESSION['user_id']))
        {
            $cart = $this->cartModel->getCart();

            $this->cartModel->removeOneProduct($cart['id']);
        }
        header('Location: /cart');
    }
    public function addOneProduct(): void
    {
        session_start();
        if (isset($_SESSION['user_id']))
        {
            $cart = $this->cartModel->getCart();

            $this->cartModel->addOneProduct($cart['id']);
        }
        header('Location: /cart');
    }
}