<?php
session_start();
if (isset($_SESSION['user_id'])) {

    // Connect to the database
    $pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");
    // Get the bytea data
    $res = $pdo->prepare("SELECT * FROM products");
    $res->execute();
    $pokemon = $res->fetchAll();

    require_once './../html/home.phtml';

} else {
    header('Location: /login');
    exit();
}
