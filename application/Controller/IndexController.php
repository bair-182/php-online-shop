<?php

namespace Controller;

use Controller\CartController;

use Model\Cart;
use Model\Product;

class IndexController
{
    private Product $productModel;

    public function __construct()
    {
        require_once "./../Model/Product.php";
        $this->productModel = new Product();
    }

    public function homePageControl(): void
    {
        session_start();

        if (isset($_SESSION['user_id'])) {

            $products = $this->productModel->getProduct();
            require_once './../View/home.phtml';
        } else {
            header('Location: /login');
        }
    }
}