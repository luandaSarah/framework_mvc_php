<?php

namespace App\Core;

/**
 * Cette classe permet de la redirection des routes vers le controlleur correspondant
 * 
 */
class Router
{
    private array $routes = []; //contient l'ensemble de nos routes

    /**
     * fonction stockant dans l'array $route contenant l'ensemble des routes de nos applications
     * 
     */
    public function addRoutes(array $newRoute): self
    {
        $this->routes[] = $newRoute;

        return $this;
    }

    public function initRouter(): void
    {
        $directory = new \RecursiveDirectoryIterator(DIR_ROOT . '/Controllers');
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($files as $file) {
            $file = $file[0];
            // /app/Controllers/HomeController.php //! ON A CA
            // App\Controllers\HomeController.php //! ET ON VEUT CA

            //TODO On enlève le / du début
            $file = substr($file, 1);

            // TODO On remplace les / par des \
            $file = str_replace('/', '\\', $file);

            // TODO Supprimer l'extension php
            $file = substr($file, 0, -4);

            //TODO Mettre le A en majuscule
            $file = ucfirst($file);

            // var_dump(value: $file);

            //! FQCN : Full Qualify Class Name

            if (class_exists($file)) {
                $classes[] = $file;

                // var_dump($classes);
            }
        }

        // On boucle sur toutes les classs
        foreach ($classes as $class) {
            // On récupère dans un tableau toutes les méthodes pour la classe
            $methods = get_class_methods($class);

            foreach ($methods as $method) {
                // On récupère dans un tableau toutes les classes attributs Route dans notre méthode
                //new \ReflectionMethod($class, $method) => creer un objet qui permet d'analyser une methode spécifique d'une classe
                //->getAttributes(Route::class) renvoie les attribut Route  attaché a cette methode
                $attributes = (new \ReflectionMethod($class, $method))->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    //pour chaque attribut:
                    // On crée une instance de la classe Route avec les informations que nous avons récupérée
                    $route = $attribute->newInstance();
                    // On définit pour la route le controller
                    $route->setController($class);
                    // On définit pour la route le controller
                    $route->setAction($method);

                    // On ajoute la route à notre routeur avec un tableau associatif
                    $this->addRoutes([
                        'url' => $route->getUrl(),
                        'methods' => $route->getMethods(),
                        'controller' => $route->getController(),
                        'action' => $route->getAction(),
                        'name' => $route->getName(),
                    ]);
                }
            }
        }

        //une fois que toute les routes sont stocker dans l'array $routes (à l'initialisation)
        //On stocke $routes en Session pour qu'une fois le routeur redemarer on conserve les routes
        $_SESSION['routes'] = $this->routes;
    }


    /**
     * Cette methode permet de verifier si l'url correspond à une routes stocker dans notre $routes
     * 
     */
    public function handleRequest(string $url, string $method): void
    {
        //on verifie si l'url commence par '/admin/'

        if (preg_match("~^/admin~", $url)) {

            //on verife si l'utilisateur est connecté Et qu'il a le rôle admin

            if (empty($_SESSION['USER']) || !in_array('ROLE_ADMIN', $_SESSION['USER']['roles'])) {
                $_SESSION['flash']['danger'] = "Vous n'avez pas accés à cette page";

                //On redirigie ver la page de connexion
                $response = new Response('', 403, ['location' => '/login']);
                $response->send();

                return;
            }
        }
        // On boucle sur toutes les routes de notre application
        foreach ($this->routes as $route) {
            // On vérifie si l'URL du navigateur correspond à une url dans notre routeur et si la méthode HTTP du navigateur correspond aux méthodes autorisées de la route
            //le pattern veut dire que url doit correpondre  $route['url'] du debut à la fin
            if (preg_match('#^' . $route['url'] . '$#', $url, $matches) && in_array($method, $route['methods'])) {
                // On appel le controller 
                $controllerName = $route['controller'];
                // On instancie le controller de la route
                $controller = new $controllerName();
                // On execute la méthode de la route
                $actionName = $route['action'];

                $params = array_slice($matches, 1);    // on enleve le premier '/' car il ne nous sert pas trop ici (ici on a pas besoin de l'url)


                // On exécute la fonction avec tous les paramètres
                // d'url de manière chronnologique
                //$controller->$actionName(...$params);  // ! On veut extirper chaque élément du tableau et on va le passer comme paramètres

                $response = $controller->$actionName(...$params);
                $response->send();

                return;
            }
        }

        // Si aucune route n'a pu prendre en charge la demande, on redirige l'utilisateur vers la page 404
        http_response_code(404);
        echo '404 - Page not found';
        exit(404);
    }

    public function getUrl(string $name): ?string
    {
        foreach ($_SESSION['routes'] ?? [] as $route) {
            if ($route['name'] === $name) {
                return $route['url'];
            }
        }
        return null;
    }
}
