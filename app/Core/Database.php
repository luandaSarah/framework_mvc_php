<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Cette classe permet la connexion vers notre base de donnée
 * Toute les requêtes vers la BDD de nos Models communique avec cette class
 * 
 */
class Database extends PDO //herite de tout dans PDO ainsi que son construct
{
    private static ?self $instance = null;

    private const DB_HOST = 'mvclyon2025-db-1';
    private const DB_NAME = 'mvc_cours';
    private const DB_USER = 'root';
    private const DB_PASSWORD = 'root';

    public function __construct()
    {
        $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";

        try {
         //parent correspond à la classe parent PDO

            parent::__construct(
                $dsn,
                self::DB_USER,
                self::DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, //quand on fetch on ne renvoie plus un tableau assoc mais un objet simple
                ]
            );
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public static function getInstance(): self
    {
        //design pattern singleton : Pour éviter de créer plusieurs fois cette instance, 
        //regarde si cette instance existe l'utiliser sinon la créer

        //si et seulement si  la propriété $instance est null 
        if(self::$instance === null) {
        //instancie la connexion avec la bdd 
            self::$instance = new self(); //new Database()
        }

        return self::$instance;
    }
}