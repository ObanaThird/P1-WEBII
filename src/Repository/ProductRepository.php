<?php

namespace Obana\App\Repository;

use Obana\App\Database\DatabaseConnection;
use Obana\App\Model\Product;
use PDO;
use PDOException;

class ProductRepository {
    private $connection;
    private $datetime;

    public function __construct() {
        $database = new DatabaseConnection();
        $this->connection = $database->getConnection();

        date_default_timezone_set('America/Sao_Paulo');
        $this->datetime = date('d/m/Y h:i:s a', time());
    }

    public function selectAllProducts() {
        $pdoStmt = $this->connection->prepare("SELECT * FROM Products");
        try {
            if ($pdoStmt->execute()) {
                $result = $pdoStmt->fetchAll(PDO::FETCH_ASSOC);
                if ($result > 0) {
                    return $result;
                }
            }
        } catch (PDOException) {
            return false;
        }

    }

    public function selectProducts(Product $product) {
        $id = $product->getId();
        $pdoStmt = $this->connection->prepare("SELECT * FROM Products WHERE id = :id");
        $pdoStmt->bindParam(':id', $id);
        try {
            if ($pdoStmt->execute()) {
                $result = $pdoStmt->fetch(PDO::FETCH_ASSOC);
                if ($result > 0) {
                    return $result;
                }
            }
        } catch (PDOException) {
            return false;
        }
    }

    public function insertProducts(Product $product) {
        $name = $product->getName();
        $description = $product->getDescription();
        $price = $product->getPrice();
        $storage = $product->getStorage();
        $userInsert = $product->getUserInsert();
        $datetime = $this->datetime;

        $pdoStmt = $this->connection->prepare("INSERT INTO Products 
        (name, description, price, storage, userInsert, date_time) VALUES 
        (:name, :description, :price, :storage, :userInsert, :date_time)");

        $pdoStmt->bindParam(':name', $name);
        $pdoStmt->bindParam(':description', $description);
        $pdoStmt->bindParam(':price', $price);
        $pdoStmt->bindParam(':storage', $storage);
        $pdoStmt->bindParam('userInsert', $userInsert);
        $pdoStmt->bindParam('date_time', $datetime);
        try {
            if ($pdoStmt->execute()) {
                $rowsAffected = $pdoStmt->rowCount();
                if ($rowsAffected > 0) {
                    return $rowsAffected;
                } else {
                    return false;
                }
                
            }
        } catch (PDOException) {
            return false;
        }
    }

    public function updateProducts(Product $product) {
        $idVerified = $this->selectProducts($product);
        
        try {
            if($idVerified !== false) {
                $id = $product->getId();
                $name = $product->getName();
                $description = $product->getDescription();
                $price = $product->getPrice();
                $storage = $product->getStorage();
                $userInsert = $product->getUserInsert();

                $pdoStmt = $this->connection->prepare("
                    UPDATE Products 
                    SET name = :name, 
                        description = :description, 
                        price = :price, 
                        storage = :storage, 
                        userInsert = :userInsert 
                    WHERE id = :id
                ");

                $pdoStmt->bindParam(':id', $id);
                $pdoStmt->bindParam(':name', $name);
                $pdoStmt->bindParam(':description', $description);
                $pdoStmt->bindParam(':price', $price);
                $pdoStmt->bindParam(':storage', $storage);
                $pdoStmt->bindParam(':userInsert', $userInsert);
                if ($pdoStmt->execute()) {
                    $rowsAffected = $pdoStmt->rowCount();
                    if ($rowsAffected > 0) {
                        return $rowsAffected;
                    } else {
                        return false;
                    }
                }
            }
        } catch (PDOException) {
            return false;
        }
    }

    public function eraseProducts(Product $product) {
        $idVerified = $this->selectProducts($product);

        try {
            $id = $product->getId();
            if($idVerified !== false) {
                $pdoStmt = $this->connection->prepare("DELETE FROM Products WHERE id = :id");
                $pdoStmt->bindParam(':id', $id);
                if ($pdoStmt->execute()) {
                    $rowsAffected = $pdoStmt->rowCount();
                    if ($rowsAffected > 0) {
                        return $rowsAffected;
                    } else {
                        return false;
                    } 
                }
            }
        } catch (PDOException) {
            return false;
        }
    }       
}
