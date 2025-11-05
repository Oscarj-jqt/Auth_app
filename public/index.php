<?php


require_once __DIR__ . '/../vendor/autoload.php';

// Charger .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$config = require(__DIR__ . '/../config/config.php');

/**
 * Point d'entrée de l'application OAuth (router)
 */

// Nettoie l'URI pour enlever les éventuels paramètres GET
$uri = strtok($_SERVER['REQUEST_URI'], '?');

switch ($uri) {
    case '/':
        echo "";
        require_once __DIR__ . '/../src/views/login.html';
        break;
    case '/login':
        require_once __DIR__ . '/../src/routes/login.php';
        break;
    case '/callback':
        require_once __DIR__ . '/../src/routes/callback.php';
        break;
    case '/protected':
        require_once __DIR__ . '/../src/routes/protected.php';
        break;
    case '/2fa_select':
        require_once __DIR__ . '/../src/routes/2fa_select.php';
        break;
    case '/2fa_send':
        require_once __DIR__ . '/../src/routes/2fa_send.php';
        break;
    case '/2fa_verify':
        require_once __DIR__ . '/../src/routes/2fa_verify.php';
        break;
    default:
        http_response_code(404);
        echo "Route introuvable";
        break;
}