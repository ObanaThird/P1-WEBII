<?php

namespace Obana\App\Controller;

use Obana\App\Repository\LogsRepository;
use Obana\App\Model\LogModel;
use Obana\App\Model\Product;
use PDOException;

Class LogController {
    public function __construct() {
        header('Content-Type: application/json');
    }

    public function getAllLogs(){
        try{
            $productRepository = new LogsRepository();
            $logs = $productRepository->selectLogs();
            if ($logs) {
                http_response_code(200);
                echo json_encode($logs);
            } else {
                http_response_code(404);
                echo json_encode(['Mensagem' => 'Nenhum log encontrado.']);
            }
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['Erro' => $e->getMessage()]);
        }
    
    }

    public function postLogs(Product $product, $method) {
        try{
            $logModel = new LogModel();
            $logModel->setIdProduct($product);
            $logModel->setOperationType($method);

            $logRepository = new LogsRepository();
            $log = $logRepository->insertLogs($logModel);
            if($log){
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['Erro' => $e->getMessage()]);
        }
    }
}