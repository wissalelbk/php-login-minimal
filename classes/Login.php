<?php

class Login
{
    private $db_connection = null;
    public $errors = array();
    public $messages = array();

    private $max_attempts = 3;  
    private $lock_time = 120;   

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Initialisation compteur brute force
        $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
        $_SESSION['lock_until']     = $_SESSION['lock_until'] ?? 0;

        if (isset($_GET["logout"])) {
            $this->doLogout();
        } elseif (isset($_POST["login"])) {
            $this->doLoginWithPostData();
        }
    }

    private function doLoginWithPostData()
    {
        // Vérifier lockout
        if (time() < $_SESSION['lock_until']) {
            $remaining = $_SESSION['lock_until'] - time();
            $this->errors[] =
                "Too many failed login attempts. Your account is temporarily locked. " .
                "Please wait $remaining seconds before trying again.";
            return;
        }

        // Vérifier si username/password remplis
        if (empty($_POST['user_name']) || empty($_POST['user_password'])) {
            $this->errors[] = "Username or password is empty.";
            return;
        }

        // Vérification CAPTCHA (obligatoire)
        $captcha_input = $_POST['captcha'] ?? '';
        if ($captcha_input == '') {
            $this->errors[] = "CAPTCHA_REQUIRED: Please solve the CAPTCHA.";
            return;
        }

        if ((int)$captcha_input !== ($_SESSION['captcha_solution'] ?? -1)) {
            $this->errors[] = "WRONG_CAPTCHA: CAPTCHA verification failed.";
            // Ne pas supprimer le captcha pour que l'utilisateur puisse réessayer
            return;
        }

        // Connexion DB
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db_connection->connect_errno) {
            $this->errors[] = "Database connection problem.";
            return;
        }
        $this->db_connection->set_charset("utf8");

        $user_name = $this->db_connection->real_escape_string($_POST['user_name']);
        $sql = "SELECT user_name, user_email, user_password_hash
                FROM users
                WHERE user_name = '$user_name'
                OR user_email = '$user_name'
                LIMIT 1";
        $result = $this->db_connection->query($sql);

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_object();

            if (password_verify($_POST['user_password'], $user->user_password_hash)) {
                // Login réussi
                $_SESSION['user_name'] = $user->user_name;
                $_SESSION['user_email'] = $user->user_email;
                $_SESSION['user_login_status'] = 1;

                // Reset brute-force et captcha
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lock_until'] = 0;
                unset($_SESSION['captcha_solution'], $_SESSION['captcha_question']);

                $this->messages[] = "Login successful.";

            } else {
                $this->registerFailedAttempt();
                $this->errors[] = "Incorrect password.";
            }

        } else {
            $this->registerFailedAttempt();
            $this->errors[] = "User not found.";
        }
    }

    private function registerFailedAttempt()
    {
        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= $this->max_attempts) {
            $_SESSION['lock_until'] = time() + $this->lock_time;
            $_SESSION['login_attempts'] = 0;
            $this->errors[] =
                "Too many failed login attempts. Your account is now locked for " .
                $this->lock_time . " seconds.";
        }
    }

    public function doLogout()
    {
        $_SESSION = array();
        session_destroy();
        $this->messages[] = "You have been logged out.";
    }

    public function isUserLoggedIn()
    {
        return isset($_SESSION['user_login_status']) && $_SESSION['user_login_status'] == 1;
    }
}
