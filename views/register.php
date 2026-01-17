<?php
// Messages d’erreur / succès
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo '<div class="error">'.$error.'</div>';
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo '<div class="message">'.$message.'</div>';
        }
    }
}
?>

<form method="post" action="register.php" class="register-form">
    <label for="user_name">Username</label>
    <input id="user_name" type="text" name="user_name" required placeholder="Enter username">

    <label for="user_email">Email</label>
    <input id="user_email" type="email" name="user_email" required placeholder="Enter your email">

    <label for="user_password_new">Password</label>
    <input id="user_password_new" type="password" name="user_password_new" required placeholder="Enter password">

    <label for="user_password_repeat">Repeat password</label>
    <input id="user_password_repeat" type="password" name="user_password_repeat" required placeholder="Repeat password">

    <input type="submit" name="register" value="Register">
</form>

<a href="index.php">Back to Login</a>
