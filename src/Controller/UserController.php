<?php

namespace Obana\App\Controller;

use Obana\App\Functions\ValidateFields;
use Obana\App\Model\UserModel;
use Obana\App\Repository\UserRepository;
use Firebase\JWT\JWT;

class UserController{
    private $secretKey;
    private $expiration;

    public function __construct() {
        header('Content-Type: application/json');

        $this->secretKey = $_ENV['JWT_SECRET_KEY'];
        $this->expiration = $_ENV['JWT_EXPIRATION'];

    }

    public function getAllUsers(){
        $userRepository = new UserRepository();
        $result = $userRepository->selectAllUsers();

        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['Mensagem' => 'Nenhum usuário cadastrado.']);
        }
    }

    public function getUser($id) {
        $userModel = new UserModel();
        $userModel->setId($id);
    
        $userRepository = new UserRepository();
        $result = $userRepository->selectUsers($userModel);
            
        if($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['Mensagem' => 'ID não encontrado.']);
        }

    }

    public function postUser($data){
        $missingFields = new ValidateFields();
        $requiredFields = ['userName', 'email', 'userPassword'];
        $resultFields = $missingFields->validateFields($data, $requiredFields);

        if (!empty($resultFields)) {
            http_response_code(400);
            echo json_encode([
                'Mensagem' => 'Todos os campos são obrigatórios.',
                'Campos não preenchidos' => $resultFields
            ]);
            return;
        }

        $name = $data['userName'];
        $email = $data['email'];
        $password = $data['userPassword'];


        $userModel = new UserModel();
        $userModel->setName($name);
        $userModel->setEmail($email);
        $userModel->setPassword($password);

        $userRepository = new UserRepository();
        $result = $userRepository->insertUser($userModel);

        if($result){
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(409);
            echo json_encode(['Error' => 'Email já cadastrado.',  'status' => $result]);
        }

    }

    public function postLogin($data){
        $missingFields = new ValidateFields();
        $requiredFields = ['email', 'userPassword'];
        $resultFields = $missingFields->validateFields($data, $requiredFields);

        if (!empty($resultFields)) {
            http_response_code(400);
            echo json_encode([
                'Mensagem' => 'Todos os campos são obrigatórios.',
                'Campos não preenchidos' => $resultFields
            ]);
            return;
        }

        $email = $data['email'];
        $password = $data['userPassword'];

        $userModel = new UserModel();
        $userModel->setEmail($email);
        $userModel->setPassword($password);

        $userRepository = new UserRepository();
        $result = $userRepository->userLogin($userModel);
        if($result){
            $payload = [
                'iss' => 'http://localhost:8000',
                'aud' => 'http://localhost:8000',
                'iat' => time(),
                'exp' => time() + $this->expiration,
                'data' => [
                    'userId' => $result['id'],
                    'email' => $email
                ]
            ];
    
            $jwt = JWT::encode($payload, $this->secretKey, 'HS256');

            http_response_code(200);
            echo json_encode(['Mensagem' => 'Conectado com sucesso.', 'Usuário' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['Mensagem' => 'Email e/ou Senha incorreto(os).',  'status' => $result]);
        }

    }

    public function putUser($id, $data){
        $missingFields = new ValidateFields();
        $requiredFields = ['userName', 'email', 'userPassword'];
        $resultFields = $missingFields->validateFields($data, $requiredFields);

        if (!empty($resultFields)) {
            http_response_code(400);
            echo json_encode([
                'Mensagem' => 'Todos os campos são obrigatórios.',
                'Campos não preenchidos' => $resultFields
            ]);
            return;
        }

        $name = $data['userName'];
        $email = $data['email'];
        $password = $data['userPassword'];

        $userModel = new UserModel();
        $userModel->setId($id);
        $userModel->setName($name);
        $userModel->setEmail($email);
        $userModel->setPassword($password);

        $userRepository = new UserRepository();
        $result = $userRepository->updateUser($userModel);
        if($result){
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode($result);
        }

    }

    public function deleteUser($id){
        $userModel = new UserModel();
        $userModel->setId($id);

        $userRepository = new UserRepository();
        $result = $userRepository->eraseUser($userModel);

        if($result){
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode($result);
        }
    }
    
}