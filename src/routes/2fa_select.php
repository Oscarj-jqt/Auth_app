<?php

require_once __DIR__ . '/../../vendor/autoload.php';


session_start();

// Vérifier que l'utilisateur vient de s'authentifier
if (!isset($_SESSION['github_token'])) {
    header('Location: /login');
    exit;
}

echo "<h1>Authentification à deux facteurs par email</h1>";
echo "<form method='post' action='/2fa_send'>
    <input type='hidden' name='type' value='email'>
    <button type='submit'>Recevoir le code par email</button>
</form>";

echo "<h1>Authentification à deux facteurs avec Google Authenticator (TOTP)</h1>";
echo "<form method='post' action='/2fa_send'>
    <input type='hidden' name='type' value='totp'>
    <button type='submit'>Recevoir le code par TOTP</button>
</form>";