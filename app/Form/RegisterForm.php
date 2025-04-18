<?php

namespace App\Form;

use App\Core\Form;

class RegisterForm extends Form
{
    public function __construct()
    {
        $this
            ->startForm('/register', 'POST', [
                'class' => 'form card p-3 w-50 mx-auto',
                'id' => 'form-register',
            ])

            //lastname
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('lastName', 'Nom:', [
                'class' => 'form-label',
            ])
            ->addInput('text', 'lastName', [
                'class' => 'form-control',
                'placeholder' => 'Doe',
                'id' => 'lastName',
                'required' => true,
            ])
            ->endDiv()

            //firstname
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('firstName', 'Prénom:', [
                'class' => 'form-label',
            ])
            ->addInput('text', 'firstName', [
                'class' => 'form-control',
                'placeholder' => 'john',
                'id' => 'firstName',
                'required' => true,
            ])
            ->endDiv()
            
            //email
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('email', 'Email:', [
                'class' => 'form-label',
            ])
            ->addInput('email', 'email', [
                'class' => 'form-control',
                'placeholder' => 'john@example.com',
                'id' => 'email',
                'required' => true,
            ])
            ->endDiv()

            //password
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('password', 'Mot de passe:', [
                'class' => 'form-label',
            ])
            ->addInput('password', 'password', [
                'id' => 'password',
                'class' => 'form-control',
                'placeholder' => 'S3CR3T',
                'required' => true,
            ])
            ->endDiv()
            ->addButton('Inscription', [
                'class' => 'btn btn-primary',
            ])
            ->endForm();
    }
}
