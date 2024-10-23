<?php 

namespace Obana\App\Repository;

use Obana\App\Database\DatabaseConnection;
use Obana\App\Model\UserModel;
use PDO;
use PDOException;

class UserRepository{
    private $connection;

    public function __construct() {
        $database = new DatabaseConnection();
        $this->connection = $database->getConnection();
    }

    public function selectAllUsers(){
        $pdoStmt = $this->connection->prepare("SELECT * FROM Users");
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

    public function selectUsers(UserModel $userModel){
        $id = $userModel->getId();
        $pdoStmt = $this->connection->prepare("SELECT * FROM Users WHERE id = :id");
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

    public function userLogin(UserModel $userModel){
        $password = $userModel->getPassword();

        $result = $this->selectUserByEmail($userModel);

        if($result){
            try{
                if(password_verify($password, $result['userPassword'])){
                    return $result['userName'];
                } else {
                    return false;
                }
                    
            } catch(PDOException $e){
                return $e->getMessage();
            }
        } else {
            return false;
        }
    }

    public function insertUser(UserModel $userModel){
        $name = $userModel->getName();
        $email = $userModel->getEmail();
        $password = $userModel->getPassword();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $result = $this->selectUserByEmail($userModel);

        if(!$result){
            $pdoStmt = $this->connection->prepare("INSERT INTO Users 
            (userName, email, userPassword) VALUES (:userName, :email, :userPassword)");
    
            $pdoStmt->bindParam(':userName', $name);
            $pdoStmt->bindParam(':email', $email);
            $pdoStmt->bindParam(':userPassword', $hash);

            try{
                if($pdoStmt->execute()){
                    $rowsAffected = $pdoStmt->rowCount();
                    return $rowsAffected > 0;
                }
            } catch(PDOException $e){
                return $e->getMessage();
            }
        } else {
            return false;
        }
    }


    public function selectUserByEmail(UserModel $userModel){
        $email = $userModel->getEmail();

        $pdoStmt = $this->connection->prepare("SELECT * FROM Users WHERE email = :email");
        $pdoStmt->bindParam(':email', $email);

        try{
            if($pdoStmt->execute()){
                $result = $pdoStmt->fetch(PDO::FETCH_ASSOC);
                if($result){
                    return $result;
                }
            }
        } catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function updateUser(UserModel $userModel){
        $id = $userModel->getId();
        $name = $userModel->getName();
        $email = $userModel->getEmail();
        $password = $userModel->getPassword();

        $pdoStmt = $this->connection->prepare("UPDATE Users SET 
        userName = :userName,
        email = :email,
        userPassword = :userPassword WHERE id = :id");

        $pdoStmt->bindParam(':id', $id);
        $pdoStmt->bindParam(':userName', $name);
        $pdoStmt->bindParam(':email', $email);
        $pdoStmt->bindParam(':userPassword', $password);

        try{
            if($pdoStmt->execute()){
                $rowsAffected = $pdoStmt->rowCount();
                return $rowsAffected > 0;
            }
        } catch (PDOException $e){
            return $e->getMessage();
        }

    }

    public function eraseUser(UserModel $userModel){
        $id = $userModel->getId();

        $pdoStmt = $this->connection->prepare("DELETE FROM Users WHERE id = :id");
        $pdoStmt->bindParam(':id', $id);

        try{
            if($pdoStmt->execute()){
                $rowsAffected = $pdoStmt->rowCount();
                return $rowsAffected > 0;
            }
        } catch (PDOException $e){
            $e->getMessage();
        }
    }

}