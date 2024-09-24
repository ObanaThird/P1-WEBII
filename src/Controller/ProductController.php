<?php

namespace Obana\App\Controller;

use Obana\App\Model\Product;
use Obana\App\Repository\ProductRepository;

Class ProductController {

    public function getAllProducts(){
        $productRepository = new ProductRepository();
        $products = $productRepository->selectAllProducts();

        header('Content-Type: application/json');
        if ($products) {
            echo json_encode($products); // Retorna todos os produtos em formato JSON
        } else {
            echo json_encode(['message' => 'Nenhum produto encontrado.']);
        }

    }

    public function getProducts($id){
        $product = new Product();
        $product->setId($id);

        $productRepository = new ProductRepository();
        $productRepository->selectProducts($product);

    }

    public function postProducts($data) {
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $storage = $data['storage'];

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setStorage($storage);

        $productRepository = new ProductRepository();
        $productRepository->insertProducts($product);
    }


}