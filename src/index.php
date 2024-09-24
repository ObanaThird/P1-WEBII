<?php

namespace Obana\App;

require_once '../vendor/autoload.php';

use Obana\App\Controller\ProductController;

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


switch($method) {
    case 'GET':
        switch($uri) {
            case '/products':
                if(preg_match('/\/users\/(\+d)/', $uri, $match)){
                    $id = $match[1];
                    $productController = new ProductController();
                    $productController->getProducts($id);
                    http_response_code(200);
                    var_dump($id);
                } else {
                    $productController = new ProductController;
                    $productController->getAllProducts();
                }              
            break;
           
        }
    break;

    case 'POST':
        switch($uri) {
            case '/products';
            $data = json_decode(file_get_contents('php://input'), true);
        
            if (empty($data['name']) || empty($data['description']) || empty($data['price']) || empty($data['storage'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Todos os campos são obrigatórios.']);
                return;
            }

            $productController = new ProductController();
            $productController->postProducts($data);

            /*
            {
                "name": "sacoasdasdla",
                "description": "meeqwqweal",
                "price": 19.99,
                "storage": 3
            }
            */
            break;
        }
    break;
}
