<?php

class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");
    }

    public function createUser(array $data):array
    {
        $stmt = $this->pdo->prepare(query: 'INSERT INTO users (
                   name, 
                   surname, 
                   email, 
                   password, 
                   gender, 
                   country) VALUES (
                                    :name, 
                                    :surname, 
                                    :email, 
                                    :password, 
                                    :gender, 
                                    :country
                                    )');
        $stmt->execute([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => $data['password'],
            'gender' => $data['gender'],
            'country' => $data['country']
        ]);
        return $stmt->fetch();
    }

    public function getOnlyEmail(string $email):array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users  WHERE email=:email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }



}