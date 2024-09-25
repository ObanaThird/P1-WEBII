<?php

namespace Obana\App\Repository;

use Obana\App\Database\DatabaseConnection;
use Obana\App\Model\Product;
use PDO;
use PDOException;

class ProductRepository {
    private $connection;
    public function __construct() {
        $database = new DatabaseConnection();
        $this->connection = $database->getConnection();
    }

    public function selectAllProducts() {
        $pdoStmt = $this->connection->prepare("SELECT * FROM Products");
        $pdoStmt->execute();
        return $pdoStmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function selectProducts(Product $product) {
        $id = $product->getId();
        $pdoStmt = $this->connection->prepare("SELECT * FROM Products WHERE id = :id");
        $pdoStmt->bindParam(':id', $id);
        $pdoStmt->execute();
        $result = $pdoStmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insertProducts(Product $product) {
        $name = $product->getName();
        $description = $product->getDescription();
        $price = $product->getPrice();
        $storage = $product->getStorage();
        $userInsert = $product->getUserInsert();
        date_default_timezone_set('America/Sao_Paulo');
        $datetime = date('d/m/Y h:i:s a', time());

        $pdoStmt = $this->connection->prepare("INSERT INTO Products (name, description, price, storage, userInsert, date_time) VALUES (:name, :description, :price, :storage, :userInsert, :date_time)");
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
                    echo "Produto cadastrado com sucesso. Linhas afetadas: $rowsAffected";
                    $this->insertLogs($product);
                } else {
                    echo "Produto não cadastrado. Linhas afetadas: $rowsAffected";
                }
            }
        } catch (PDOException $e) {
            echo "Erro ao cadastrar o produto: " . $e->getMessage();
        }
    }

    public function updateProducts(Product $product) {
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
        try {
            if ($pdoStmt->execute()) {
                $rowsAffected = $pdoStmt->rowCount();
                if ($rowsAffected > 0) {
                    echo "Produto atualizado com sucesso. Linhas afetadas: $rowsAffected";
                    $this->insertLogs($product);
                } else {
                    echo "Produto não encontrado. Linhas afetadas: $rowsAffected";
                }
            }
        } catch (PDOException $e) {
            echo "Erro ao atualizar o produto: " . $e->getMessage();
        }

    }

    public function eraseProducts(Product $product) {
        $id = $product->getId();

        $pdoStmt = $this->connection->prepare("DELETE FROM Products WHERE id = :id");
        $pdoStmt->bindParam(':id', $id);
        try {
            if ($pdoStmt->execute()) {
                $rowsAffected = $pdoStmt->rowCount();
                if ($rowsAffected > 0) {
                    echo "Produto excluído com sucesso. Linhas afetadas: $rowsAffected";
                    $this->insertLogs($product);
                } else {
                    echo "Produto não encontrado. Linhas afetadas: $rowsAffected";
                }
            }
        } catch (PDOException $e) {
            echo "Erro ao excluir o produto: " . $e->getMessage();
        }
    }

    public function insertLogs(Product $product){
        $operationType =  $product->getOperationType();
        date_default_timezone_set('America/Sao_Paulo');
        $datetime = date('d/m/Y h:i:s a', time());
        $id = $product->getId();
        if($id === null) {
            $id = $this->idNull();
        }
        
        $pdoStmt = $this->connection->prepare("INSERT INTO Logs (operationType, date_time, idProduct) VALUES (:operationType, :date_time, :idProduct)");
        $pdoStmt->bindParam(':operationType', $operationType);
        $pdoStmt->bindParam(':date_time', $datetime);
        $pdoStmt->bindParam(':idProduct', $id);
        $pdoStmt->execute();
    }

    public function idNull() {
        $pdoStmt = $this->connection->prepare("SELECT id FROM Products ORDER BY id DESC LIMIT 1");
        $pdoStmt->execute();
        $result = $pdoStmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];

    }

    public function selectLogs() {
        $pdoStmt = $this->connection->prepare("SELECT * FROM Logs");
        $pdoStmt->execute();
        return $pdoStmt->fetchAll(PDO::FETCH_ASSOC);
    }
        
}
