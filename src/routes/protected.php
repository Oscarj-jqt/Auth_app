<?php

/**
 * Route privée affichant données du user
 */

session_start();

if (!isset($_SESSION['github_token'])) {
    http_response_code(401);
    echo "Accès refusé : veuillez vous authentifier via GitHub.";
    exit;
}

// Ici, tu pourrais aussi vérifier un JWT si tu passes au système 2FA/JWT.

// Afficher une ressource protégée
echo "<h1>Ressource protégée</h1>";
echo "<p>Vous êtes bien authentifié via GitHub !</p>";

// Exemple : afficher le login GitHub stocké en session
if (isset($_SESSION['github_login'])) {
    echo "<p>Connecté en tant que <strong>" . htmlspecialchars($_SESSION['github_login']) . "</strong></p>";
}