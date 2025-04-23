<?php

namespace App\Controllers\Backend; // ce dossier permet de gerer les controllers qui s'occupent du back de notre app

use App\Core\AbstractController;
use App\Core\Response;
use App\Core\Route;
use App\Models\Poste;


class PosteController extends AbstractController
{
    #[Route('admin.poste.index', '/admin/poste', ['GET'])]

    public function index(): Response
    {
        $postes = (new Poste)->findAll();


        return $this->render('backend/poste/index.php', ['poste' => $postes,]);
    }
}
