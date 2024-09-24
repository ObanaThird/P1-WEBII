<?php

namespace Obana\App\Model;

class Product {
    private $id;
    private $name;
    private $description;
    private $price;
    private $storage;
    private $datetime;

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

    public function getDatetime() {
        return $this->datetime;
    }
    
    public function setDatetime($datetime) {
        $this->datetime = $datetime;
    }

}