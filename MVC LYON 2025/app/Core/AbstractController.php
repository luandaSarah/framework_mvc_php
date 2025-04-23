<?php


namespace App\Core;

/**
 * Cette classe est parente à tout les controllers
 * Ca methode render renvoie un objet de type Response
 */

abstract class AbstractController
{
    protected function  render(string $view, array $data = []): Response
    {

        //Extraire les données pour les rendre accessible dans la vue 

        // extrait toute les clés de mon array et les transforme en variable
        extract($data);



        /**On veut rendre le contenu de la view parente base.php
         * Cette view affiche le contenue des views enfants
         * Pour cela on doit stocker le contenu dans la variable $content
         */

        /**Démarrer le buffer de sortie 
         * tout ce qu'il y a entre ob_start() et ob_get_clean() ne s'affiche pas mais est stocker en memoire
         */
        ob_start();

        require DIR_ROOT . '/Views/' . $view;

        //on stock le contenue cad le require dans la variable $content
        $content = ob_get_clean(); //fin du buffer de sortie

        /**Mainenant on veut stocker les views enfants dans la view parentes
         * On recommence
         */

        ob_start();

        require DIR_ROOT . '/Views/base.php';

        $finaleContent = ob_get_clean();

        //On donne en reponse le contenue finale
        return new Response(

            $finaleContent,
            200,
        );
    }


    protected function addFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function redirectToRoute(string $name): Response
    {
        $url = (new Router)->getUrl($name);
        return new Response('', 302, ['Location' => $url]);
    }
}
