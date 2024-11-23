<?php
session_start();
require_once '../app/controller/UserController.php';

// Instancia o controlador de usuário
$userController = new UserController();

// Chama a função para obter todos os fornecedores
$fornecedores = $userController->getAllFornecedores();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica a ação solicitada
    $acao = $_POST['acao'];

    if ($acao === 'cadastrar') {
        // Obtém os dados do formulário de cadastro
        $nome = $_POST['nome'];
        $cnpjCpf = $_POST['cnpjCpf'];
        $tipoProdutoServico = $_POST['tipoProdutoServico'];
        $whatsapp = $_POST['whatsapp'];
        $email = $_POST['email'];
        $site = $_POST['site'];
        $endereco = $_POST['endereco'];
        $status = $_POST['status'];

        // Chama o método de cadastro de fornecedor
        $mensagem = $userController->addFornecedor($nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status);
        echo "<script>alert('$mensagem');</script>";
    } elseif ($acao === 'atualizar') {
        // Obtém os dados do fornecedor a ser atualizado
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $cnpjCpf = $_POST['cnpjCpf'];
        $tipoProdutoServico = $_POST['tipoProdutoServico'];
        $whatsapp = $_POST['whatsapp'];
        $email = $_POST['email'];
        $site = $_POST['site'];
        $endereco = $_POST['endereco'];
        $status = $_POST['status'];

        // Chama o método de atualização de fornecedor
        $mensagem = $userController->updateFornecedor($id, $nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status);
        echo "<script>alert('$mensagem');</script>";
    } elseif ($acao === 'deletar') {
        // Obtém o ID do fornecedor a ser deletado
        $id = $_POST['id'];

        // Chama o método de exclusão de fornecedor
        $mensagem = $userController->deleteFornecedor($id);
        echo "<script>alert('$mensagem');</script>";
    }

    // Recarrega a lista de fornecedores após a operação
    $fornecedores = $userController->getAllFornecedores();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Fornecedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizáveis do seu site */
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
            margin-bottom: 20px;
            width: 100%;
        }

        .card-header-custom {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .form-control-sm {
            width: 100%;
            max-width: 300px;
            margin-bottom: 10px;
        }

        .icon-link {
            text-decoration: none;
            font-size: 20px;
            margin-right: 10px;
        }

        .icon-link:hover {
            color: #007bff;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-container input {
            width: 100%;
            max-width: 300px;
        }
    </style>
</head>

<body>
    <!-- Menu Lateral -->
    <div class="sidebar">

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

    <!-- Conteúdo Principal -->
    <div class="content">
        <div class="container mt-5">
            <div class="card card-custom">
                <div class="card-header card-header-custom">
                    <h4>Cadastrar Fornecedor</h4>
                </div>
                <div class="card-body">
                    <!-- Formulário de Cadastro -->
                    <form action="" method="POST">
                        <input type="hidden" name="acao" value="cadastrar">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" id="nome" name="nome" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cnpj" class="form-label">CNPJ/CPF:</label>
                                <input type="text" id="cnpjCpf" name="cnpjCpf" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" id="email" name="email" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="endereco" class="form-label">Endereço:</label>
                                <input type="text" id="endereco" name="endereco" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label for="whatsapp" class="form-label">WhatsApp (Opcional):</label>
                                <input type="text" id="whatsapp" name="whatsapp" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="site" class="form-label">Site (Opcional):</label>
                                <input type="text" id="site" name="site" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label for="tipoProdutoServico" class="form-label">Tipo de Produto/Serviço:</label>
                                <input type="text" id="tipoProdutoServico" name="tipoProdutoServico" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status:</label>
                            <select id="status" name="status" class="form-control form-control-sm" required>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cadastrar Fornecedor</button>
                    </form>
                </div>
            </div>

            <!-- Cards de Fornecedores -->
            <div class="mt-5">
                <h4>Fornecedores Cadastrados</h4>
                <div class="search-container">
                    <input type="text" id="search" class="form-control form-control-sm mb-3" placeholder="Buscar fornecedor..." onkeyup="searchFornecedor()">
                </div>

                <div class="card-wrapper">
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <div class="card card-custom">
                            <div class="card-header card-header-custom">
                                <h5>
                                    <?= htmlspecialchars($fornecedor['nome']) ?>
                                </h5>
                            </div>
                            <div class="card-body card-body-custom">
                                <div>
                                    <strong>CNPJ/CPF:</strong>
                                    <span><?= htmlspecialchars($fornecedor['cnpj_cpf'] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <strong>E-mail:</strong>
                                    <span><?= htmlspecialchars($fornecedor['email'] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <strong>Telefone:</strong>
                                    <span><?= htmlspecialchars($fornecedor['telefone'] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <strong>Endereço:</strong>
                                    <span><?= htmlspecialchars($fornecedor['endereco'] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <strong>WhatsApp:</strong>
                                    <span><?= htmlspecialchars($fornecedor['whatsapp'] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <strong>Site:</strong>
                                    <span><?= htmlspecialchars($fornecedor['site'] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <strong>Tipo de Produto/Serviço:</strong>
                                    <span><?= htmlspecialchars($fornecedor['tipo_produto_servico'] ?? 'N/A') ?></span>
                                </div>
                                <div>
                                    <strong>Status:</strong>
                                    <span><?= htmlspecialchars($fornecedor['status'] ?? 'N/A') ?></span>
                                </div>
                                <div class="btns">
                                    <form action="" method="post" class="d-flex mb-3">
                                        <div class="btn-delete">
                                            <input type="hidden" name="acao" value="deletar">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($fornecedor['id']) ?>">

                                            <button type="submit" class="btn btn-danger">Deletar Fornecedor</button>
                                        </div>

                                        <div class="update">
                                            <input type="hidden" name="acao" value="atualizar">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($fornecedor['id']) ?>">

                                            <button type="submit" class="btn btn-warning">Atualizar Fornecedor</button>
                                        </div>
                                    </form>
                                </div>
                                <div>
                                    <a href="https://wa.me/<?= htmlspecialchars($fornecedor['whatsapp'] ?? '') ?>" target="_blank" class="icon-link">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                    <?php if (!empty($fornecedor['site'])): ?>
                                        <a href="https://<?= htmlspecialchars($fornecedor['site']) ?>" target="_blank" class="icon-link">
                                            <i class="fas fa-globe"></i> Site
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>

    <script>
        function searchFornecedor() {
            let input = document.getElementById("search");
            let filter = input.value.toLowerCase();
            let cards = document.querySelectorAll(".card-custom");
            cards.forEach(function(card) {
                let nome = card.querySelector(".card-title").textContent.toLowerCase();
                if (nome.includes(filter)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>
</body>

</html>