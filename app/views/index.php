<?php
require "/home/wagner/Documentos/miniframe/app/controller/UserController.php";
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <!-- CDN do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo para a imagem centralizada */
        .center-img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
        }

        /* Centralização do conteúdo na página */
        .content {
            text-align: center;
            margin-top: 50px;
        }

        /* Estilo para os botões */
        .btn-custom {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1>Bem-vindo ao Sistema</h1>

            <!-- Foto centralizada -->
            <img src="caminho-da-sua-imagem.jpg" alt="Logo do Mercadinho" class="center-img">

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <!-- Se o usuário estiver logado, exibe as opções de perfil e logout -->
                <p>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</p>
                <a href="/dashboard" class="btn btn-primary btn-custom">Ir para o Dashboard</a>
                <a href="/logout" class="btn btn-danger btn-custom">Sair</a>
            <?php else: ?>
                <!-- Se o usuário não estiver logado, exibe as opções de login e cadastro -->
                <a href="/login" class="btn btn-success btn-custom">Entrar</a>
                <a href="/register" class="btn btn-info btn-custom">Cadastrar-se</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
