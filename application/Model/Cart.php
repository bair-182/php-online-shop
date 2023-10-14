<?php

namespace Model;
use PDO;

class Cart
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");
    }


    public function getCart(): array | bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE customer_id=:customer_id");
        $stmt->execute(['customer_id' => $_SESSION['user_id']]);
        return $stmt->fetch();
    }

    public function createCart(): array | bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO cart (customer_id) VALUES (:customer_id)');
        $stmt->execute(['customer_id' => $_SESSION['user_id']]);
        return $stmt->fetch();

    }

    public function addProduct(INT $cartId):void
    {
        $data = $_POST;
        $stmt = $this->pdo->prepare('INSERT INTO cart_product (product_id, cart_id) 
                                                            VALUES (:product_id, :cart_id)');
        $stmt->execute(['product_id' => $data['addProductToCart'],'cart_id' => $cartId] );
        $stmt->fetch();
    }

    public function getProduct(INT $cart_id):array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cart_product WHERE cart_id=:cart_id");
        $stmt->execute(['cart_id' => $cart_id]);
        return $stmt->fetch();
    }


}