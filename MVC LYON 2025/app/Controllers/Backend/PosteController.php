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



        return $this->render('backend/poste/index.php', ['postes' => $postes]);
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
                ->create()
            ;

            //On redirigie vers la page d'index avec un message de succès
            $this->addFlash('success', "Le poste a été créé avec succès !");
            return $this->redirectToRoute('admin.poste.index');
        }

        return $this->render('backend/poste/create.php', ['form' => $form->createForm()]);
    }
}
