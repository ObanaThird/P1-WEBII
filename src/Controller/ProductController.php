<?php

namespace Obana\App\Controller;

use Obana\App\Model\Product;
use Obana\App\Repository\ProductRepository;
use PDOException;

Class ProductController {

    public function getAllProducts(){
        $productRepository = new ProductRepository();
        $products = $productRepository->selectAllProducts();

        header('Content-Type: application/json');
        if ($products) {
            http_response_code(200);
            echo json_encode($products);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Nenhum produto cadastrado.']);
        }

    }

    public function getProducts($id){
        $product = new Product();
        $product->setId($id);

        $productRepository = new ProductRepository();
        $result = $productRepository->selectProducts($product);
        header('Content-Type: application/json');
        if($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'ID não encontrado.']);
        }
    }

    public function postProducts($data, $method) {
        header('Content-Type: application/json');
        if (empty($data['name']) || empty($data['description']) || empty($data['price']) || empty($data['storage']) || empty($data['userInsert'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
            return;
        }
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $storage = $data['storage'];
        $userInsert = $data['userInsert'];

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setStorage($storage);
        $product->setUserInsert($userInsert);
        $product->setUserInsert($userInsert);
        $product->setOperationType($method);

        $productRepository = new ProductRepository();
        $productRepository->insertProducts($product);
    }

    public function putProducts($id, $data, $method) {
        header('Content-Type: application/json');
        if (empty($data['name']) || empty($data['description']) || empty($data['price']) || empty($data['storage']) || empty($data['userInsert'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
            return;
        }
        try{
            $product = new Product();
        
            $name = $data['name'];
            $description = $data['description'];
            $price = $data['price'];
            $storage = $data['storage'];
            $userInsert = $data['userInsert'];
    
            $product->setId($id);
            $product->setName($name);
            $product->setDescription($description);
            $product->setPrice($price);
            $product->setStorage($storage);
            $product->setUserInsert($userInsert);
            $product->setOperationType($method);
    
            $productRepository = new ProductRepository();
            $productRepository->updateProducts($product);
        } catch (PDOException $e) {
            echo 'Erro:' . $e->getMessage();
        }

        
    }

    public function deleteProducts($id, $method) {
        $product = new Product();
        $product->setId($id);
        $product->setOperationType($method);

        $productRepository = new ProductRepository();
        $productRepository->eraseProducts($product);
    }

    public function getLogs(){
        $productRepository = new ProductRepository();
        $logs = $productRepository->selectLogs();
        header('Content-Type: application/json');
        if ($logs) {
            http_response_code(200);
            echo json_encode($logs);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Nenhum log encontrado.']);
        }
    }


}