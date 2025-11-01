<?php

/**
 * Route de connexion OAuth
 */

use App\Service\GithubOAuthService;

$config = require(__DIR__ . '/../../config/config.php');


// Si l'utilisateur n'est pas connecté, démarrer OAuth GitHub
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Générer l'URL d'autorisation OAuth
    $githubOAuth = new GithubOAuthService($config['github_client_id'], $config['github_redirect_uri'], $config['github_scope']);

    // Eventuel paramètre state pour la sécurité (CSRF)
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;

    $authUrl = $githubOAuth->getAuthorizationUrl($state);

    // Rediriger vers GitHub
    header('Location: ' . $authUrl);
    exit;
}