<?php

/**
 * Envoi code 2FA
 */

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'email';
    $_SESSION['2fa_type'] = $type;

    // Ici, selon le type, tu envoies un code ou génères un QR code.
    if ($type === 'email') {
        // Générer un code, l'envoyer à l'email utilisateur (ex via PHPMailer)
        $code = rand(100000, 999999);
        $_SESSION['2fa_code'] = $code;
        // TODO : envoyer le code par email
        echo "<p>Un code a été envoyé à votre adresse email. Veuillez le saisir ci-dessous.</p>";
    } elseif ($type === 'sms') {
        // Générer et envoyer via Twilio
        $code = rand(100000, 999999);
        $_SESSION['2fa_code'] = $code;
        // TODO : envoyer le code par SMS
        echo "<p>Un code a été envoyé par SMS. Veuillez le saisir ci-dessous.</p>";
    } elseif ($type === 'totp') {
        // Générer le secret TOTP pour le QR code
        // TODO : générer le QR code et afficher
        echo "<p>Scannez ce QR code avec Google Authenticator.</p>";
        // Afficher le formulaire de saisie du code TOTP
    }
}

echo "<form method='post' action='/2fa_verify.php'>
    <label for='code'>Code 2FA :</label>
    <input type='text' name='code' required>
    <button type='submit'>Vérifier</button>
</form>";