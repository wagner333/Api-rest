<?php
// Supondo que o banco de dados já esteja configurado e a conexão esteja disponível
// Inicie a sessão para utilizar as variáveis de sessão (carrinho)
session_start();

// Simula o ID do usuário autenticado (isso deve vir do sistema de autenticação)
$usuarioId = 1; // Exemplo de ID do usuário autenticado

// Inclua o modelo e o controlador

require_once '/home/wagner/Documentos/miniframe/app/controller/UserController.php';

// Criando instâncias do modelo e do controlador
$userModel = new userController();
$carrinhoController = new userController();

// Verifica se foi enviado o formulário para adicionar ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $produtoId = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    // Chama o método para adicionar ao carrinho
    $message = $carrinhoController->addToCart($usuarioId, $produtoId, $quantidade);
    echo "<p>$message</p>";
}

// Exibe os produtos (supondo que você já tenha a lógica para obter os produtos)
$produtos = $userModel->getAllProducts(); // Método fictício para listar todos os produtos
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <!-- Link para o Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></head>
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product-table th {
            background-color: #007bff;
            color: white;
        }

        .product-table td, .product-table th {
            text-align: center;
            vertical-align: middle;
        }

        .product-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 8px 15px;
            border: none;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .cart-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .cart-list {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Produtos</h1>

    <!-- Tabela de produtos -->
    <table class="table table-striped product-table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Imagem</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?php echo $produto['nome']; ?></td>
                    <td><img src="https://via.placeholder.com/150" class="product-image" alt="Produto"></td>
                    <td>R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                            <input type="number" name="quantidade" min="1" value="1" class="form-control" style="width: 70px;" required>
                        </form>
                    </td>
                    <td>
                        <button type="submit" name="add_to_cart" class="btn btn-custom">Adicionar ao Carrinho</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Carrinho de Compras -->
    <h2 class="mt-5">Carrinho de Compras</h2>
    <?php
    $cartItems = $carrinhoController->viewCart($usuarioId);
    if (empty($cartItems)) {
        echo "<p>Carrinho vazio!</p>";
    } else {
        echo "<div class='cart-list'>";
        foreach ($cartItems as $item) {
            echo "<div class='cart-item'>
                    <strong>{$item['nome']}</strong><br>
                    Quantidade: {$item['quantidade']}<br>
                    Preço: R$ {$item['preco_venda']}
                  </div>";
        }
        echo "</div>";
    }
    ?>
</div>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>