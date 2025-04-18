<?php

namespace App;

/**
 * Class Autoloader pour chargement dynamique de class
 */
class Autoloader
{
    static function register()
    {

        //spl_autoload_register est une fonction qui va enregistrer une fonction(callback) 
        //A appellé lorsque il repere un appel de class
        //il prend en paramettre, une fonction annonyme/ une fonction nommé/ [la classe de, la methode]
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }

    static function autoload(string $class): void
    {
        // On retire App\
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        // On replace les \ par des /
        $class = str_replace('\\', '/', $class);

        // Récupérer le fichier
        $filePath = __DIR__ . '/' . $class . '.php';

        // On vérifie si le fichier existe
        if (file_exists($filePath)) {
            // On inclu  du fichier
            require_once $filePath;
        }
    }
}
