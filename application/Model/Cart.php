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
        $stmt = $this->pdo->prepare('SELECT
                                                product_id,
                                                p.title,
                                                p.description,
                                                p.price,
                                                p.image,
                                                count(product_id),
                                                p.price*count(product_id) AS total
                                            FROM cart_product
                                                JOIN public.products p on cart_product.product_id = p.id
                                            WHERE cart_id=:cart_id
                                            group by product_id, p.title, p.description, p.price, p.image
                                            ORDER BY product_id');
        $stmt->execute(['cart_id' => $cart_id]);
        return $stmt->fetchAll();
    }

    public function getTotalOfCart(INT $cartId):array
    {
        $stmt = $this->pdo->prepare('SELECT SUM(COALESCE(p.price)) FROM cart_product
                                            JOIN public.products p on cart_product.product_id = p.id
                                            WHERE cart_id=:cart_id');
        $stmt->execute(['cart_id' => $cartId]);
        return $stmt->fetch();
    }


    public function addOneProduct(INT $cartId):void
    {
        $data = $_POST;
        $stmt = $this->pdo->prepare('INSERT INTO cart_product (product_id, cart_id) 
                                                            VALUES (:product_id, :cart_id)');
        $stmt->execute(['product_id' => $data['incProductToCart'],'cart_id' => $cartId] );
        $stmt->fetch();
    }
    public function removeOneProduct(INT $cartId): void
    {
        $data = $_POST;
        $stmt = $this->pdo->prepare('delete from cart_product where cart_id=:cart_id AND product_id=:product_id
                                                                and ctid = (select min(ctid)
                                                          from cart_product
                                                          where cart_id=:cart_id AND product_id=:product_id);');
        $stmt->execute(['product_id' => $data['decProductToCart'],'cart_id' => $cartId] );
        $stmt->fetch();
    }

}