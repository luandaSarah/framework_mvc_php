<?php

namespace App\Form;

use App\Core\Form;
use App\Models\Categorie;

class CategorieForm extends Form
{
    public function __construct(?Categorie $categorie = null, string $action = '#')
    {
        $this
            ->startForm($action, 'POST', ['class' => 'form card p-3 w-75 mx-auto'])
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('name', 'Nom:', ['class' => 'form-label'])
            ->addInput('text', 'name', [
                'class' => 'form-control',
                'id' => 'name',
                'placeholder' => 'Nom de la catégorie',
                'required' => true,
                'value' => $categorie?->getName(), //si $poste defini alors tu peux recuperer getTitle
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('description', 'Description:', ['class' => 'form-label'])
            ->addTextarea('description', '', [
                'class' => 'form-control',
                'id' => 'description',
                'rows' => 7,
                'placeholder' => 'Description de la catégorie',
                'required' => true,
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3 form-check form-switch'])
            ->addInput('checkbox', 'enabled', [
                'class' => 'form-check-input',
                'id' => 'enabled',
            ])
            ->addLabel('enabled', 'Actif', ['class' => 'form-check-label'])
            ->endDiv()
            ->addButton('Envoyer', [
                'class' => 'btn btn-primary',
                'type' => 'submit',
            ])
            ->endForm()
        ;
    }
}
