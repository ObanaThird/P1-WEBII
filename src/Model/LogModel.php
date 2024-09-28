<?php

namespace Obana\App\Model;

class LogModel {
    private $idProduct;
    private $operationType;

    public function getIdProduct() {
        return $this->idProduct;
    }
    
    public function setIdProduct(Product $product) {
        $this->idProduct = $product->getId();
    }

    public function getOperationType() {
        return $this->operationType;
    }
    
    public function setOperationType($method) {
        $this->operationType = $method;
    }
}