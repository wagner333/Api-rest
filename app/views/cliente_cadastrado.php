<?php
session_start();
require_once '../app/controller/UserController.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Instância do controlador de usuários
$userController = new UserController();
$clientes = $userController->getAllCustomers(); // Ajustei para garantir que o método correto seja chamado
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes Cadastrados</title>
    <!-- Link do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .client-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Menu Lateral -->
    <div class="sidebar">
        <h2 class="text-center text-white">Menu</h2>
        <a href="index.php">Dashboard</a>
        <a href="/CadastrarClientes">Cadastrar Cliente</a>
        <a href="cliente_cadastrado.php">Clientes Cadastrados</a>
        <a href="pedidos.php">Pedidos</a>
        <a href="logout.php">Sair</a>
    </div>

    <!-- Conteúdo Principal -->
    <div class="content">
        <div class="container mt-5">
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    <h4>Clientes Cadastrados</h4>
                </div>
                <div class="card-body">
                    <?php if (count($clientes) > 0): ?>
                        <div class="row">
                            <?php foreach ($clientes as $cliente): ?>
                                <div class="col-md-4">
                                    <div class="card client-card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($cliente['nome']); ?></h5>
                                            <p class="card-text"><strong>CPF:</strong> <?= htmlspecialchars($cliente['cpf']); ?></p>
                                            <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($cliente['email']); ?></p>
                                            <p class="card-text"><strong>Telefone:</strong> <?= htmlspecialchars($cliente['telefone']); ?></p>
                                            <p class="card-text"><strong>Endereço:</strong> <?= htmlspecialchars($cliente['endereco']); ?></p>
                                            <a href="editar_cliente.php?id=<?= $cliente['id']; ?>" class="btn btn-warning">Editar</a>
                                            <a href="deletar_cliente.php?id=<?= $cliente['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center">Nenhum cliente cadastrado ainda.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
