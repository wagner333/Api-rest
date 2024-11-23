<?php
session_start();
require_once '../app/controller/UserController.php';

$userController = new UserController();
$relatorio = $userController->getRelatorio();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            max-width: 1200px;
            margin-top: 50px;
        }
        .card {
            margin-bottom: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .chart-container {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1 class="display-4 text-primary">Relatório Financeiro</h1>
            <p class="lead">Aqui você pode visualizar o desempenho financeiro da sua empresa.</p>
        </div>

        <!-- Cards com dados financeiros -->
        <div class="row text-center">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-success">Total de Vendas</h5>
                        <p class="card-text h4">R$ <?= number_format($relatorio['totalVendas'], 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Total de Despesas</h5>
                        <p class="card-text h4">R$ <?= number_format($relatorio['totalDespesas'], 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Total de Clientes</h5>
                        <p class="card-text h4"><?= $relatorio['totalClientes'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Total de Produtos</h5>
                        <p class="card-text h4"><?= $relatorio['totalProdutos'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="chart-container mt-5">
            <canvas id="graficoRelatorio"></canvas>
        </div>
    </div>

    <script>
        // Dados para o gráfico
        const ctx = document.getElementById('graficoRelatorio').getContext('2d');
        const graficoRelatorio = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: ['Vendas', 'Despesas', 'Clientes', 'Produtos'], // Rótulos do gráfico
                datasets: [{
                    label: 'Relatório Financeiro',
                    data: [
                        <?= $relatorio['totalVendas'] ?>,
                        <?= $relatorio['totalDespesas'] ?>,
                        <?= $relatorio['totalClientes'] ?>,
                        <?= $relatorio['totalProdutos'] ?>
                    ], // Valores que vão ser exibidos no gráfico
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
