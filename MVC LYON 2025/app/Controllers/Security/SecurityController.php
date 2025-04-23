<?php

namespace App\Controllers\Security;

use App\Core\AbstractController;
use App\Core\Response;
use App\Core\Route;
use App\Form\LoginForm;
use App\Models\User;



/**
 * Cette Class comportera toute les methodes de Routes sécurisé login, register etc...
 */
class SecurityController extends AbstractController
{

    #[Route('app.security.login', '/login', ['GET', 'POST'])]
    public function login(): Response
    {
        //On instancie le formulaire
        $form = new LoginForm();

        //On veriife si le formulaire est soumis et est valide

        if ($form->validate(['email', 'password'], $_POST)) {
            //On recupere et netoie les donnée du formulaire         
            $email = strip_tags($_POST['email']);

            $password = $_POST['password'];

            // On vérifie si l'utilisateur existe en BDD
            $user = (new User)->findOneByEmail($email);

            // Si l'utilisateur n'existe pas ou le mot de passe est incorrect
            if (!$user || !password_verify($password, $user->getPassword())) {
                // TODO: ajouter un message d'erreur
                $this->addFlash('danger', 'Identifiants incorrects');
                // On redirige vers la page de connexion

                return $this->redirectToRoute('app.security.login');
            }

            // On connecter l'utilisateur
            $user->connectUser();

            $this->addFlash('success', 'Vous êtes bien connecté !');
            // TODO: ajouter un message de succès
            // On redirige vers la page d'accueil

            return $this->redirectToRoute('app.home');
        }

        return $this->render('security/login.php', [
            'form' => $form->createForm(),
        ]);
    }

    #[Route('app.security.logout', '/logout', ['GET'])]
    public function logout(): Response
    {
        unset($_SESSION['USER']);

        return $this->redirectToRoute('app.home');
    }
}
