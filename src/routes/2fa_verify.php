<?php

/**
 * Vérifie code 2FA
 */

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';

    // Vérifier le code 2FA selon la méthode choisie
    if ($_SESSION['2fa_type'] === 'email' || $_SESSION['2fa_type'] === 'sms') {
        if ($code == ($_SESSION['2fa_code'] ?? '')) {
            $_SESSION['2fa_verified'] = true;
            echo "<p>2FA validé, accès autorisé !</p>";
            // Ici, on peut générer un JWT, etc.
        } else {
            echo "<p>Code incorrect, veuillez réessayer.</p>";
        }
    } elseif ($_SESSION['2fa_type'] === 'totp') {
        // TODO : vérifier le code TOTP via le secret stocké
        // Exemple avec une lib TOTP (voir spomky-labs/otphp)
        echo "<p>(Vérification TOTP à implémenter)</p>";
    }
}