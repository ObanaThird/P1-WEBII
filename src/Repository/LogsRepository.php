<?php

namespace Obana\App\Repository;

use Obana\App\Database\DatabaseConnection;
use Obana\App\Model\LogModel;

use PDO;
use PDOException;

class LogsRepository {
    private $connection;
    private $datetime;

    public function __construct() {
        $database = new DatabaseConnection();
        $this->connection = $database->getConnection();

        date_default_timezone_set('America/Sao_Paulo');
        $this->datetime = date('d/m/Y h:i:s a', time());
    }

    public function insertLogs(LogModel $log){
        $operationType =  $log->getOperationType();
        $datetime = $this->datetime;
        $id = $log->getIdProduct();
        
        if($id === null) {
            $id = $this->idNull();
        }
        
        $pdoStmt = $this->connection->prepare("INSERT INTO Logs (operationType, date_time, idProduct) VALUES (:operationType, :date_time, :idProduct)");
        $pdoStmt->bindParam(':operationType', $operationType);
        $pdoStmt->bindParam(':date_time', $datetime);
        $pdoStmt->bindParam(':idProduct', $id);
        try {
            if($pdoStmt->execute()) {
                return true;
            }
        } catch (PDOException) {
            return false;
        }
    }

    public function idNull() {
        $pdoStmt = $this->connection->prepare("SELECT id FROM Products ORDER BY id DESC LIMIT 1");
        
        try {
            if ($pdoStmt->execute()) {
                $result = $pdoStmt->fetch(PDO::FETCH_ASSOC);
                if ($result > 0) {
                    return $result['id'];
                }
            }
        } catch (PDOException) {
            return false;
        }
    }

    public function selectLogs() {
        $pdoStmt = $this->connection->prepare("SELECT * FROM Logs");
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
}