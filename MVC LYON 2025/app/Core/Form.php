<?php

namespace App\Core;

class Form
{
    /**
     * Propriété qui stock le code HTML du formulaire 
     * 
     */
    protected string $formCode = '';


    /**
     * Summary of validate
     * @param array $requiredFields = Champs nécessaire à la validation
     * @param array $submitedField = Tout les champs reçu, correspond à $_POST
     * @return void
     */
    public static function validate(array $requireFields, array $submitFields): bool
    {
        // ['email', 'password', 'lastname', 'firstname']
        // ['email' => 'test@test.com', 'password' => 'test', 'lastname' => 'Doe', 'firstname' => 'John']
        // On boucle sur le tableau de champs obligatoires
        foreach ($requireFields as $requireField) {
            if (empty($submitFields[$requireField]) || strlen(trim($submitFields[$requireField])) === 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Ouvre la balise form
     * @param string $action = action à la soumission du formulaire
     * @param string $method = method http
     * @param mixed $attributes = attributs html
     * @return Form
     */
    public function startForm(string $action, string $method = "POST", $attributes = []): static
    {
        //Rendu attendu = <form action="#" method="POST">

        $this->formCode .= "<form action=\"$action\" method=\"$method\"";

        $this->formCode .= $this->addAtributes($attributes) . ">";

        return  $this;
    }


    /**
     * Ferme la balise form
     * @return Form
     */
    public function endForm(): static
    {
        $this->formCode .= "</form>";
        return $this;
    }


    /**
     * Ouvre la balise Div
     * @param array $attributes = attribut html
     * @return Form
     */
    public function startDiv(array $attributes = []): static
    {
        $this->formCode .= "<div" . $this->addAtributes($attributes) . ">";

        return $this;
    }

    /**
     * Ferme la balise Div
     * @return Form
     */
    public function endDiv(): static
    {
        $this->formCode .= "</div>";
        return $this;
    }


    /**
     * Ajoute des labels aux formulaires
     * @param string $for
     * @param string $text
     * @return static
     */
    public function addLabel(string $for, string $text, array $attributes): static
    {
        // <label for="email">Email:</label>

        $this->formCode .= "<label for=\"$for\"" . $this->addAtributes($attributes) . ">$text</label>";
        return $this;
    }

    public function  addInput(string $type, string $name, array $attributes = []): static
    {

        //<input type="email" name="email" id="email" />
        $this->formCode .= "<input type=\"$type\" name=\"$name\"" . $this->addAtributes($attributes) . "/>";

        return $this;
    }

    public function addButton(string $text, array $attributes = []): static
    {
        $this->formCode .= "<button type\"submit\"" . $this->addAtributes($attributes) . ">$text</button>";
        return $this;
    }


    /**
     * Cette methode ajoute automatiquement les attributs html aux éléments de formulaires
     * @param array $attributes
     * @return string
     */
    public function addAtributes(array $attributes): string
    {
        //On crée une chaine de caractères vide
        $attributeString = "";

        $short = ['checked', 'disabled', 'readonly', 'selected', 'multiple', 'required'];

        foreach ($attributes as $key => $value) {

            //On vérifie si c'est un attribut court
            if (in_array($key, $short)) {
                $attributeString .= " $key";
            } else {
                $attributeString .= " $key=\"$value\"";
            }
        }
        return $attributeString;
    }

    /**
     * Renvoe le code HTML du formulaire stocké dans la propriété $formCode
     * @return string
     */
    public function createForm(): string
    {
        return $this->formCode;
    }
}
