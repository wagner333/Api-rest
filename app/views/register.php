<?php
// Inclua o arquivo do controlador (UserController.php)
require_once '../app/controller/UserController.php';

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Chama o método de registro
    $mensagem = $userController->registerUser($nome, $email, $senha);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Cadastro</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Exibe mensagem de erro ou sucesso -->
                <?php if (isset($mensagem)): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $mensagem; ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário de Cadastro -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                </form>
                <p class="text-center mt-3">Já tem uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
