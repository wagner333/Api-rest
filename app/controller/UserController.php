<?php
require_once '../app/model/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function addDespesa($usuario_id, $descricao, $valor, $categoria) {
        // Chama o método do modelo para adicionar a despesa com o usuario_id
        if ($this->userModel->createDespesa($usuario_id, $descricao, $valor, $categoria)) {
            return "Despesa cadastrada com sucesso!";
        } else {
            return "Erro ao cadastrar despesa.";
        }
    }
    

    // Função para editar uma despesa
    public function editDespesa($id, $descricao, $valor, $categoria) {
        if ($this->userModel->editDespesa($id, $descricao, $valor, $categoria)) {
            return "Despesa atualizada com sucesso!";
        } else {
            return "Erro ao atualizar despesa.";
        }
    }

    // Função para excluir uma despesa
    public function deleteDespesa($id) {
        if ($this->userModel->deleteDespesa($id)) {
            return "Despesa excluída com sucesso!";
        } else {
            return "Erro ao excluir despesa.";
        }
    }

    // Função para listar as despesas
    public function getDespesas() {
        return $this->userModel->getDespesas();
    }
    public function addFuncionario($nome, $email, $cargo, $salario) {
        if ($this->userModel->createFuncionario($nome, $email, $cargo, $salario)) {
            return "Funcionário cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar funcionário.";
        }
    }

    // Função para editar um funcionário
    public function editFuncionario($id, $nome, $email, $cargo, $salario, $status) {
        if ($this->userModel->editFuncionario($id, $nome, $email, $cargo, $salario, $status)) {
            return "Funcionário atualizado com sucesso!";
        } else {
            return "Erro ao atualizar funcionário.";
        }
    }

    // Função para excluir um funcionário
    public function deleteFuncionario($id) {
        if ($this->userModel->deleteFuncionario($id)) {
            return "Funcionário excluído com sucesso!";
        } else {
            return "Erro ao excluir funcionário.";
        }
    }
    public function addToCart($usuarioId, $produtoId, $quantidade) {
        if ($this->userModel->addProductToCart($usuarioId, $produtoId, $quantidade)) {
            return "Produto adicionado ao carrinho com sucesso!";
        } else {
            return "Erro ao adicionar produto ao carrinho.";
        }
    }

    // Método para remover produto do carrinho
    public function removeFromCart($usuarioId, $produtoId) {
        if ($this->userModel->removeProductFromCart($usuarioId, $produtoId)) {
            return "Produto removido do carrinho com sucesso!";
        } else {
            return "Erro ao remover produto do carrinho.";
        }
    }
    public function getRelatorio() {
        $totalVendas = $this->getTotalVendas();
        $totalDespesas = $this->getTotalDespesas($_SESSION['usuario_id']); // Considerando que o ID do usuário está na sessão
        $totalClientes = $this->getTotalClientes();
        $totalProdutos = $this->getTotalProdutos();
    
        return [
            'totalVendas' => $totalVendas,
            'totalDespesas' => $totalDespesas,
            'totalClientes' => $totalClientes,
            'totalProdutos' => $totalProdutos,
        ];
    }
    

    // Método para exibir os itens do carrinho
    public function viewCart($usuarioId) {
        return $this->userModel->getCartItems($usuarioId);
    }
    // Função para listar os funcionários
    public function getFuncionarios() {
        return $this->userModel->getFuncionarios();
    }
    public function getFuncionarios2() {
        return $this->userModel->getFuncionarios2();
    }
    public function registerUser($nome, $email, $senha) {
        if ($this->userModel->userExists($email)) {
            error_log("Tentativa de cadastro com e-mail já existente: $email");
            return "Erro: Usuário com este e-mail já existe.";
        }

        if ($this->userModel->createUser($nome, $email, $senha)) {
            return "Parabéns, cadastrado com sucesso!";
        } else {
            error_log("Erro ao cadastrar usuário: $nome, $email");
            return "Erro ao cadastrar usuário.";
        }
    }

    // Realiza o login do usuário
    public function login($email, $senha) {
        $usuario = $this->userModel->login($email, $senha);

        if (is_array($usuario)) {
            $_SESSION['usuario_id'] = $usuario['id']; 
            $_SESSION['usuario_nome'] = $usuario['nome']; 
            $_SESSION['usuario_email'] = $usuario['email']; 

            return "Login realizado com sucesso.";
        } else {
            return $usuario;
        }
    }

    // Desloga o usuário
    public function logout() {
        session_start();
        session_destroy();
        return "Você foi desconectado.";
    }

    // Atualiza os dados do usuário
    public function updateUser($email, $novoNome, $novaSenha) {
        if ($this->userModel->update($email, $novoNome, $novaSenha)) {
            return "Informações atualizadas com sucesso!";
        } else {
            return "Erro ao atualizar informações.";
        }
    }

    public function createOrder($usuarioId, $itens) {
        return $this->userModel->createOrder($usuarioId, $itens);
    }

    // Recupera todos os pedidos de um usuário
    public function getOrdersByUser($usuarioId) {
        return $this->userModel->getOrdersByUser($usuarioId);
    }

    public function addProduct($nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento, $estoqueMinimo = 0) {
        // Chama o método do modelo para adicionar o produto no banco de dados
        if ($this->userModel->addProduct($nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento, $estoqueMinimo)) {
            return "Produto cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar o produto.";
        }
    }

    public function editProduct($id, $nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento) {
        // Chama o método do modelo para editar o produto
        if ($this->userModel->editProduct($id, $nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento)) {
            return "Produto editado com sucesso!";
        } else {
            return "Erro ao editar o produto.";
        }
    }

    public function deleteProduct($id) {
        // Chama o método do modelo para excluir o produto
        if ($this->userModel->deleteProduct($id)) {
            return "Produto excluído com sucesso!";
        } else {
            return "Erro ao excluir o produto.";
        }
    }

    public function reduzirEstoque($id, $quantidade) {
        // Chama o método do modelo para reduzir a quantidade de um produto
        if ($this->userModel->reduzirEstoque($id, $quantidade)) {
            return "Estoque reduzido com sucesso!";
        } else {
            return "Erro ao reduzir o estoque.";
        }
    }

    public function adicionarEstoque($id, $quantidade) {
        // Chama o método do modelo para adicionar quantidade no estoque
        if ($this->userModel->adicionarEstoque($id, $quantidade)) {
            return "Estoque adicionado com sucesso!";
        } else {
            return "Erro ao adicionar estoque.";
        }
    }

    public function verificarEstoqueMinimo() {
        // Chama o método do modelo para verificar os produtos com estoque abaixo do mínimo
        $produtosAbaixoEstoqueMinimo = $this->userModel->verificarEstoqueMinimo();
        if ($produtosAbaixoEstoqueMinimo) {
            return $produtosAbaixoEstoqueMinimo;
        } else {
            return "Não há produtos com estoque abaixo do mínimo.";
        }
    }

    public function getProdutosUltimoMovimento() {
        // Chama o método do modelo para obter os produtos com último movimento
        $produtosUltimoMovimento = $this->userModel->getProdutosUltimoMovimento();
        if ($produtosUltimoMovimento) {
            return $produtosUltimoMovimento;
        } else {
            return "Erro ao obter produtos com último movimento.";
        }
    }

    public function getAllProducts() {
        // Chama o método do modelo para obter todos os produtos
        $produtos = $this->userModel->getAllProducts();
        if ($produtos) {
            return $produtos;
        } else {
            return "Erro ao listar produtos.";
        }
    }

    // Registra um cliente
    public function registerCustomer($nome, $cpf, $email, $telefone, $endereco) {
        if ($this->userModel->customerExists($cpf)) {
            return "Erro: Cliente com este CPF já existe.";
        }

        if ($this->userModel->createCustomer($nome, $cpf, $email, $telefone, $endereco)) {
            return "Cliente cadastrado com sucesso!";
        } else {
            return "Erro ao cadastrar cliente.";
        }
    }

    // Atualiza as informações do cliente
    public function updateCustomer($cpf, $nome, $email, $telefone, $endereco) {
        if ($this->userModel->updateCustomer($cpf, $nome, $email, $telefone, $endereco)) {
            return "Informações do cliente atualizadas com sucesso!";
        } else {
            return "Erro ao atualizar informações do cliente.";
        }
    }

    // Calcula o lucro
    public function calcularLucro($usuarioId) {
        $totalVendas = $this->userModel->getTotalVendas(); // Assumindo que o método getTotalVendas está implementado
        $totalDespesas = $this->userModel->getTotalDespesas($usuarioId);

        return $totalVendas - $totalDespesas;
    }

    // Atualiza o crédito do cliente
    public function updateCredit($cpf, $valor) {
        if ($this->userModel->updateCredit($cpf, $valor)) {
            return "Crédito atualizado com sucesso!";
        } else {
            return "Erro ao atualizar crédito.";
        }
    }

    // Atualiza o histórico de compras do cliente
    public function updatePurchaseHistory($cpf, $historico) {
        if ($this->userModel->updatePurchaseHistory($cpf, $historico)) {
            return "Histórico de compras atualizado com sucesso!";
        } else {
            return "Erro ao atualizar histórico de compras.";
        }
    }

    // Recupera todos os clientes
    public function getAllCustomers() {
        return $this->userModel->getAllCustomers();
    }

    // Recupera o total de clientes
    
    public function getTotalClientes() {
        return $this->userModel->getTotalClientes();
    }

    // Recupera o total de produtos
    public function getTotalProdutos() {
        return $this->userModel->getTotalProdutos();
    }

    // Recupera o total de vendas
    public function getTotalVendas() {
        return $this->userModel->getTotalVendas();
    }

    // Recupera o total de despesas
    public function getTotalDespesas($usuarioId) {
        return $this->userModel->getTotalDespesas($usuarioId);
    }

    // Recupera as informações de um usuário
    public function getUserInfo($usuarioId) {
        return $this->userModel->getUserById($usuarioId);
    }

    // Função para adicionar um fornecedor
    public function addFornecedor($nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status) {
        if ($this->userModel->supplierExists($email)) {
            return "Erro: Fornecedor com este e-mail já existe.";
        }

        if ($this->userModel->addSupplier($nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status)) {
            return "Fornecedor adicionado com sucesso!";
        } else {
            return "Erro ao adicionar fornecedor.";
        }
    }

    // Função para atualizar um fornecedor
    public function updateFornecedor($id, $nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status) {
        if ($this->userModel->updateSupplier($id, $nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status)) {
            return "Fornecedor atualizado com sucesso!";
        } else {
            return "Erro ao atualizar fornecedor.";
        }
    }

    // Função para excluir fornecedor
    public function deleteFornecedor($id) {
        if ($this->userModel->deleteSupplier($id)) {
            return "Fornecedor excluído com sucesso!";
        } else {
            return "Erro ao excluir fornecedor.";
        }
    }

    // Função para listar todos os fornecedores
    public function getAllFornecedores() {
        return $this->userModel->getAllSuppliers();
    }

    // Função para obter informações de um fornecedor específico
    public function getFornecedorInfo($id) {
        return $this->userModel-> getAllSuppliers($id);
    }
    public function createVenda($total, $status_pagamento = 'Pendente') {
        // Chama o método do modelo para criar a venda no banco de dados
        $venda_id = $this->userModel->createVenda($total, $status_pagamento);
        
        if ($venda_id) {
            return "Venda registrada com sucesso! ID da venda: " . $venda_id;
        } else {
            return "Erro ao registrar a venda.";
        }
    }
    public function addProdutoVenda($venda_id, $produto_id, $quantidade, $preco_unitario) {
        // Chama o método do modelo para adicionar o produto na venda
        if ($this->userModel->addProdutoVenda($venda_id, $produto_id, $quantidade, $preco_unitario)) {
            return "Produto adicionado à venda com sucesso!";
        } else {
            return "Erro ao adicionar produto à venda.";
        }
    }
    public function addTransacaoCaixa($tipo_transacao, $valor, $descricao = null) {
        // Chama o método do modelo para registrar a transação no caixa
        if ($this->userModel->addTransacaoCaixa($tipo_transacao, $valor, $descricao)) {
            return "Transação registrada no caixa com sucesso!";
        } else {
            return "Erro ao registrar transação no caixa.";
        }
    }
    public function createRelatorio($tipo_relatorio, $data_inicio, $data_fim, $total) {
        // Chama o método do modelo para criar o relatório
        if ($this->userModel->createRelatorio($tipo_relatorio, $data_inicio, $data_fim, $total)) {
            return "Relatório gerado com sucesso!";
        } else {
            return "Erro ao gerar relatório.";
        }
    }
    public function getRelatorios() {
        // Chama o método do modelo para buscar os relatórios
        $relatorios = $this->userModel->getRelatorios();
        
        if ($relatorios) {
            return $relatorios;
        } else {
            return "Erro ao buscar relatórios.";
        }
    }
                   
}
?>
