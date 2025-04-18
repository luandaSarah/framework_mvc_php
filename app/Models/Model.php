<?php

namespace App\Models;

use App\Core\Database;

/** 
 * Pour indiquer à l'IDE que $id peut etre un int
 * @property-read int $id 
 */

//PDO(parent)->Database(enfant)->Model(petit enfant)->user(arrière petit enfant)
abstract class Model extends Database
{
    protected ?string $table = null; //prend le nom de la table, la meme pour tout les models qu'on créera

    protected ?Database $db = null;


    /*READ */
    //pour la requette findAll 
    public function findAll(): array
    {
        $pdoStatement =  $this
            ->runQuery("SELECT * FROM $this->table")
            ->fetchAll();

        return $this->fetchHydrate($pdoStatement);
    }

    //trouver une entrée d'une table à partir de son id
    public function find(int $id): bool|object
    {

        $pdoStatement = $this->runQuery(
            "SELECT * FROM $this->table WHERE id = :id",
            ['id' => $id]
        )
            ->fetch();

        return $this->fetchHydrate($pdoStatement);
    }

    //récupere un array correspondant à tout les critère(filtre) 
    public function findBy(array $filters): array
    {

        $champs = []; // tableau qui stockera les champs avec les markers SQL
        $params = [];  // tableau qui stockera les valeurs (tableau associatif execute)

        //On boucle sur le tableau qui stock les filtres en paramettre

        foreach ($filters as $champ => $valeur) {
            //stock dans champs, une chaine de caracteres qui va prendre la clé et lui donnera en valeurs le marqueur
            $champs[] = "$champ = :$champ";

            //On ajoute la caaleur dans le tableau de valeurs
            $params[$champ] = $valeur;
        }

        //va transformer le tableau en une chaine de caractere 
        //AND se metra entre chaque valeurs s'il y'en a plusieurs
        $listeChamps = implode(' AND ', $champs);


        //on execute en fin la requête 
        return $this
            ->runQuery(
                "SELECT * FROM $this->table WHERE $listeChamps",
                $params
            )
            ->fetchAll();
    }


    /*CREATE */

    //recupere l'objet (user, poste) que l'on a modifier avec hydrate
    //boucle sur cette objet et creer mon nouvel elemenet dans ma table en bdd
    public function create(): ?\PDOStatement
    {
        $champs = [];
        $markers = [];
        $params = [];

        foreach ($this as $champ => $valeur) {
            //si le champ est db, table ou la valeur === null alors on ne retourne rien on passe au tour suivant
            if ($champ === 'db' || $champ === 'table' || $valeur === null) {
                continue;
            }

            $champs[] = $champ;
            $markers[] = ":$champ";

            //patch les erreurs de conversions entre php et sql 
            //si la valeur est boolean ca va transformer (true, false) en (1, 0)
            if (gettype($valeur) === 'boolean') { //function gettype retourne le type d'une variable/function/props
                $valeur = (int) $valeur;
                //Ou regarde si la valeur est une instance de la classe date time
            } else if ($valeur instanceof \DateTime) {
                $valeur = $valeur->format('Y-m-d H:i:s');
            }

            $params[$champ] = "$valeur";
        }

        $listeChamps = implode(', ', $champs);
        $listeMarkers = implode(', ', $markers);


        return $this
            ->runQuery(
                "INSERT INTO $this->table($listeChamps) VALUES ($listeMarkers)",
                $params,
            );
    }

    //ne prends pas de paramettre car parcours $this 
    public function update(): ?\PDOStatement
    {
        $champs = [];
        $params = [];

        foreach ($this as $champ => $valeur) {
            if ($champ === 'db' || $champ === 'table' || $valeur === null || $champ === 'id') {
                continue;
            }

            $champs[] = "$champ = :$champ";

            //patch les erreurs de conversions entre php et sql 
            //si la valeur est boolean ca va transformer (true, false) en (1, 0)
            if (gettype($valeur) === 'boolean') { //function gettype retourne le type d'une variable/function/props
                $valeur = (int) $valeur;
                //Ou regarde si la valeur est une instance de la classe date time
            } else if ($valeur instanceof \DateTime) {
                $valeur = $valeur->format('Y-m-d H:i:s');
            }

            $params[$champ] = "$valeur";
        }

        $listeChamps = implode(', ', $champs);

        $params['id'] = $this->id;
        return $this
            ->runQuery(
                "UPDATE $this->table SET $listeChamps WHERE id = :id",
                $params,
            );
    }


    public function delete(): ?\PDOStatement
    {
        return $this
            ->runQuery(
                "DELETE FROM $this->table WHERE id = :id",
                ['id' => $this->id]
            );
    }

    //Va recuperer nos data de notre formulaire pour crée un nouvelle objet, nouveau user, poste etc... 
    //les clé correspond à notre champ formulaire et la valeur, ce que l'user entre 
    public function hydrate(array|object $data): static //data correspond au tableau asssociatif qu'on recupere avec create/update
    {

        foreach ($data as $key => $valeur) {
            //transforme les clé en methode correspondante 
            $method = 'set' . ucfirst($key);
            //on 
            if (method_exists($this, $method)) { //method_exists verifie si la methode en deuxieme params existe dans l'objet ou class ($this correspond a toute les classes qu'on instancie User, Postes, etc)
                if ($key === 'createdAt') {
                    if ($valeur !== null) {
                        $valeur = new \DateTime($valeur);
                    } else {
                        $valeur = new \DateTime('now');
                    }
                }
                //On execute la méthode
                $this->$method($valeur);
            }
        }
        return $this;
    }

    //transforme objet simple ou un tableau d'objet simple en objet de type de l'instance 
    public function fetchHydrate(mixed $query): array|static|bool //mixed accepte à tout type 
    {

        if (is_array($query) && count($query) > 0) {
            //Boucle sur le tableau de résultats pour instancier chaque objet

            // ! Méthode array_map
            return array_map(
                function ($object): static {
                    return (new static())->hydrate($object);
                },
                $query // le array map va boucler sur le tableau query et pour chaque élément du tableau, il va faire un hydrate
            );
            //! Fin de la Méthode array_map

            // TODO Version plus courte array_map
            // return array_map(
            //     fn ($object) => (new static())->hydrate($object),
            //     $query
            // );
            // TODO Fin de la version plus courte

            // $data = [];

            // foreach ($query as $object) {
            //     $data[] = (new static())->hydrate($object);
            // }

            // return $data;


        } else if (is_object($query)) {
            // On a un objet standard dans $query -> on instancie un objet de la classe et on hydrate
            return (new static())->hydrate($query);
        } else {
            return $query;
        }
    }

    //quel est l'objectif de la methode ? 
    //automatiser le lancement de la requête en verifiant si 
    //l'on doit faire une requete preparé ou simple
    protected function runQuery(string $sql, ?array $params = null): ?\PDOStatement //soit null soit PDOStatement
    {

        //On récupere la connexion en bdd
        $this->db = Database::getInstance();

        //On verifie si c'est une requette preparé ou non
        //si params est pas null
        if ($params !== null) {
            //c'est une requette preparé
            $query = $this->db->prepare($sql);
            $query->execute($params);

            return $query;
        } else {
            //alors c'est une requete simple
            return $this->db->query($sql);
        }
    }
}
