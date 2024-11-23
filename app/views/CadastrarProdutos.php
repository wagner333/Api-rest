<?php
session_start();
require_once '/home/wagner/Documentos/miniframe/app/controller/UserController.php';

// Instancia o controlador de usuário
$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se é para adicionar, editar ou excluir
    if (isset($_POST['acao']) && $_POST['acao'] == 'add') {
        // Obtém os dados do formulário de cadastro
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $precoVenda = $_POST['preco_venda'];
        $quantidade = $_POST['quantidade'];
        $dataVencimento = $_POST['data_vencimento'];
        $descricao = $_POST['descricao'];

        // Chama o método de cadastro de produto
        $mensagem = $userController->addProduct($nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento);
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'edit') {
        // Edita o produto
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $precoVenda = $_POST['preco_venda'];
        $quantidade = $_POST['quantidade'];
        $dataVencimento = $_POST['data_vencimento'];
        $descricao = $_POST['descricao'];

        // Chama o método de edição de produto
        $mensagem = $userController->editProduct($id, $nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento);
    } elseif (isset($_POST['acao']) && $_POST['acao'] == 'delete') {
        // Exclui o produto
        $id = $_POST['id'];
        $mensagem = $userController->deleteProduct($id);
    }

    // Recupera todos os produtos após a ação
    $produtos = $userController->getAllProducts();
} else {
    // Caso não seja um POST, busca os produtos diretamente
    $produtos = $userController->getAllProducts();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        .form-section {
            max-width: 600px;
            margin: 0 auto;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h1>Produtos</h1>
        <a href="/CadastrarProdutos"><i class="fas fa-box"></i> Cadastro de Produtos</a>
        <a href="/ControleEstoque"><i class="fas fa-cogs"></i> Controle de Estoque</a>
        <a href="/CadastroFornecedores"><i class="fas fa-truck"></i> Cadastro de Fornecedores</a>
        <a href="/CadastrarClientes"><i class="fas fa-users"></i> Cadastro de Clientes</a>
        <a href="/VendasCaixa"><i class="fas fa-cash-register"></i> Vendas e Caixa</a>
        <a href="#sistemaPromocoes"><i class="fas fa-tags"></i> Sistema de Promoções</a>
        <a href="/Funcionarios"><i class="fas fa-user-tie"></i> Gestão de Funcionários</a>
        <a href=""><i class="fas fa-wallet"></i> Controle de Despesas</a>
        <a href="/ControleCompras"><i class="fas fa-cart-plus"></i> Controle de Compras</a>
        <a href="#relatorios"><i class="fas fa-chart-line"></i> Relatórios</a>
        <a href="#controlePrecificacao"><i class="fas fa-dollar-sign"></i> Controle de Precificação</a>
    </div>

    <div class="content">
        <div class="container mt-5">
            <div class="card card-custom">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Produtos Cadastrados</h4>
                    </div>
                    <div>
                        <!-- Botão de Adicionar Produto com Ícone em cima -->
                        <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fas fa-plus-circle"></i><br>Adicionar Produto
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($mensagem)): ?>
                        <div class="alert alert-info"><?= $mensagem ?></div>
                    <?php endif; ?>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Preço de Venda</th>
                                <th>Quantidade</th>
                                <th>Data de Vencimento</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($produtos) && is_array($produtos)): ?>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                                        <td>R$ <?= number_format($produto['preco_venda'], 2, ',', '.') ?></td>
                                        <td><?= $produto['quantidade'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($produto['data_vencimento'])) ?></td>
                                        <td><?= htmlspecialchars($produto['descricao']) ?></td>
                                        <td>
                                            <a href="?id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                                <input type="hidden" name="acao" value="delete">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Nenhum produto encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" class="form-section">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço</label>
                            <input type="number" step="0.01" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="preco_venda" class="form-label">Preço de Venda</label>
                            <input type="number" step="0.01" class="form-control" id="preco_venda" name="preco_venda" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição do Produto</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="acao" value="add" class="btn btn-custom">Cadastrar Produto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>