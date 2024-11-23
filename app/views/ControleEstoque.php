<?php
require_once '../app/controller/UserController.php';

// Instancia o controlador de usuário
$userController = new UserController();

// Verifica as ações solicitadas
if (isset($_POST['reduzir_estoque'])) {
    $idProduto = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $mensagem = $userController->reduzirEstoque($idProduto, $quantidade);
}

if (isset($_POST['adicionar_estoque'])) {
    $idProduto = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $mensagem = $userController->adicionarEstoque($idProduto, $quantidade);
}

// Obtém todos os produtos
$produtos = $userController->getAllProducts();

// Obtém os produtos abaixo do estoque mínimo
$produtosAbaixoEstoqueMinimo = $userController->verificarEstoqueMinimo();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-4">
        <h1 class="text-center">Controle de Estoque</h1>
        
        <!-- Mensagens de erro ou sucesso -->
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-info">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <!-- Tabela de Produtos -->
        <h2>Todos os Produtos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Estoque Mínimo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($produtos) && count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo $produto['id']; ?></td>
                            <td><?php echo $produto['nome']; ?></td>
                            <td><?php echo $produto['quantidade']; ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo isset($produto['estoque_minimo']) ? $produto['estoque_minimo'] : 'N/A'; ?></td>
                            <td>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalReduzirEstoque<?php echo $produto['id']; ?>">Reduzir Estoque</button>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdicionarEstoque<?php echo $produto['id']; ?>">Adicionar Estoque</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">Nenhum produto encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Produtos abaixo do estoque mínimo -->
        <h2>Produtos com Estoque Abaixo do Mínimo</h2>
        <table class="table table-danger">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Estoque Mínimo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($produtosAbaixoEstoqueMinimo) && count($produtosAbaixoEstoqueMinimo) > 0): ?>
                    <?php foreach ($produtosAbaixoEstoqueMinimo as $produto): ?>
                        <tr>
                            <td><?php echo $produto['id']; ?></td>
                            <td><?php echo $produto['nome']; ?></td>
                            <td><?php echo $produto['quantidade']; ?></td>
                            <td><?php echo isset($produto['estoque_minimo']) ? $produto['estoque_minimo'] : 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Nenhum produto com estoque abaixo do mínimo.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Reduzir Estoque -->
    <?php foreach ($produtos as $produto): ?>
    <div class="modal fade" id="modalReduzirEstoque<?php echo $produto['id']; ?>" tabindex="-1" aria-labelledby="modalReduzirEstoqueLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalReduzirEstoqueLabel">Reduzir Estoque de <?php echo $produto['nome']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade a ser reduzida:</label>
                            <input type="number" class="form-control" name="quantidade" required>
                        </div>
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                        <button type="submit" name="reduzir_estoque" class="btn btn-warning">Reduzir Estoque</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Modal para Adicionar Estoque -->
    <?php foreach ($produtos as $produto): ?>
    <div class="modal fade" id="modalAdicionarEstoque<?php echo $produto['id']; ?>" tabindex="-1" aria-labelledby="modalAdicionarEstoqueLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdicionarEstoqueLabel">Adicionar Estoque de <?php echo $produto['nome']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="quantidade" class="form-label">Quantidade a ser adicionada:</label>
                            <input type="number" class="form-control" name="quantidade" required>
                        </div>
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                        <button type="submit" name="adicionar_estoque" class="btn btn-success">Adicionar Estoque</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
 
</body>
</html>
