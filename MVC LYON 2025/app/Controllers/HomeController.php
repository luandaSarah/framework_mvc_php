<?php

namespace App\Controllers;

use App\Core\Route;
use App\Models\Poste;
use App\Core\Response;
use App\Core\AbstractController;

class HomeController extends AbstractController
{
    #[Route('app.home', '/', ['GET'])] // convention de nommage app.home, admin.home etc ...
    public function index(): Response
    {
        $postes = (new Poste)->findAll();
        return $this->render('home/index.php', ['postes' => $postes]);
    }
}
