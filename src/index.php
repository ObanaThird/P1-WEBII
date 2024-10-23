<?php

namespace Obana\App;

require_once '../vendor/autoload.php';

use Obana\App\Controller\ProductController;
use Obana\App\Controller\LogController;
use Obana\App\Controller\UserController;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Access-Control-Allow-Origin: http://127.0.0.1:7000');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


switch($method) {
    case 'GET':
        $id = null;

        if (preg_match('/\/(\w+)\/(\d+)/', $uri, $match)) {
            $resource = $match[1];
            $id = $match[2];
        } else {
            $resource = trim($uri, '/');
        }

        switch (true) {
            case ($resource === 'products' && $id !== null):
                $productController = new ProductController();
                $productController->getProducts($id);
                break;

            case ($resource === 'products' && $id === null):
                $productController = new ProductController();
                $productController->getAllProducts();
                break;

            case ($resource === 'users' && $id !== null):
                $userController = new UserController();
                $userController->getUser($id);
                break;

            case ($resource === 'users' && $id === null):
                $userController = new UserController();
                $userController->getAllUsers();
                break;

            case ($resource === 'logs'):
                $logController = new LogController();
                $logController->getAllLogs();
                break;

            default:
                http_response_code(404);
                echo json_encode(["Mensagem" => "Endpoint não encontrado"]);
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

            case '/users':
                $data = json_decode(file_get_contents('php://input'), true);
                $userController = new UserController();
                $userController->postUser($data);
            break;

            case '/login':
                $data = [
                'userName' => $_POST['userName'],
                'email' => $_POST['email'],
                'userPassword' => $_POST['userPassword']
                ];
                $userController = new UserController();
                $userController->postLogin($data);
            break;

            default:
                http_response_code(404);
                echo "Endpoint não encontrado.";
            break;
        }
    break;

    case 'PUT':
        $id = null;

        switch(true){
            case preg_match('/\/products\/(\+d)/', $uri, $match):
                $id = $match[1];
                switch($id){
                    case ($id !== null):
                        $data = json_decode(file_get_contents('php://input'), true);
                        $productController = new ProductController();
                        $productController->putProducts($id, $data, $method);
                    break;
                }
            break;

            case preg_match('/\/users\/(\d+)/', $uri, $match):
                $id = $match[1];
                switch($id){
                    case ($id !== null):
                        $data = json_decode(file_get_contents('php://input'), true);
                        $userController = new UserController();
                        $userController->putUser($id, $data);
                    break;
                }
            break;
        }

    break;
    
    case 'DELETE':
        $id = null;
        
        switch(true){
            case preg_match('/\/products\/(\+d)/', $uri, $match):
                $id = $match[1];
                switch($id){
                    case ($id !== null):
                        $data = json_decode(file_get_contents('php://input'), true);
                        $productController = new ProductController();
                        $productController->deleteProducts($id, $method);
                    break;
                }
            break;

            case preg_match('/\/users\/(\d+)/', $uri, $match):
                $id = $match[1];
                switch($id){
                    case ($id !== null):
                        $userController = new UserController();
                        $userController->deleteUser($id);
                    break;
                }
            break;
        }
    break;    
}
