<?php

namespace Obana\App\Database;
use Obana\App\Database\DatabaseConnection;

require '../../vendor/autoload.php';


class ProductManagement {
    private $connection;
    public function __construct() {
        $databaseConnection = new DatabaseConnection();
        $this->connection = $databaseConnection->getConnection();
    }

    public function createProducts() {
        $this->connection->exec('CREATE TABLE Products (id INTEGER PRIMARY KEY, name TEXT, description TEXT, price INTEGER, storage INTEGER, date_time TEXT);');
    }

}

$ProductManagement = new ProductManagement();
$ProductManagement->createProducts();