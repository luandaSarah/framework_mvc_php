<?php

namespace App\Models;

//herite de Model(parent)
class User extends Model 
{

    public function __construct()
    {
        $this->table = "users"; //$table correspond à la proprité créée dans Model(parent) //defini le nom de la table 
    }
}