<?php

namespace App\Controllers;

use App\Core\AbstractController;
use App\Core\Response;
use App\Core\Route;

class HomeController extends AbstractController
{
    #[Route('app.home', '/', ['GET'])] // convention de nommage app.home, admin.home etc ...
    public function index(): Response
    {
        return $this->render('home/index.php');
    }

}