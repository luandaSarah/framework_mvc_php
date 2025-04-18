<?php

namespace App\Controllers\Security;

use App\Core\AbstractController;
use App\Core\Response;
use App\Core\Route;
use App\Form\LoginForm;
use App\Form\RegisterForm;
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

    #[Route('app.security.register', '/register', ['GET', 'POST'])]
    public function register(): Response
    {

        //on instancie le formulaire d'inscription
        $form = new RegisterForm();



        //si les données necessaire sont soumis
        if ($form->validate(['email', 'password', 'lastName', 'firstName'], $_POST)) {


            //recuperer les donner et les nettoyer
            $firstName = strip_tags($_POST['firstName']);
            $lastName = strip_tags($_POST['lastName']);
            $email = strip_tags($_POST['email']);
            $password = $_POST['password'];

            //verifier si le mail est déja utilisé en bdd
            $userExist = (new User)->findOneByEmail($email);

            //si oui 
            if ($userExist) {
                //afficher un message d'erreur
                $this->addFlash('danger', 'Cette addresse E-mail est indisponible');
                //on redirige vers la page d'inscription 
                return $this->redirectToRoute('app.security.register');
            }

            //on stock le nouvelle utilisateur dans un tableau
            $password = password_hash($password, PASSWORD_ARGON2I);
            // $newUser = [$firstName, $lastName, $email, $password];

            // var_dump($newUser);
            $newUser = new User(null, $firstName, $lastName, $email, $password, null, null);
            $newUser->fetchHydrate($newUser)
                ->create();

            // var_dump($newUser);
            $this->addFlash('success', 'Vous êtes bien inscrit !');

            // On redirige vers la page de connexion

            return $this->redirectToRoute('app.security.login');
        }

        return $this->render('security/register.php', [
            'form' => $form->createForm(),
        ]);
    }
}
