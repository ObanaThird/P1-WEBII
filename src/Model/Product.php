<?php

namespace Obana\App\Model;

class Product {
    private $id;
    private $name;
    private $description;
    private $price;
    private $storage;
    private $userInsert;
    private $operationType;

    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }
    
    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPrice() {
        return $this->price;
    }
    
    public function setPrice($price) {
        $this->price = $price;
    }

    public function getStorage() {
        return $this->storage;
    }
    
    public function setStorage($storage) {
        $this->storage = $storage;
    }

    public function getUserInsert() {
        return $this->userInsert;
    }
    
    public function setUserInsert($userInsert) {
        $this->userInsert = $userInsert;
    }

    public function getOperationType() {
        return $this->operationType;
    }
    
    public function setOperationType($operationType) {
        $this->operationType = $operationType;
    }


}