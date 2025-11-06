<?php

require_once __DIR__ . '/../../vendor/autoload.php';


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';

    if ($_SESSION['2fa_type'] === 'email') {
        if ($code == ($_SESSION['2fa_code'] ?? '')) {
            $_SESSION['2fa_verified'] = true;
            echo "<p>2FA validé, accès autorisé !</p>";
            echo "<a href='/protected'>Accéder à la ressource protégée</a>";
        } else {
            echo "<p>Code incorrect, veuillez réessayer.</p>";
            echo "<a href='/2fa_select'>Renvoyer le code</a>";
        }
    } elseif ($_SESSION['2fa_type'] === 'totp') {
        $secret = $_SESSION['totp_secret'] ?? null;
        if ($secret) {
            $totp = \OTPHP\TOTP::create($secret);
            if ($totp->verify($code)) {
                $_SESSION['2fa_verified'] = true;
                echo "<p>2FA validé, accès autorisé !</p>";
                echo "<a href='/protected'>Accéder à la ressource protégée</a>";
            } else {
                echo "<p>Code TOTP incorrect, veuillez réessayer.</p>";
                echo "<a href='/2fa_select'>Renvoyer le code</a>";
            }
        } else {
            echo "<p>Erreur : secret TOTP manquant dans la session.</p>";
        }
    }
}