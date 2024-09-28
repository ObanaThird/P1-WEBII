<?php

namespace Obana\App\Database;
use PDO;
use PDOException;

class DatabaseConnection {
    private $path;

    public function __construct() {
        $this->path = __DIR__ . '/ProductsDatabase.sqlite';
    }

    public function getConnection(){
        try {
            $pdo = new PDO ("sqlite:$this->path");
            return $pdo;
        } catch (PDOException $e) {
            echo 'Erro ao conectar: ' . $e->getMessage();
        }
    }

}