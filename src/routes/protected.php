<?php

require_once __DIR__ . '/../../vendor/autoload.php';


/**
 * Route privée affichant données du user
 */

use App\Service\JWTService;

session_start();

$config = require(__DIR__ . '/../../config/config.php');


$jwt = $_SESSION['jwt'] ?? null;

if (!$jwt) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? ($_SERVER['Authorization'] ?? null);
    if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $m)) {
        $jwt = $m[1];
    }
}

if ($jwt) {
    $jwtService = new JWTService($config['jwt_secret'], $config['jwt_issuer'], $config['jwt_ttl']);
    $payload = $jwtService->decode($jwt);
    if ($payload && isset($payload['user_id'])) {
        echo "<h1>Ressource protégée</h1>";
        echo "<p>Bienvenue " . htmlspecialchars($payload['username']) . " !</p>";
        echo "<ul>";
        if (isset($payload['email'])) {
            echo "<li>Email : " . htmlspecialchars($payload['email']) . "</li>";
        }
        if (isset($payload['github_id'])) {
            echo "<li>GitHub ID : " . htmlspecialchars($payload['github_id']) . "</li>";
        }
        // Affiche le type de 2FA
        $userRepo = new \App\Repository\UserRepository($config['users_file']);
        $user = $userRepo->findById($payload['user_id']);
        if ($user && isset($user['twofa_type'])) {
            echo "<li>Authentification 2FA : " . htmlspecialchars($user['twofa_type']) . "</li>";
            if ($user['twofa_type'] === 'totp') {
                echo "<li>Connexion validée par QR code (Google Authenticator)</li>";
            }
        }
        echo "</ul>";
    } else {
        http_response_code(401);
        echo "JWT invalide ou expiré.";
    }
} else {
    http_response_code(401);
    echo "Accès refusé : authentification requise.";
}