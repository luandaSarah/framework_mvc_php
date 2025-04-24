<?php

namespace App\Controllers\Backend; // ce dossier permet de gerer les controllers qui s'occupent du back de notre app

use App\Core\Route;
use App\Models\Poste;
use App\Core\Response;
use App\Form\PosteForm;
use App\Core\AbstractController;


class PosteController extends AbstractController
{
    #[Route('admin.poste.index', '/admin/postes', ['GET'])]

    public function index(): Response
    {
        $postes = (new Poste)->findAll();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(56));

    
        return $this->render('backend/poste/index.php', [
            'postes' => $postes,
            'token' => $_SESSION['csrf_token'],

            //pour personnaliser les infos du header pour chaque pages dynamiquemnent
            'meta' => [
                'title' => 'Administration des postes',
            ],
            'scripts' => [
                '/assets/js/switchVisibilityPoste.js',
            ],
            // 'styles' =>[
            //     '/assets/styles/postes'
            // ]
        ]);
    }

    #[Route('admin.poste.create', '/admin/postes/create', ['GET', 'POST'])]
    public function create(): Response
    {
        //On instancie le formulaire 
        $form = new PosteForm();

        //On vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->validate(['title', 'description'], $_POST)) {
            $title = strip_tags(trim($_POST['title']));
            $description = strip_tags(trim($_POST['description']));
            $enabled = isset($_POST['enabled']) ? true : false;

            //On envoe en BDD

            (new Poste)
                ->setTitle($title)
                ->setDescription($description)
                ->setEnabled($enabled)
                ->setUserId($_SESSION['USER']['id'])
                ->create()
            ;

            //On redirigie vers la page d'index avec un message de succès
            $this->addFlash('success', "Le poste a été créé avec succès !");
            return $this->redirectToRoute('admin.poste.index');
        }

        return $this->render('backend/poste/create.php', ['form' => $form->createForm()]);
    }

    #[Route('admin.poste.edit', '/admin/postes/([0-9]+)/edit', ['GET', 'POST'])]
    public function update(int $id): Response
    {

        //on recupere le poste à modifier


        /**
         * @var ?Poste $poste  (pour patcher les warning lié à VS Code)
         */


        $poste = (new Poste)->find($id);

        //Si le poste n'existe pas on redirige ver l'index avec un message d'erreur
        if (!$poste) {
            $this->addFlash('danger', 'le poste n\'existe pas !');

            return $this->redirectToRoute('admin.poste.index');
        }

        //on instancie le formulaire
        $form = new PosteForm($poste);

        //on verifie si le formulaire à été soumis et qu'il soit valide
        if ($form->validate(['title', 'description'], $_POST)) {
            //On recupere et nettoie les données du formulaire
            $title = strip_tags(trim($_POST['title']));
            $description = strip_tags(trim($_POST['description']));
            $enabled = isset($_POST['enabled']) ? true : false;

            //On met à jour l'objet poste et on persist en BDD
            $poste
                ->setTitle($title)
                ->setDescription($description)
                ->setEnabled($enabled)
                ->update()
            ;

            //On redirigie vers la page d'index avec un message de succès
            $this->addFlash('success', "Le poste a été modifié avec succès !");
            return $this->redirectToRoute('admin.poste.index');
        }
        return $this->render('backend/poste/update.php', [
            'form' => $form->createForm(),
        ]);
    }

    #[Route('admin.poste.delete', '/admin/postes/([0-9]+)/delete', ['POST'])]
    public function delete(int $id): Response
    {
        //on recupere le poste à supprimer
        $poste = (new Poste)->find($id);

        //Si le poste n'existe pas on redirige ver l'index avec un message d'erreur
        if (!$poste) {
            $this->addFlash('danger', 'le poste n\'existe pas !');

            return $this->redirectToRoute('admin.poste.index');
        }

        //on verifie si le token en session est celui qu'on a recuperer
        if ($_SESSION['csrf_token'] === $_POST['csrf_token'] ?? '') // si le token n'existe pas, on y insert une chaine de caracteres vide qui ne sera jamais égale au token csrf mais qui patchera les erreurs qui bloquerais le site
        {

            //On supprime le poste 
            $poste->delete();

            //on definit un message de succès
            $this->addFlash('success', "Le poste a bien été supprimé");
        } else {
            //on definit un message d'erreur
            $this->addFlash('danger', "Le token CSRF est invalide !");
        }

        //on redirige vers l'index dans tout les cas 
        return $this->redirectToRoute('admin.poste.index');
    }

    #[Route('admin.poste.switch', '/admin/api/postes/([0-9]+)/switch', ['GET'])]
    public function switch(int $id): Response
    {
        //On récupère le poste à modifier
        $poste = (new Poste)->find($id);

        //Si le poste n'existe pas, on renvoie une erreur 404

        if (!$poste) {
            $content = [
                'status' => 'error',
                'message' => 'Le poste n\'existe pas !',
            ];

            //en procedural
            // header('Content-Type: application/json');
            // http_response_code(404);
            // echo json_encode($content);
            return new Response(
                json_encode($content),
                404,
                ['Content-Type' => 'application/json'],

            );
        }
        //On inverse la visibilité du poste et on update la bdd
        $poste
            ->setEnabled(!$poste->getEnabled())
            ->Update();

        //On definit le contenu de la reponse
        $content = [
            'status' => 'success',
            'message' => 'Le poste a été mis à jour avec succèss !',
            'enabled' => $poste->getEnabled(),
        ];

        //on renvoie la reponse au format JSON
        return new Response(
            json_encode($content),
            201,
            ['Content-Type' => 'application/json'],
        );
    }
}
