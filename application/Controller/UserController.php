<?php

class UserController
{
    public function registrate(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {

            $data = $_POST; // работаем с $data
            $errors = $this->validate($data);

            if (empty($errors)) {
                $name = $data['name'];
                $surname = $data['surname'];
                $email = $data['email'];
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $gender = $data['gender'];
                $countryOption = $data['country'];

                $pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");
                $stmt = $pdo->prepare("INSERT INTO users (name, surname, email, password, gender, country) VALUES (:name, :surname, :email, :password, :gender, :country)");
                $stmt->execute(['name' => $name, 'surname' => $surname, 'email' => $email, 'password' => $hashedPassword, 'gender' => $gender, 'country' => $countryOption]);

                header('Location: /login');
            }
        }
        require_once './../View/registration.phtml';
    }

    public function login(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {

            $data = $_POST; // работаем с $data
            $errors = $this->validate($data);
            $method = $_SERVER['REQUEST_METHOD'];

            if ($method === 'POST') {
                $email = $data['email'];
                $password = $data['password'];


                $pdo = new PDO("pgsql:host=postgres;dbname=postgres", "postgres", "postgres");
                $stmt = $pdo->prepare("SELECT * FROM users  WHERE email=:email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();


                if (isset($user['email'])) {
                    if ($user && password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = 1;
                        header('Location:/home');
                    } else {
                        $errors['password'] = 'Пароль неправильный';
                    }
                } else {
                    $errors['email'] = 'Электронная почта не зарегистрирована.';
                }
            }
            require_once './../View/login.phtml';
        } else {
            header('Location: /home');
        }
    }

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

    private function validate (array $data):array
    {
        $errors = [];
        $uri = $_SERVER['REQUEST_URI'];

        if ($uri === '/registration')
        {
            if (isset($data["foo"])) {

                $name = $this->filterInput($data["name"]) ?? null;
                $surname = $this->filterInput($data["surname"]) ?? null;
                $email = $this->filterInput($data["email"]) ?? null;
                $gender = $this->filterInput($data["gender"]);
                $password = $this->filterInput($data["password"]);

                if (empty($name)) {
                    $errors['name'] = 'Имя нельзя оставлять пустым.';
                } // Allow letters and white space
                else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                    $errors['name'] = 'Допускаются только буквы и пробелы.';
                } else {
                    $data['name'] = $name;
                }

                if (empty($surname)) {
                    $errors['surname'] = "Фамилию нельзя оставлять пустым.";
                } // Allow letters and white space
                else if (!preg_match("/^[a-zA-Z ]*$/", $surname)) {
                    $errors['surname'] ="Допускаются только буквы и пробелы.";
                } else {
                    $data['surname'] = $surname;
                }

                // Email validation
                if (empty($email)) {
                    $errors['email'] = "Адрес электронной почты нельзя оставлять пустым.";
                } // E-mail format validation
                else if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
                    $errors['email'] = "Формат электронной почты недопустим.";
                } else {
                    $data['email'] = $email;
                }

                // PasswordValidation
                if(!empty($data["password"]) && ($data["password"] == $data["cpassword"])) {
                    if (strlen($data["password"]) <= '8') {
                        $errors['password'] = "Ваш пароль должен содержать не менее 8 символов!";
                    }
                    elseif(!preg_match("#[0-9]+#",$password)) {
                        $errors['password'] = "Ваш Пароль Должен Содержать Как Минимум 1 Цифру!";
                    }
                    elseif(!preg_match("#[A-Z]+#",$password)) {
                        $errors['password'] = "Ваш Пароль Должен Содержать Как Минимум 1 Заглавную Букву!";
                    }
                    elseif(!preg_match("#[a-z]+#",$password)) {
                        $errors['password'] = "Ваш Пароль Должен Содержать Как Минимум 1 Строчную Букву!";
                    }
                } elseif(!empty($data["password"])) {
                    $errors['cpassword'] = "Пароли должны совпадать!";
                } else {
                    $errors['password'] = "Пожалуйста, введите пароль";
                }

                // Radio button validation
                if (empty($gender)) {
                    $errors['gender'] = "Укажите свой пол.";
                } else {
                    $data['gender'] = $gender;
                }
            }
        } elseif ($uri === '/login') {
            if (isset($data["foo"])) {

                $email = $this->filterInput($data["email"]) ?? null;
                $password = $this->filterInput($data["password"]);

                // Email validation
                if (empty($email)) {
                    $errors['email'] = "Адрес электронной почты нельзя оставлять пустым.";
                } // E-mail format validation
                else if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
                    $errors['email'] = "Формат электронной почты недопустим.";
                } else {
                    $data['email'] = $email;
                }
                // PasswordValidation
                if(empty($password)) {
                    $errors['password'] = 'Пожалуйста, введите пароль';
                }
            }
        }
        return $errors;
    }
    private function filterInput(string $input): string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}