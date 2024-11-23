<?php
session_start();
require_once '../app/controller/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Instancia o controlador de usuário
    $userController = new UserController();

    // Chama o método de cadastro de cliente
    $mensagem = $userController->registerCustomer($nome, $cpf, $email, $telefone, $endereco);

    // Exibe a mensagem retornada
    echo "<script>alert('$mensagem');</script>";

    // Redireciona para outra página ou limpa o formulário após cadastro
    // header("Location: cliente_cadastrado.php");
    // exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <!-- Link do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos adicionais */
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
            color: #fff;
        }
        .sidebar a {
            color: #fff;
            padding: 10px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card-custom {
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header-custom {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Menu Lateral -->
    <div class="sidebar">
        <h2 class="text-center text-white">Menu</h2>
        <a href="/dashboard">Dashboard</a>
        <a href="">Cadastrar Cliente</a>
        <a href="/cliente_cadastrado">Clientes Cadastrados</a>
        <a href="#">Pedidos</a>
        <a href="#">Sair</a>
    </div>

    <!-- Conteúdo Principal -->
    <div class="content">
        <div class="container mt-5">
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    <h4>Cadastrar Cliente</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" id="nome" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF:</label>
                            <input type="text" id="cpf" name="cpf" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone:</label>
                            <input type="text" id="telefone" name="telefone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço:</label>
                            <input type="text" id="endereco" name="endereco" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
