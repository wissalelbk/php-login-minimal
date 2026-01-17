<?php
// Vérification version PHP
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, this project does not run on PHP < 5.3.7");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("libraries/password_compatibility_library.php");
}

// Configuration base de données
require_once("config/db.php");

// Logique d'inscription
require_once("classes/Registration.php");

// Création de l'objet registration (gère automatiquement le POST)
$registration = new Registration();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="css.css">
</head>
<body>

    <div class="login-container">
        <h1>Create Account</h1>

        <?php
        // Inclure le formulaire + messages
        include("views/register.php");
        ?>
    </div>

</body>
</html>