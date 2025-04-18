<?php

namespace App\Controllers;

use App\Core\AbstractController;
use App\Core\Response;
use App\Core\Route;
use App\Models\Poste;

class PosteController extends AbstractController
{
    #[Route('app.poste.show', '/poste/details/([0-9]+)', ['GET'])] // convention de nommage app.home, admin.home etc ...[Route('app.poste.show', '/poste/details/{id}', ['GET'])]
    public function show(int $id): Response
    {
        $poste = (new Poste())->find($id);

        // require DIR_ROOT . '/Views/postes/show.php';

        return  $this->render('postes/show.php', [
            'poste' => $poste,
        ]);
    }
}
