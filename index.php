<?php

session_start(); 

// Initialisation du compteur
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

// Vérification du verrouillage
if ($_SESSION['failed_attempts'] >= 3) {
    $account_locked = true;
} else {
    $account_locked = false;
}

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("libraries/password_compatibility_library.php");
}

// include DB config
require_once("config/db.php");

// load login class
require_once("classes/Login.php");
// Génération CAPTCHA simple
if (!isset($_SESSION['captcha_solution'])) {
    $a = rand(1, 9);
    $b = rand(1, 9);
    $_SESSION['captcha_solution'] = $a + $b;
    $_SESSION['captcha_question'] = "$a + $b = ?";
}

// create login object
$login = new Login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brute Force Login Demo</title>
    <link rel="stylesheet" href="css.css">
</head>
<body>

<?php
// Si le compte est bloqué
if ($account_locked) {
    echo "<div class='login-container'>
            <h1>Login</h1>
            <div class='error'>Account temporarily locked after 3 failed attempts</div>
          </div>";
}
// Sinon comportement normal
else if ($login->isUserLoggedIn() == true) {
    include("views/logged_in.php");
} else {
    include("views/not_logged_in.php");
}
?>

</body>
</html>
