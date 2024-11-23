<?php
// Inclua o arquivo do controlador (UserController.php)
require_once '../app/controller/UserController.php';

// Inicia a sessão
session_start();

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Chama o método de login
    $mensagem = $userController->login($email, $senha);

    // Se o login for bem-sucedido, armazena o ID do usuário na sessão
    if (isset($_SESSION['usuario_id'])) {
        // Redireciona para o dashboard
        header('Location: /dashboard');
        exit(); // Garante que o código seguinte não será executado após o redirecionamento
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Login</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Exibe mensagem de erro ou sucesso -->
                <?php if (isset($mensagem)): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $mensagem; ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário de Login -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
                <p class="text-center mt-3">Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
