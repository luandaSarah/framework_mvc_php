<?php

namespace App\Core;

/**
 * Cette classe permet d'initialiser notre application 
 * 
 */
class App
{
    public function __construct(
        private Router $router = new Router(),
    ) {}

    /**
     * Démarre l'application
     *
     * @return void
     */
    public function start(): void
    {

        //ETAPE 1: On nettoie nos URL:

        // On stocke l'URL du navigateur dans une variable
        $url = $_SERVER['REQUEST_URI'];

        // var_dump($url[-1]);
        // Si url n'est pas vide et se termine bien par /
        //$url[-1] signifie le dernier caractere de $url
        if (!empty($url) && $url !== '/' && $url[-1] === '/') {
            $url = substr($url, 0, -1); // on va supprimer le dernier / de notre url

            // On redirige vers l'url
            // redirection permanent 301, redirection temporaire 302
            http_response_code(301);

            header("Location: $url");
            exit(301);
        }

        // TODO Init du routeur
        $this->router->initRouter();

        // On appel le routeur et on lui passe l'url et la methode HTTP du navigateur
        $this->router->handleRequest($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }
}
