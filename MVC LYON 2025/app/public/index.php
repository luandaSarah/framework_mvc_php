<?php
use App\Autoloader;
use App\Core\App;


// TODO On va vouloir définir une constante qui va stocker le chemin absolu du dossier racine/parent
//en gros DIR_ROOT = racine du dossier public === /app
define('DIR_ROOT', dirname(__DIR__)); //define permet de definir une constante

require_once DIR_ROOT . '/Autoloader.php'; // correspond à app/Autoloader.php

Autoloader::register();

//TODO : On va instancier l'objet App (qui représente notre application)

$app = new App();

// TODO On va lancer l'application
$app->start();
