<?php

namespace App;

class Authentication
{
    private array $userCredentials = [
        'USER-001' => '$2a$12$cJ3LPTn8zolcOrRYNK3w/eIhVcPkaDdUxUxTI/JMJyCN3.RJibyLy', // Hashed password for '2024'
    ];

    public function authenticate(string $username, string $password): bool
    {
        if (isset($this->userCredentials[$username])) {
            if (password_verify($password, $this->userCredentials[$username])) {
                session_start();
                $_SESSION['user'] = $username;
                return true;
            }
        }
        return false;
    }

    public function checkLoginStatus(): void
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            $this->signOut();
            exit;
        }
    }

    public function signOut(): void
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        ob_start();
        header("Location: /?");
        ob_end_flush();
        exit;
    }
}

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    $auth = new Authentication();
    $auth->signOut();
}
