<?php

class AuthController
{

    public function register()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Alap ellenőrzések:
            if (empty($username)) {
                $errors[] = "A felhasználónév megadása kötelező.";
            }
            if (empty($email)) {
                $errors[] = "Az email megadása kötelező.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Érvénytelen email formátum.";
            }
            if (empty($password)) {
                $errors[] = "A jelszó megadása kötelező.";
            } elseif (strlen($password) < 6) {
                $errors[] = "A jelszónak legalább 6 karakter hosszúnak kell lennie.";
            }

            $userModel = new User();

            // Egyediség ellenőrzések:
            if ($userModel->emailExists($email)) {
                $errors[] = "Ezzel az email címmel már regisztráltak.";
            }
            if ($userModel->usernameExists($username)) {
                $errors[] = "Ez a felhasználónév már foglalt.";
            }

            if (empty($errors)) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $user_id = $userModel->create($username, $email, $hash);
                if ($user_id) {
                    redirect('?page=login');
                } else {
                    $errors[] = "Hiba történt a regisztráció során. Próbáld újra később.";
                }
            }
        }

        view('register', ['errors' => $errors]);
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($email && $password) {
                $userModel = new User();
                $user = $userModel->findByEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    redirect('?page=cryptocurrencies');
                } else {
                    echo "Hibás bejelentkezés!";
                }
            }
        }
        view('login');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        redirect('?page=login');
    }
}
