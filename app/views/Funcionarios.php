<?php
require_once '/home/wagner/Documentos/miniframe/app/controller/UserController.php';

$funcionarioController = new UserController();

// Adicionar funcionário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_funcionario'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cargo = $_POST['cargo'];
    $salario = $_POST['salario'];
    $mensagem = $funcionarioController->addFuncionario($nome, $email, $cargo, $salario);
}

// Listar funcionários
$funcionarios = $funcionarioController->getFuncionarios2();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- FontAwesome -->
    <style>
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            color: white;
        }
        .sidebar a {
            padding: 8px 16px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Menu Lateral -->
    <div class="sidebar">
        <h3 class="text-center text-white">Menu</h3>
        <a href="#">Dashboard</a>
        <a href="#">Gestão de Funcionários</a>
        <a href="#">Relatórios</a>
        <a href="#">Configurações</a>
    </div>

    <div class="main-content">
        <div class="container mt-4">
            <h2>Gestão de Funcionários</h2>

            <!-- Mensagem de Sucesso ou Erro -->
            <?php if (isset($mensagem)): ?>
                <div class="alert alert-info"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <!-- Botão para abrir o Modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adicionarFuncionarioModal">
                <i class="fas fa-plus-circle"></i> Cadastrar Funcionário
            </button>

            <hr>

            <!-- Listar Funcionários -->
            <h3>Funcionários Cadastrados</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Cargo</th>
                        <th>Salário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($funcionarios as $funcionario) {
                        echo "<tr>
                                <td>{$funcionario['id']}</td>
                                <td>{$funcionario['nome']}</td>
                                <td>{$funcionario['email']}</td>
                                <td>{$funcionario['cargo']}</td>
                                <td>{$funcionario['salario']}</td>
                                <td>{$funcionario['status']}</td>
                                <td>
                                    <a href='editar_funcionario.php?id={$funcionario['id']}' class='btn btn-warning btn-sm'>Editar</a>
                                    <a href='excluir_funcionario.php?id={$funcionario['id']}' class='btn btn-danger btn-sm'>Excluir</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Cadastrar Funcionário -->
    <div class="modal fade" id="adicionarFuncionarioModal" tabindex="-1" aria-labelledby="adicionarFuncionarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarFuncionarioModalLabel">Cadastrar Novo Funcionário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="cargo" class="form-label">Cargo</label>
                            <input type="text" class="form-control" id="cargo" name="cargo" required>
                        </div>
                        <div class="mb-3">
                            <label for="salario" class="form-label">Salário</label>
                            <input type="number" class="form-control" id="salario" name="salario" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="adicionar_funcionario">
                            <i class="fas fa-plus-circle"></i> Cadastrar Funcionário
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
