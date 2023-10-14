<?php

namespace Model;

use PDO;

class Product
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");
    }

    public function getProduct ():array
    {
        $res = $this->pdo->prepare("SELECT * FROM products");
        $res->execute();
        return $res->fetchAll();
    }
}