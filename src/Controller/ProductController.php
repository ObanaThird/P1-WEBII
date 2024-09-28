<?php

namespace Obana\App\Controller;

use Obana\App\Functions\GreaterThanZero;
use Obana\App\Functions\LowerThanThree;
use Obana\App\Functions\PositivePrice;
use Obana\App\Functions\ValidateFields;
use Obana\App\Model\Product;
use Obana\App\Repository\ProductRepository;
use PDOException;

Class ProductController {
    public function __construct() {
        header('Content-Type: application/json');
    }

    public function getAllProducts() {
        
        try {
            $productRepository = new ProductRepository();
            $products = $productRepository->selectAllProducts();
    
            if ($products) {
                http_response_code(200);
                echo json_encode($products);
            } else {
                http_response_code(404);
                echo json_encode(['Mensagem' => 'Nenhum produto cadastrado.']);
            }
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['Erro' => $e->getMessage()]);
        }
    }

    public function getProducts($id) {
        
        try {
            $product = new Product();
            $product->setId($id);
    
            $productRepository = new ProductRepository();
            $result = $productRepository->selectProducts($product);
            
            if($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'ID não encontrado.']);
            }
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['Erro' => $e->getMessage()]);
        }
    }

    public function postProducts($data, $method) {
        
        $missingFields = new ValidateFields();
        $requiredFields = ['name', 'description', 'price', 'storage', 'userInsert'];
        $resultFields = $missingFields->validateFields($data, $requiredFields);

        if (!empty($resultFields)) {
            http_response_code(400);
            echo json_encode([
                'Mensagem' => 'Todos os campos são obrigatórios.',
                'Campos não preenchidos' => $resultFields
            ]);
            return;
        }

        $lowerThanThree = new LowerThanThree();
        $resultLower = $lowerThanThree->lowerThanThree($data);

        if($resultLower === false) {
            http_response_code(400);
            echo json_encode(['Mensagem' => 'O nome do produto precisa ter pelo menos 3 caracteres.']);
            return;
        }

        $positivePrice = new PositivePrice();
        $resultPositive = $positivePrice->positivePrice($data);

        if($resultPositive === false) {
            http_response_code(400);
            echo json_encode(['Mensagem' => 'O valor do produto precisa ser positivo e maior que 0.']);
            return;
        }

        $zeroDecimal = new GreaterThanZero();
        $resultZero = $zeroDecimal->greaterThanZero($data); 
        if($resultZero === false) {
            http_response_code(400);
            echo json_encode(['Mensagem' => 'O estoque do produto precisa ser positivo e inteiro.']);
            return;
        }

        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $storage = $data['storage'];
        $userInsert = $data['userInsert'];

        try{
            $product = new Product();
            $product->setName($name);
            $product->setDescription($description);
            $product->setPrice($price);
            $product->setStorage($storage);
            $product->setUserInsert($userInsert);

            $productRepository = new ProductRepository();
            $result = $productRepository->insertProducts($product);

            $logController = new LogController();
            $logResult = $logController->postLogs($product, $method);

            if($result !== false && $logResult !== false) {
                http_response_code(200);
                echo json_encode(['Mensagem' => 'Produto cadastrado e Log criado com sucesso.']);
            } else {
                http_response_code(400);
                echo json_encode(['Mensagem' => 'Produto não cadastrado.']);
            }  

        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['Erro' => $e->getMessage()]);
        }

    }

    public function putProducts($id, $data, $method) {
        
        $missingFields = new ValidateFields();
        $requiredFields = ['name', 'description', 'price', 'storage', 'userInsert'];

        $result = $missingFields->validateFields($data, $requiredFields);
        if (!empty($result)) {
            http_response_code(400);
            echo json_encode([
                'Mensagem' => 'Todos os campos são obrigatórios.',
                'Campos não preenchidos' => $result
            ]);
            return;
        }

        $lowerThanThree = new LowerThanThree();
        $resultLower = $lowerThanThree->lowerThanThree($data);

        if($resultLower === false) {
            http_response_code(400);
            echo json_encode(['Mensagem' => 'O nome do produto precisa ter pelo menos 3 caracteres.']);
            return;
        }

        $positivePrice = new PositivePrice();
        $resultPositive = $positivePrice->positivePrice($data);

        if($resultPositive === false) {
            http_response_code(400);
            echo json_encode(['Mensagem' => 'O valor do produto precisa ser positivo e maior que 0.']);
            return;
        }

        $zeroDecimal = new GreaterThanZero();
        $resultZero = $zeroDecimal->greaterThanZero($data); 
        if($resultZero === false) {
            http_response_code(400);
            echo json_encode(['Mensagem' => 'O estoque do produto precisa ser positivo e inteiro.']);
            return;
        }

        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $storage = $data['storage'];
        $userInsert = $data['userInsert'];

        try{
            $product = new Product();
            $product->setId($id);
            $product->setName($name);
            $product->setDescription($description);
            $product->setPrice($price);
            $product->setStorage($storage);
            $product->setUserInsert($userInsert);
            $product->setOperationType($method);
        
            $productRepository = new ProductRepository();
            $result = $productRepository->updateProducts($product);

            $logController = new LogController();
            $logResult = $logController->postLogs($product, $method);
       
            if($result !== false && $logResult !== false) {
                http_response_code(200);
                echo json_encode(['Mensagem' => 'Produto atualizado e Log criado com sucesso.']);
            } else {
                http_response_code(400);
                echo json_encode(['Mensagem' => 'Produto não atualizado.']);
            }
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['Erro' => $e->getMessage()]);
        }
    }

    public function deleteProducts($id, $method) {
        
        try {
            $product = new Product();
            $product->setId($id);
    
            $productRepository = new ProductRepository();
            $result = $productRepository->eraseProducts($product);

            $logController = new LogController();
            $logResult = $logController->postLogs($product, $method);

            if($result !== false && $logResult !== false) {
                http_response_code(200);
                echo json_encode(['Mensagem' => 'Produto excluído e Log criado com sucesso.']);
            } else {
                http_response_code(400);
                echo json_encode(['Mensagem' => 'Produto não excluído.']);
            }

        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['Erro' => $e->getMessage()]);
        }
    }  
}