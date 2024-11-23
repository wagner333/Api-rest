<?php
// Incluir o arquivo de conexão e o controlador (UserController)
include '../app/controller/UserController.php';
$userController = new UserController();

// Lidar com a criação de uma nova venda
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['criar_venda'])) {
    $total = $_POST['total'];
    $status_pagamento = $_POST['status_pagamento'] ?? 'Pendente';
    $mensagem_venda = $userController->createVenda($total, $status_pagamento);
}

// Lidar com o registro de produto na venda
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_produto_venda'])) {
    $venda_id = $_POST['venda_id'];
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $preco_unitario = $_POST['preco_unitario'];
    $mensagem_produto = $userController->addProdutoVenda($venda_id, $produto_id, $quantidade, $preco_unitario);
}

// Lidar com o registro de transação no caixa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_transacao'])) {
    $tipo_transacao = $_POST['tipo_transacao'];
    $valor = $_POST['valor'];
    $descricao = $_POST['descricao'] ?? null;
    $mensagem_caixa = $userController->addTransacaoCaixa($tipo_transacao, $valor, $descricao);
}

// Lidar com a criação de um relatório
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['gerar_relatorio'])) {
    $tipo_relatorio = $_POST['tipo_relatorio'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $total = $_POST['total_relatorio'];
    $mensagem_relatorio = $userController->createRelatorio($tipo_relatorio, $data_inicio, $data_fim, $total);
}

// Lidar com a obtenção de relatórios
$relatorios = $userController->getRelatorios();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendas, Caixa e Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Vendas, Caixa e Relatórios</h2>

        <!-- Mensagens de sucesso ou erro -->
        <?php if (isset($mensagem_venda)): ?>
            <div class="alert alert-info"><?php echo $mensagem_venda; ?></div>
        <?php endif; ?>
        <?php if (isset($mensagem_produto)): ?>
            <div class="alert alert-info"><?php echo $mensagem_produto; ?></div>
        <?php endif; ?>
        <?php if (isset($mensagem_caixa)): ?>
            <div class="alert alert-info"><?php echo $mensagem_caixa; ?></div>
        <?php endif; ?>
        <?php if (isset($mensagem_relatorio)): ?>
            <div class="alert alert-info"><?php echo $mensagem_relatorio; ?></div>
        <?php endif; ?>

        <!-- Formulário para Criar Venda -->
        <h3>Criar Venda</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="total" class="form-label">Total da Venda (R$):</label>
                <input type="number" class="form-control" id="total" name="total" required>
            </div>
            <div class="mb-3">
                <label for="status_pagamento" class="form-label">Status do Pagamento:</label>
                <select class="form-control" id="status_pagamento" name="status_pagamento">
                    <option value="Pendente">Pendente</option>
                    <option value="Pago">Pago</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="criar_venda">Criar Venda</button>
        </form>

        <hr>

        <!-- Exibir as Vendas -->
        <h3>Vendas Registradas</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Total (R$)</th>
                    <th>Status Pagamento</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Buscar todas as vendas e exibir
                $vendas = $userController->getVendas();  // Método para pegar as vendas
                foreach ($vendas as $venda) {
                    echo "<tr>
                            <td>{$venda['id']}</td>
                            <td>{$venda['total']}</td>
                            <td>{$venda['status_pagamento']}</td>
                            <td>{$venda['data_venda']}</td>
                            <td>
                                <a href='#' class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#addProdutoModal' data-venda-id='{$venda['id']}'>Adicionar Produto</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Formulário para Adicionar Produto à Venda -->
        <div class="modal fade" id="addProdutoModal" tabindex="-1" aria-labelledby="addProdutoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProdutoModalLabel">Adicionar Produto à Venda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="produto_id" class="form-label">Produto ID</label>
                                <input type="text" class="form-control" id="produto_id" name="produto_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                            </div>
                            <div class="mb-3">
                                <label for="preco_unitario" class="form-label">Preço Unitário (R$)</label>
                                <input type="number" class="form-control" id="preco_unitario" name="preco_unitario" required>
                            </div>
                            <input type="hidden" id="venda_id" name="venda_id">
                            <button type="submit" class="btn btn-primary" name="adicionar_produto_venda">Adicionar Produto</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Formulário para Registrar Transação no Caixa -->
        <h3>Registrar Transação no Caixa</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="tipo_transacao" class="form-label">Tipo de Transação:</label>
                <select class="form-control" id="tipo_transacao" name="tipo_transacao" required>
                    <option value="Entrada">Entrada</option>
                    <option value="Saída">Saída</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="valor" class="form-label">Valor (R$):</label>
                <input type="number" class="form-control" id="valor" name="valor" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição (opcional):</label>
                <textarea class="form-control" id="descricao" name="descricao"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="registrar_transacao">Registrar Transação</button>
        </form>

        <hr>

        <!-- Exibir Transações no Caixa -->
        <h3>Transações no Caixa</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Valor (R$)</th>
                    <th>Descrição</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Exibir transações no caixa
                $transacoes = $userController->getTransacoesCaixa();
                foreach ($transacoes as $transacao) {
                    echo "<tr>
                            <td>{$transacao['id']}</td>
                            <td>{$transacao['tipo']}</td>
                            <td>{$transacao['valor']}</td>
                            <td>{$transacao['descricao']}</td>
                            <td>{$transacao['data']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <hr>

        <!-- Formulário para Criar Relatório -->
        <h3>Gerar Relatório</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="tipo_relatorio" class="form-label">Tipo de Relatório:</label>
                <select class="form-control" id="tipo_relatorio" name="tipo_relatorio">
                    <option value="Vendas">Vendas</option>
                    <option value="Caixa">Caixa</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data Início:</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
            </div>
            <div class="mb-3">
                <label for="data_fim" class="form-label">Data Fim:</label>
                <input type="date" class="form-control" id="data_fim" name="data_fim" required>
            </div>
            <div class="mb-3">
                <label for="total_relatorio" class="form-label">Total (R$):</label>
                <input type="number" class="form-control" id="total_relatorio" name="total_relatorio">
            </div>
            <button type="submit" class="btn btn-primary" name="gerar_relatorio">Gerar Relatório</button>
        </form>

        <hr>

        <!-- Exibir Relatórios -->
        <h3>Relatórios Gerados</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Data Início</th>
                    <th>Data Fim</th>
                    <th>Total (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Exibir relatórios
                if (is_array($relatorios)) {
                    foreach ($relatorios as $relatorio) {
                        echo "<tr>
                                <td>{$relatorio['id']}</td>
                                <td>{$relatorio['tipo']}</td>
                                <td>{$relatorio['data_inicio']}</td>
                                <td>{$relatorio['data_fim']}</td>
                                <td>{$relatorio['total']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>{$relatorios}</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
