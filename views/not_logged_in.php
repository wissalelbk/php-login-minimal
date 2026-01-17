<div class="login-container">
    <h1>Login</h1>

<?php
// Messages
if (isset($login)) {

    // Erreurs login, captcha, lockout
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo '<div class="error">'.$error.'</div>';

            if (preg_match('/(\d+) seconds/', $error, $matches)) {
                $seconds = (int)$matches[1];
                echo "<div id='countdown'></div>";
            }
        }
    }

    // Messages normaux
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo '<div class="message">'.$message.'</div>';
        }
    }
}

// Générer un nouveau CAPTCHA à chaque affichage du formulaire
$a = rand(1, 9);
$b = rand(1, 9);
$_SESSION['captcha_solution'] = $a + $b;
$_SESSION['captcha_question'] = "$a + $b";

?>

<script>
let countdownEl = document.getElementById('countdown');
let seconds = <?php echo $seconds ?? 0; ?>;
if(seconds > 0){
    let interval = setInterval(() => {
        countdownEl.textContent = "Time remaining: " + seconds + "s";
        seconds--;
        if(seconds < 0) clearInterval(interval);
    }, 1000);
}
</script>

<form method="post" action="index.php" name="loginform">
    <label>Username</label>
    <input type="text" name="user_name" required>

    <label>Password</label>
    <input type="password" name="user_password" required>

    <label>CAPTCHA: <?php echo $_SESSION['captcha_question'] ?? 'Solve this question'; ?></label>
    <input type="text" name="captcha" required>

    <input type="submit" name="login" value="Log in">
</form>

<a href="register.php">Register new account</a>
</div>
