<?php

namespace Obana\App;

require_once '../vendor/autoload.php';

use Obana\App\Controller\ProductController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


switch($method) {
    case 'GET':
        $id = null;
        if (preg_match('/\/products\/(\d+)/', $uri, $match)) {
            $id = $match[1];
        }

        switch (true) {
            case ($id !== null):
                $productController = new ProductController();
                $productController->getProducts($id);
            break;

            case ($uri === '/products'):
                $productController = new ProductController();
                $productController->getAllProducts();
            break;
            case ($uri === '/logs'):
                $productController = new ProductController();
                $productController->getLogs();
            break;

            default:
                http_response_code(404);
                echo "Endpoint não encontrado.";
            break;
        }
    break;

    case 'POST':
        switch($uri) {
            case '/products';
                $data = json_decode(file_get_contents('php://input'), true);
                $productController = new ProductController();
                $productController->postProducts($data, $method);
            break;

            default:
                http_response_code(404);
                echo "Endpoint não encontrado.";
            break;
        }
    break;

    case 'PUT':
        $id = null;
        if (preg_match('/\/products\/(\d+)/', $uri, $match)) {
            $id = $match[1];
        }
                
        switch(true) {
            case ($id !== null):
                $data = json_decode(file_get_contents('php://input'), true);
                $productController = new ProductController();
                $productController->putProducts($id, $data, $method);
            break;
        }   
    break;
    case 'DELETE':
        $id = null;
        if (preg_match('/\/products\/(\d+)/', $uri, $match)) {
            $id = $match[1];
        }
                
        switch(true) {
            case ($id !== null):
                $productController = new ProductController();
                $productController->deleteProducts($id, $method);
            break;
        }  
    break;
            
}
