<?php

/**
 * Route de redirection après autorisation
 */

use App\Service\GithubOAuthService;

$config = require(__DIR__ . '/../../config/config.php');

if (!isset($_GET['code'], $_GET['state'])) {
    http_response_code(400);
    echo "Erreur : paramètres manquants.";
    exit;
}

if (!isset($_SESSION['oauth_state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
    http_response_code(403);
    echo "Erreur : vérification du state CSRF échouée.";
    exit;
}

$code = $_GET['code'];
$state = $_GET['state'];

// Créer le service OAuth
$githubOAuth = new GithubOAuthService($config['github_client_id'], $config['github_redirect_uri'], $config['github_scope']);

// Obtenir le token d'accès
$accessToken = $githubOAuth->getAccessToken($code, $state, $config['github_client_secret']);

if (!$accessToken) {
    http_response_code(401);
    echo "Erreur : impossible d'obtenir le token d'accès.";
    exit;
}

// Requête à l'API GitHub pour récupérer le profil utilisateur
$ch = curl_init('https://api.github.com/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'User-Agent: OAUTH-Exercice'
]);
$userData = curl_exec($ch);
curl_close($ch);

$userArray = json_decode($userData, true);

if (!$userArray || !isset($userArray['login'])) {
    http_response_code(401);
    echo "Impossible de récupérer le profil utilisateur";
    exit;
}

// Exemple : afficher les données du profil utilisateur GitHub
echo "<h1>Bienvenue " . htmlspecialchars($userArray['login']) . "</h1>";
echo "<pre>" . htmlspecialchars(print_r($userArray, true)) . "</pre>";

// Ici tu peux stocker le token dans le User ou démarrer le process 2FA
// $_SESSION['github_token'] = $accessToken;
// $_SESSION['github_id'] = $userArray['id'];
