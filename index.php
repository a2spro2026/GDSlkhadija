<?php

/**
 * Point d'entrée production — racine du projet (même niveau que .env)
 * Utilisé lorsque la racine web du serveur pointe sur ce dossier.
 */

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(\Illuminate\Http\Request::capture());
