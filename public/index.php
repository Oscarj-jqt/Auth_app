<?php

/**
 * Point d'entrée de l'application OAuth (router)
 */



// index.php
switch ($_SERVER['REQUEST_URI']) {
    case '/login':
        break;
    case '/callback':
        break;
    case '/protected':
        break;
    default:
        echo "Route introuvable";
        break;
}