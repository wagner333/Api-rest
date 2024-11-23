<?php
session_start();
include '/home/wagner/Documentos/miniframe/app/controller/UserController.php';

// Verificando se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    die('Você precisa estar logado para acessar esta página.');
}

$despesaController = new UserController();

// Adicionar despesa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_despesa'])) {
    $usuario_id = $_SESSION['usuario_id']; // Pegando o ID do usuário logado
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $categoria = $_POST['categoria'];
    $mensagem = $despesaController->addDespesa($usuario_id, $descricao, $valor, $categoria);
}

// Listar despesas
$despesas = $despesaController->getDespesas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Despesas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Gestão de Despesas</h2>

        <!-- Mensagem de Sucesso ou Erro -->
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-info"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <!-- Formulário para Adicionar Despesa -->
        <h3>Cadastrar Nova Despesa</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control" id="descricao" name="descricao" required>
            </div>
            <div class="mb-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="number" class="form-control" id="valor" name="valor" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <input type="text" class="form-control" id="categoria" name="categoria" required>
            </div>
            <button type="submit" class="btn btn-primary" name="adicionar_despesa">Cadastrar Despesa</button>
        </form>

        <hr>

        <!-- Listar Despesas -->
        <h3>Despesas Cadastradas</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Categoria</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($despesas as $despesa) {
                    echo "<tr>
                            <td>{$despesa['id']}</td>
                            <td>{$despesa['descricao']}</td>
                            <td>{$despesa['valor']}</td>
                            <td>{$despesa['categoria']}</td>
                            <td>{$despesa['data']}</td>
                            <td>
                                <a href='editar_despesa.php?id={$despesa['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                <a href='excluir_despesa.php?id={$despesa['id']}' class='btn btn-danger btn-sm'>Excluir</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
