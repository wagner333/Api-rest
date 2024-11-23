<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    $totalDespesas = $userController->getTotalDespesas($_SESSION['usuario_id']);
    header('Location: /login');
    exit();
}

// Importa o controlador de usuário
require "/home/wagner/Documentos/miniframe/app/controller/UserController.php";

// Instancia o controlador
$userController = new UserController();

// Obtém os totais de clientes, produtos, vendas, despesas e lucro
$totalClientes = $userController->getTotalClientes();
$totalProdutos = $userController->getTotalProdutos();
$totalVendas = $userController->getTotalVendas();
$totalFuncionario = count($userController->getFuncionarios());
$funcionarios = $userController->getFuncionarios2();
$produtos = $userController->getAllProducts();
$clientes = $userController->getAllFornecedores();
$relatorio = $userController->getRelatorio();
$totalDespesas = $userController->getTotalDespesas($_SESSION['usuario_id']); // Método para obter as despesas
$totalLucro = $totalVendas - $totalDespesas; // Lucro é a diferença entre vendas e despesas

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mercadinho</title>
    <!-- CDN do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Incluindo o Chart.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
       .charts-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .chart-box {
            flex: 1;
            min-width: 300px;
            max-width: 450px;
        }
        canvas {
            max-width: 100%;
            height: 300px;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #007bff;
            border-radius: 5px;
        }

        .sidebar h4 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        /* Estilo para os cards */
        .info-box {
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .info-box i {
            font-size: 2rem;
        }

        .info-box-content {
            flex-grow: 1;
            padding-left: 15px;
        }

        .info-box h5 {
            font-size: 1.25rem;
        }

        .btn-custom {
            font-size: 16px;
            margin-top: 10px;
        }

        /* Estilo para o título */
        h1 {
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        /* Estilo para a navbar */
        .navbar {
            background-color: #343a40;
            /* Cor igual ao menu lateral */
        }

        .navbar .navbar-nav .nav-link {
            color: white;
        }

        .navbar .navbar-nav .nav-link:hover {
            color: #007bff;
        }

        /* Estilo para responsividade */
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
            }

            .sidebar {
                width: 100%;
                position: relative;
            }

            .info-box {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar superior -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-circle me-2"></i> Mercadinho
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/Perfil">Perfil</a></li>
                            <li><a class="dropdown-item" href="/logout">Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <div class="sidebar">

        <a href="/CadastrarProdutos"><i class="fas fa-box"></i> Cadastro de Produtos</a>
        <a href="/ControleEstoque"><i class="fas fa-cogs"></i> Controle de Estoque</a>
        <a href="/CadastroFornecedores"><i class="fas fa-truck"></i> Cadastro de Fornecedores</a>
        <a href="/CadastrarClientes"><i class="fas fa-users"></i> Cadastro de Clientes</a>
        <a href="/VendasCaixa"><i class="fas fa-cash-register"></i> Vendas e Caixa</a>
        <a href="/Funcionarios"><i class="fas fa-user-tie"></i> Gestão de Funcionários</a>
        <a href=""><i class="fas fa-wallet"></i> Controle de Despesas</a>
        <a href="/ControleCompras"><i class="fas fa-cart-plus"></i> Controle de Compras</a>
        <a href="/relatorios"><i class="fas fa-chart-line"></i> Relatórios</a>
    </div>

    <!-- Área de Conteúdo -->
    <div class="content" id="sidebar">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-primary">
                        <i class="fas fa-users"></i>
                        <div class="info-box-content">
                            <h5>Total de Clientes</h5>
                            <p><?php echo $totalClientes; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <i class="fas fa-box"></i>
                        <div class="info-box-content">
                            <h5>Total de Produtos</h5>
                            <p><?php echo $totalProdutos; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <i class="fas fa-chart-line"></i>
                        <div class="info-box-content">
                            <h5>Total de Vendas</h5>
                            <p><?php echo number_format($totalVendas, 2, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <i class="fas fa-money-bill-wave"></i>
                        <div class="info-box-content">
                            <h5>Total de Despesas</h5>
                            <p><?php echo $totalDespesas; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-info">
                        <i class="fas fa-user-tie"></i>
                        <div class="info-box-content">
                            <h5>Total de Funcionários</h5>
                            <p><?php echo $totalFuncionario; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-secondary">
                        <i class="fas fa-hand-holding-usd"></i>
                        <div class="info-box-content">
                            <h5>Lucro</h5>
                            <p><?php echo $totalLucro; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-4 flex-wrap">
                <!-- Box para Funcionários -->
                <div class="box p-3" style="width: 30%; border: 1px solid #ddd; border-radius: 8px; min-height: 250px;">
                    <div class="d-flex align-items-center mb-4 p-2">
                        <div class="icone-fun me-3">
                            <i class="fas fa-user" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5>Funcionários</h5>
                    </div>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Cargo</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($funcionarios) > 0): ?>
                                <?php foreach ($funcionarios as $funcionario): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($funcionario['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($funcionario['cargo']); ?></td>
                                        <td>
                                            <?php if ($funcionario['status']): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="alert alert-info" role="alert">
                                            Nenhum funcionário encontrado.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Box para Clientes -->
                <div class="box p-3" style="width: 30%; border: 1px solid #ddd; border-radius: 8px; min-height: 250px;">
                    <div class="d-flex align-items-center mb-4 p-2">
                        <div class="icone-fun me-3">
                            <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5>Fornecedores</h5>
                    </div>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">whatsapp</th>
                                <th scope="col">Cargo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (($clientes) > 0): ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                        <td><a href="https://wa.me/<?= htmlspecialchars($cliente['whatsapp'] ?? '') ?>" target="_blank" class="icon-link">
                                                WhatsApp
                                            </a></td>
                                        <td><?php echo htmlspecialchars($cliente['tipo_produto_servico']); ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="alert alert-info" role="alert">
                                            Nenhum cliente encontrado.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Box para Produtos -->
                <div class="box p-3" style="width: 30%; border: 1px solid #ddd; border-radius: 8px; min-height: 250px;">
                    <div class="d-flex align-items-center mb-4 p-2">
                        <div class="icone-fun me-3">
                            <i class="fas fa-box" style="font-size: 1.5rem;"></i>
                        </div>
                        <h5>Produtos</h5>
                    </div>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nome do Produto</th>
                                <th scope="col">quantidade</th>
                                <th scope="col">Preço</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($produtos) && is_array($produtos)): ?>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($produto['quantidade']); ?></td>
                                        <td> <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <div class="alert alert-info" role="alert">
                                            Nenhum produto encontrado.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="container mt-5">
        <div class="charts-container">
            <div class="chart-box">
                <canvas id="doughnutChart"></canvas>
            </div>
            <div class="chart-box">
                <canvas id="pieChart"></canvas>
            </div>
            
        </div>
    </div>

    <script>
        const relatorio = <?= json_encode($relatorio) ?>;

        const colors = ['#6c757d', '#adb5bd', '#495057', '#ced4da', '#343a40'];

        const config = {
            type: 'doughnut',
            data: {
                labels: ['Clientes', 'Produtos', 'Vendas', 'Despesas', 'Funcionários'],
                datasets: [{
                    label: 'Total Atual',
                    data: [
                        relatorio.totalClientes || 0,
                        relatorio.totalProdutos || 0,
                        relatorio.totalVendas || 0,
                        relatorio.totalDespesas || 0,
                        relatorio.totalFuncionarios || 0
                    ],
                    backgroundColor: colors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        };

        // Doughnut Chart
        new Chart(
            document.getElementById('doughnutChart'),
            config
        );

        // Pie Chart
        config.type = 'pie';
        new Chart(
            document.getElementById('pieChart'),
            config
        );

        // Line Chart
        config.type = 'line';
        new Chart(
            document.getElementById('lineChart'),
            config
        );
    </script>
                <!-- Scripts do Bootstrap -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>