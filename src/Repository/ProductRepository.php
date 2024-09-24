<?php

namespace Obana\App\Repository;

use Obana\App\Database\DatabaseConnection;
use Obana\App\Model\Product;
use PDO;

class ProductRepository {
    private $connection;
    public function __construct() {
        $database = new DatabaseConnection();
        $this->connection = $database->getConnection();
    }

    public function selectAllProducts() {
        $stmt = $this->connection->prepare("SELECT * FROM Products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function selectProducts(Product $product) {
        $id = $product->getId();
        $pdoStmt = $this->connection->prepare("SELECT * FROM Products WHERE id = :id");
        $pdoStmt->bindParam(':id', $id);
        $pdoStmt->execute();
        return $pdoStmt->fetch(PDO::FETCH_ASSOC);
        

    }

    public function insertProducts(Product $product) {
        $name = $product->getName();
        $description = $product->getDescription();
        $price = $product->getPrice();
        $storage = $product->getStorage();

        $datetime = date('m/d/Y h:i:s a', time());

        $pdoStmt = $this->connection->prepare("INSERT INTO Products (name, description, price, storage, date_time) VALUES (:name, :description, :price, :storage, :date_time)");
        $pdoStmt->bindParam(':name', $name);
        $pdoStmt->bindParam(':description', $description);
        $pdoStmt->bindParam(':price', $price);
        $pdoStmt->bindParam(':storage', $storage);
        $pdoStmt->bindParam('date_time', $datetime);
        $pdoStmt->execute();
    }
}