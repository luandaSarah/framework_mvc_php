<?php


namespace App\Controllers\Backend;

use App\Core\Route;
use App\Core\Response;
use App\Models\Categorie;
use App\Core\AbstractController;
use App\Form\CategorieForm;

class CategorieController extends AbstractController
{
    #[Route('admin.categories.index', '/admin/categories', ['GET'])]
    public function index(): Response
    {
        $categories = (new Categorie)->findAll();

        return $this->render('backend/categorie/index.php', [
            'categories' => $categories,
        ]);
    }

    #[Route('admin.categories.create', '/admin/categories/create', ['GET', 'POST'])]
    public function create(): Response
    {
        $form = (new CategorieForm)->createForm();

        
        return $this->render('backend/categorie/create.php', [
            'form' => $form,
        ]);
    }
}
