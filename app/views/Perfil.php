<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se não estiver logado
    exit();
}

require_once '../app/controller/UserController.php';

$userController = new UserController();
$usuarioId = $_SESSION['usuario_id'];

// Recupera as informações do usuário
$usuario = $userController->getUserInfo($usuarioId);

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $_POST['novoNome'];
    $novoEmail = $_POST['novoEmail'];
    $novaSenha = !empty($_POST['novaSenha']) ? $_POST['novaSenha'] : null;

    $resultado = $userController->updateUser($novoEmail, $novoNome, $novaSenha);

    echo "<div class='alert alert-info mt-3'>$resultado</div>";

    // Atualiza as informações do usuário exibidas
    $usuario = $userController->getUserInfo($usuarioId);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .profile-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .profile-header .icon {
            font-size: 100px;
            margin-bottom: 15px;
        }
        .profile-section {
            margin-top: 30px;
        }
        .form-control {
            background-color: #f9f9f9;
        }
        .back-button {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="back-button">
        <a href="/dashboard" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar para a Dashboard
        </a>
    </div>
    
    <div class="profile-header">
        <i class="fas fa-user-circle icon"></i>
        <h2><?php echo htmlspecialchars($usuario['nome']); ?></h2>
        <p><?php echo htmlspecialchars($usuario['email']); ?></p>
    </div>

    <div class="profile-section">
        <h4 class="mt-4">Atualizar Informações</h4>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="novoNome" class="form-label">Novo Nome</label>
                <input type="text" class="form-control" id="novoNome" name="novoNome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="novoEmail" class="form-label">Novo Email</label>
                <input type="email" class="form-control" id="novoEmail" name="novoEmail" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="novaSenha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="novaSenha" name="novaSenha" placeholder="Nova senha (opcional)">
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
