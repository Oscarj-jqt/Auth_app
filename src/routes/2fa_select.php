<?php

/**
 * Choix du type 2FA
 */

session_start();

// Vérifier que l'utilisateur vient de s'authentifier (GitHub ou local)
if (!isset($_SESSION['github_token'])) {
    header('Location: /login.php');
    exit;
}

// Proposer le choix du type de 2FA
echo "<h1>Choisissez votre méthode d'authentification à deux facteurs :</h1>";
echo "<form method='post' action='/2fa_send.php'>
    <label><input type='radio' name='type' value='email' required> Email</label><br>
    <label><input type='radio' name='type' value='sms'> SMS (Twilio)</label><br>
    <label><input type='radio' name='type' value='totp'> Application TOTP (Google Authenticator)</label><br>
    <button type='submit'>Continuer</button>
</form>";