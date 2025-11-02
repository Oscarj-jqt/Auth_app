<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';

    if ($_SESSION['2fa_type'] === 'email') {
        if ($code == ($_SESSION['2fa_code'] ?? '')) {
            $_SESSION['2fa_verified'] = true;
            echo "<p>2FA validé, accès autorisé !</p>";
            echo "<a href='/protected.php'>Accéder à la ressource protégée</a>";
        } else {
            echo "<p>Code incorrect, veuillez réessayer.</p>";
            echo "<a href='/2fa_send.php'>Renvoyer le code</a>";
        }
    }
}