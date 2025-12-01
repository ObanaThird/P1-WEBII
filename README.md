API de Gerenciamento de Produtos
================================

Uma API RESTful desenvolvida em PHP para gerenciar produtos e usuários, realizando operações CRUD (Create, Read, Update, Delete). A aplicação segue o padrão de arquitetura MVC (Model-View-Controller) e registra todas as operações em um sistema de logs.

Funcionalidades
--------------

- **Gerenciamento de Produtos**: CRUD completo para produtos.
- **Gerenciamento de Usuários**: CRUD completo para usuários.
- **Autenticação**: Endpoint de login simples.
- **Logging**: Registro automático de todas as operações de escrita (POST, PUT, DELETE) na base de dados.

Tecnologias Utilizadas
----------------------

- PHP 8+
- Composer (para gerenciamento de dependências)
- phpdotenv (para gerenciamento de variáveis de ambiente)
- MySQL (ou outro banco de dados relacional)

Instalação e Execução
---------------------

Siga os passos abaixo para configurar e executar o projeto em seu ambiente local.

### Pré-requisitos

- PHP 8 ou superior
- Composer
- Um servidor de banco de dados (ex: MySQL, MariaDB)
- Postman ou similar para testar os endpoints

### 1. Clone o Repositório
```bash
git clone https://github.com/seu-usuario/seu-repositorio.git
cd seu-repositorio
```

### 2. Instale as Dependências
Execute o Composer para instalar as bibliotecas necessárias.
```bash
composer install
```

### 3. Configure as Variáveis de Ambiente
Crie um arquivo `.env` na pasta `src/` com as seguintes informações, ajustando-as para o seu ambiente:
```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=seu_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 4. Configure o Banco de Dados
Crie o banco de dados e as tabelas necessárias (`products`, `users`, `logs`).

### 5. Inicie o Servidor
Use o servidor embutido do PHP para iniciar a aplicação. Navegue até a pasta `src` e execute:
```bash
cd src
php -S 127.0.0.1:7000
```
A API estará disponível em `http://127.0.0.1:7000`.

Documentação da API
-------------------

A documentação completa dos endpoints, incluindo exemplos de requisições e respostas, está disponível no Postman.

Link: https://documenter.getpostman.com/view/37986149/2sAXqzWyKi