<?php

class indexController
{
    public function homePageControl(): void
    {
        session_start();

        if (isset($_SESSION['user_id'])) {
            $pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");

            $res = $pdo->prepare("SELECT * FROM products");
            $res->execute();
            $pokemon = $res->fetchAll();

            require_once './../View/home.phtml';
        } else {
            header('Location: /login');
        }
    }
}