<?php

/**
 * Route de connexion OAuth
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Service\GithubOAuthService;

$config = require(__DIR__ . '/../../config/config.php');

session_start();

// Si l'utilisateur n'est pas connecté, démarrer OAuth GitHub
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Générer l'URL d'autorisation OAuth
    $githubOAuth = new GithubOAuthService($config['github_client_id'], $config['github_redirect_uri'], $config['github_scope']);

    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth_state'] = $state;

    $authUrl = $githubOAuth->getAuthorizationUrl($state);

    header('Location: ' . $authUrl);
    exit;
}