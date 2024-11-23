<?php
require_once "../core/database.php"; // Inclua seu arquivo de conexão com o banco de dados

class UserModel {
    private $db;

    public function __construct() {
        $this->db = conectarSQLite(); // Conexão com o SQLite
        $this->createTablesIfNotExists(); 
        
    }

    // Verifica se o usuário já existe
    public function userExists($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() !== false;
    }
    public function createTablesIfNotExists() {
        try {
            
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS usuarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                senha TEXT NOT NULL
            )");
            $stmt->execute();

            // Tabela 'produtos'
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS produtos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                preco REAL NOT NULL,
                preco_venda REAL NOT NULL,
                quantidade INTEGER NOT NULL,
                estoque_minimo INTEGER NOT NULL DEFAULT 0,  -- Define o mínimo de estoque
                data_vencimento DATE,
                descricao TEXT,
                data_entrada DATE NOT NULL DEFAULT CURRENT_DATE,  -- Registra a data de entrada no estoque
                data_ultimo_movimento DATE  -- Registra a data do último movimento (venda ou reposição)
            )");
            $stmt->execute();

            // Tabela 'vendas'
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS vendas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER NOT NULL,
                total REAL NOT NULL,
                data_venda TEXT NOT NULL,
                FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
            )");
            $stmt->execute();
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS carrinho (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER NOT NULL,
                produto_id INTEGER NOT NULL,
                quantidade INTEGER NOT NULL,
                FOREIGN KEY(usuario_id) REFERENCES usuarios(id),
                FOREIGN KEY(produto_id) REFERENCES produtos(id)
            )");
            $stmt->execute();
            // Tabela 'clientes'
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS clientes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                cpf TEXT NOT NULL UNIQUE,
                email TEXT NOT NULL,
                telefone TEXT NOT NULL,
                endereco TEXT,
                credito REAL DEFAULT 0,
                historico_compras TEXT
            )");
            $stmt->execute();
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS despesas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario_id INTEGER NOT NULL,
                descricao TEXT,
                valor REAL NOT NULL,
                data_despesa TEXT NOT NULL,
                FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
            )");
            $stmt->execute();
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS fornecedores (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,              
                cnpj_cpf TEXT NOT NULL,                    
                tipo_produto_servico TEXT NOT NULL, 
                whatsapp TEXT NOT NULL,            
                endereco TEXT NOT NULL,            
                email TEXT NOT NULL UNIQUE,       
                site TEXT,                         
                status TEXT NOT NULL               
            );");
            $stmt->execute();
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS funcionarios (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nome TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                cargo TEXT NOT NULL,
                salario REAL NOT NULL,
                data_admissao DATETIME DEFAULT CURRENT_TIMESTAMP,
                status TEXT DEFAULT 'Ativo'
            )");
            $stmt->execute();
            // Tabela 'vendas'
            $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS vendas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
                total REAL NOT NULL,
                status_pagamento TEXT DEFAULT 'Pendente',
                usuario_id INTEGER NOT NULL,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id) -- Adiciona a referência à tabela de usuários, se necessário
            )");
            $stmt->execute();
            

        // Tabela 'venda_produtos'
        $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS venda_produtos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            venda_id INTEGER,
            produto_id INTEGER,
            quantidade INTEGER NOT NULL,
            preco_unitario REAL NOT NULL,
            total_produto REAL NOT NULL,
            FOREIGN KEY (venda_id) REFERENCES vendas(id),
            FOREIGN KEY (produto_id) REFERENCES produtos(id)
        )");
        $stmt->execute();

        // Tabela 'caixa'
        $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS caixa (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            data_transacao DATETIME DEFAULT CURRENT_TIMESTAMP,
            tipo_transacao TEXT,
            valor REAL NOT NULL,
            descricao TEXT
        )");
        $stmt->execute();

        // Tabela 'relatorios'
        $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS relatorios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tipo_relatorio TEXT,
            data_inicio DATETIME,
            data_fim DATETIME,
            total REAL NOT NULL
        )");
        $stmt->execute();
        $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS despesas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            descricao TEXT NOT NULL,
            valor REAL NOT NULL,
            categoria TEXT NOT NULL,  -- Coluna categoria obrigatória
            data DATE DEFAULT CURRENT_DATE,
            usuario_id INTEGER NOT NULL,  -- Coluna usuario_id obrigatória
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id)  -- Referência à tabela 'usuarios'
        )");
        $stmt->execute();
            
            
            
        } catch (Exception $e) {
            echo "Erro ao criar tabelas: " . $e->getMessage();
        }
    }
    
    public function createDespesa($descricao, $valor, $categoria , $usuario_id) {
        try {
            $stmt = $this->db->prepare("INSERT INTO despesas (descricao, valor, categoria, usuario_id) 
                                        VALUES (:descricao, :valor, :categoria, :usuario_id)");
            $stmt->execute([
                'descricao' => $descricao,
                'valor' => $valor,
                'categoria' => $categoria,
                'usuario_id' => $usuario_id // Aqui estamos passando o id do usuário
            ]);
            return true;
        } catch (Exception $e) {
            echo "Erro ao cadastrar despesa: " . $e->getMessage();
            return false;
        }
    }
    

    // Função para listar todas as despesas
    public function getDespesas() {
        $stmt = $this->db->prepare("SELECT * FROM despesas");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para editar uma despesa
    public function editDespesa($id, $descricao, $valor, $categoria) {
        try {
            $stmt = $this->db->prepare("UPDATE despesas SET descricao = :descricao, valor = :valor, categoria = :categoria WHERE id = :id");
            $stmt->execute([
                'descricao' => $descricao,
                'valor' => $valor,
                'categoria' => $categoria,
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Função para excluir uma despesa
    public function deleteDespesa($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM despesas WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function createFuncionario($nome, $email, $cargo, $salario) {
        try {
            $stmt = $this->db->prepare("INSERT INTO funcionarios (nome, email, cargo, salario) VALUES (:nome, :email, :cargo, :salario)");
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'cargo' => $cargo,
                'salario' => $salario
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Função para listar todos os funcionários
    public function getFuncionarios() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM funcionarios");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna todos os resultados da consulta
        } catch (Exception $e) {
            return [];  // Retorna um array vazio em caso de erro
        }
    }
    public function getFuncionarios2() {
        $stmt = $this->db->query("SELECT * FROM funcionarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para editar dados de um funcionário
    public function editFuncionario($id, $nome, $email, $cargo, $salario, $status) {
        try {
            $stmt = $this->db->prepare("UPDATE funcionarios SET nome = :nome, email = :email, cargo = :cargo, salario = :salario, status = :status WHERE id = :id");
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'cargo' => $cargo,
                'salario' => $salario,
                'status' => $status,
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Função para excluir um funcionário
    public function deleteFuncionario($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM funcionarios WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function createVenda($total, $status_pagamento = 'Pendente') {
        try {
            if (empty($total)) {
                throw new Exception("O total da venda é obrigatório.");
            }
    
            $stmt = $this->db->prepare("INSERT INTO vendas (total, status_pagamento) VALUES (:total, :status_pagamento)");
            $stmt->execute([
                'total' => $total,
                'status_pagamento' => $status_pagamento
            ]);
    
            return $this->db->lastInsertId();  // Retorna o ID da venda recém-criada
        } catch (Exception $e) {
            echo "Erro ao registrar venda: " . $e->getMessage();
            return false;
        }
    }
    
    public function updateVenda($id, $status_pagamento) {
        try {
            if (empty($status_pagamento)) {
                throw new Exception("O status do pagamento é obrigatório.");
            }
    
            $stmt = $this->db->prepare("UPDATE vendas SET status_pagamento = :status_pagamento WHERE id = :id");
            $stmt->execute([
                'id' => $id,
                'status_pagamento' => $status_pagamento
            ]);
    
            return true;
        } catch (Exception $e) {
            echo "Erro ao atualizar venda: " . $e->getMessage();
            return false;
        }
    }
    public function addProdutoVenda($venda_id, $produto_id, $quantidade, $preco_unitario) {
        try {
            if (empty($venda_id) || empty($produto_id) || empty($quantidade) || empty($preco_unitario)) {
                throw new Exception("Todos os campos são obrigatórios.");
            }
    
            $total_produto = $quantidade * $preco_unitario;
    
            $stmt = $this->db->prepare("INSERT INTO venda_produtos (venda_id, produto_id, quantidade, preco_unitario, total_produto) 
                VALUES (:venda_id, :produto_id, :quantidade, :preco_unitario, :total_produto)");
    
            $stmt->execute([
                'venda_id' => $venda_id,
                'produto_id' => $produto_id,
                'quantidade' => $quantidade,
                'preco_unitario' => $preco_unitario,
                'total_produto' => $total_produto
            ]);
    
            return true;
        } catch (Exception $e) {
            echo "Erro ao adicionar produto à venda: " . $e->getMessage();
            return false;
        }
    }
    public function addTransacaoCaixa($tipo_transacao, $valor, $descricao = null) {
        try {
            if (empty($tipo_transacao) || empty($valor)) {
                throw new Exception("Tipo de transação e valor são obrigatórios.");
            }
    
            $stmt = $this->db->prepare("INSERT INTO caixa (tipo_transacao, valor, descricao) VALUES (:tipo_transacao, :valor, :descricao)");
            $stmt->execute([
                'tipo_transacao' => $tipo_transacao,
                'valor' => $valor,
                'descricao' => $descricao
            ]);
    
            return true;
        } catch (Exception $e) {
            echo "Erro ao registrar transação no caixa: " . $e->getMessage();
            return false;
        }
    }
    public function addProductToCart($usuarioId, $produtoId, $quantidade) {
        // Insira o código para adicionar o produto ao carrinho no banco de dados
        $sql = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (:usuario_id, :produto_id, :quantidade)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':usuario_id' => $usuarioId, ':produto_id' => $produtoId, ':quantidade' => $quantidade]);
    }

    // Remove produto do carrinho
    public function removeProductFromCart($usuarioId, $produtoId) {
        // Insira o código para remover o produto do carrinho no banco de dados
        $sql = "DELETE FROM carrinho WHERE usuario_id = :usuario_id AND produto_id = :produto_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':usuario_id' => $usuarioId, ':produto_id' => $produtoId]);
    }

    // Retorna os itens do carrinho
    public function getCartItems($usuarioId) {
        $sql = "SELECT p.nome, p.preco, c.quantidade FROM carrinho c JOIN produtos p ON c.produto_id = p.id WHERE c.usuario_id = :usuario_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createRelatorio($tipo_relatorio, $data_inicio, $data_fim, $total) {
        try {
            if (empty($tipo_relatorio) || empty($data_inicio) || empty($data_fim) || empty($total)) {
                throw new Exception("Todos os campos são obrigatórios.");
            }
    
            $stmt = $this->db->prepare("INSERT INTO relatorios (tipo_relatorio, data_inicio, data_fim, total) 
                VALUES (:tipo_relatorio, :data_inicio, :data_fim, :total)");
    
            $stmt->execute([
                'tipo_relatorio' => $tipo_relatorio,
                'data_inicio' => $data_inicio,
                'data_fim' => $data_fim,
                'total' => $total
            ]);
    
            return true;
        } catch (Exception $e) {
            echo "Erro ao gerar relatório: " . $e->getMessage();
            return false;
        }
    }
    
    public function getRelatorios() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM relatorios");
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Erro ao buscar relatórios: " . $e->getMessage();
            return false;
        }
    }
                
    public function createUser($nome, $email, $senha) {
        try {
            if (empty($nome) || empty($email) || empty($senha)) {
                throw new Exception("Nome, email e senha são obrigatórios.");
            }

            $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'senha' => password_hash($senha, PASSWORD_DEFAULT)
            ]);
            return true; 
        } catch (Exception $e) {
            echo "Erro ao cadastrar usuário: " . $e->getMessage();
            return false; 
        }
    }

    // Atualiza as informações do usuário
    public function update($email, $novoNome, $novaSenha) {
        try {
            $atualizacao = [];
            if (!empty($novoNome)) {
                $atualizacao['nome'] = $novoNome;
            }
            if (!empty($novaSenha)) {
                $atualizacao['senha'] = password_hash($novaSenha, PASSWORD_DEFAULT);
            }

            $query = "UPDATE usuarios SET ";
            foreach ($atualizacao as $key => $value) {
                $query .= "$key = :$key, ";
            }
            $query = rtrim($query, ', ') . " WHERE email = :email";
            $atualizacao['email'] = $email;

            $stmt = $this->db->prepare($query);
            $stmt->execute($atualizacao);
            return $stmt->rowCount() > 0; 
        } catch (Exception $e) {
            echo "Erro ao atualizar usuário: " . $e->getMessage();
            return false; 
        }
    }

    // Realiza o login do usuário
    public function login($email, $senha) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            if (password_verify($senha, $usuario['senha'])) {
                return $usuario; // Retorna os dados do usuário para a sessão
            } else {
                return 'Senha incorreta. Tente novamente.';
            }
        } else {
            return 'Usuário não encontrado. Verifique o email e tente novamente.';
        }
    }

  
   

    // Cria um pedido
    public function createOrder($usuarioId, $itens) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, status) VALUES (:usuario_id, 'pendente')");
            $stmt->execute(['usuario_id' => $usuarioId]);

            $pedidoId = $this->db->lastInsertId();

            foreach ($itens as $itemId) {
                $stmt = $this->db->prepare("INSERT INTO pedido_itens (pedido_id, item_id) VALUES (:pedido_id, :item_id)");
                $stmt->execute(['pedido_id' => $pedidoId, 'item_id' => $itemId]);
            }

            $this->db->commit();
            return "Pedido realizado com sucesso!";
        } catch (Exception $e) {
            $this->db->rollBack();
            echo "Erro ao criar pedido: " . $e->getMessage();
            return false;
        }
    }

    
    public function getOrdersByUser($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE usuario_id = :usuario_id");
        $stmt->execute(['usuario_id' => $usuarioId]);
        return $stmt->fetchAll();
    }

    // Adiciona um produto ao inventário
    public function addProduct($nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento, $estoqueMinimo = 0) {
        try {
            $stmt = $this->db->prepare("INSERT INTO produtos (nome, preco, preco_venda, quantidade, descricao, data_vencimento, estoque_minimo) 
                                        VALUES (:nome, :preco, :preco_venda, :quantidade, :descricao, :data_vencimento, :estoque_minimo)");
            $stmt->execute([
                'nome' => $nome,
                'preco' => $preco,
                'preco_venda' => $precoVenda,
                'quantidade' => $quantidade,
                'descricao' => $descricao,
                'data_vencimento' => $dataVencimento,
                'estoque_minimo' => $estoqueMinimo
            ]);
            return true;
        } catch (Exception $e) {
            echo "Erro ao adicionar produto: " . $e->getMessage();
            return false;
        }
    }
    public function reduzirEstoque($id, $quantidade) {
        try {
            // Primeiro, verificamos se há estoque suficiente
            $stmt = $this->db->prepare("SELECT quantidade FROM produtos WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $produto = $stmt->fetch();
    
            if ($produto && $produto['quantidade'] >= $quantidade) {
                // Se houver estoque suficiente, reduzimos a quantidade e atualizamos a data do último movimento
                $stmt = $this->db->prepare("UPDATE produtos 
                                            SET quantidade = quantidade - :quantidade, 
                                                data_ultimo_movimento = CURRENT_DATE
                                            WHERE id = :id");
                $stmt->execute([
                    'quantidade' => $quantidade,
                    'id' => $id
                ]);
                return true;
            } else {
                // Se não houver estoque suficiente
                echo "Estoque insuficiente!";
                return false;
            }
        } catch (Exception $e) {
            echo "Erro ao reduzir estoque: " . $e->getMessage();
            return false;
        }
    }
    public function adicionarEstoque($id, $quantidade) {
        try {
            // Atualiza a quantidade no estoque, somando a quantidade fornecida
            $stmt = $this->db->prepare("UPDATE produtos 
                                        SET quantidade = quantidade + :quantidade, 
                                            data_ultimo_movimento = CURRENT_DATE 
                                        WHERE id = :id");
            $stmt->execute([
                'quantidade' => $quantidade,
                'id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo "Erro ao adicionar estoque: " . $e->getMessage();
            return false;
        }
    }
    public function verificarEstoqueMinimo() {
        try {
            $stmt = $this->db->prepare("SELECT id, nome, quantidade, estoque_minimo 
                                        FROM produtos 
                                        WHERE quantidade <= estoque_minimo");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Erro ao verificar estoque mínimo: " . $e->getMessage();
            return false;
        }
    }
    public function getProdutosUltimoMovimento() {
        try {
            $stmt = $this->db->prepare("SELECT id, nome, quantidade, data_ultimo_movimento 
                                        FROM produtos");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Erro ao obter produtos com último movimento: " . $e->getMessage();
            return false;
        }
    }
    
    
       public function editProduct($id, $nome, $preco, $precoVenda, $quantidade, $descricao, $dataVencimento) {
        try {
            $stmt = $this->db->prepare("UPDATE produtos 
                                        SET nome = :nome, preco = :preco, preco_venda = :preco_venda, quantidade = :quantidade, descricao = :descricao, data_vencimento = :data_vencimento 
                                        WHERE id = :id");
            $stmt->execute([
                'id' => $id,
                'nome' => $nome,
                'preco' => $preco,
                'preco_venda' => $precoVenda,
                'quantidade' => $quantidade,
                'descricao' => $descricao,
                'data_vencimento' => $dataVencimento
            ]);
            return true;
        } catch (Exception $e) {
            echo "Erro ao editar produto: " . $e->getMessage();
            return false;
        }
    }
    
    public function deleteProduct($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM produtos WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return true;
        } catch (Exception $e) {
            echo "Erro ao excluir produto: " . $e->getMessage();
            return false;
        }
    }
    public function getAllProducts() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM produtos");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Erro ao listar produtos: " . $e->getMessage();
            return false;
        }
    }
    
  

    // Verifica se o cliente já existe
    public function customerExists($cpf) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE cpf = :cpf");
        $stmt->execute(['cpf' => $cpf]);
        return $stmt->fetch() !== false;
    }
    
    // Cria um novo cliente
    public function createCustomer($nome, $cpf, $email, $telefone, $endereco) {
        try {
            if (empty($nome) || empty($cpf) || empty($email) || empty($telefone)) {
                throw new Exception("Nome, CPF, email e telefone são obrigatórios.");
            }

            $stmt = $this->db->prepare("INSERT INTO clientes (nome, cpf, email, telefone, endereco) 
                                        VALUES (:nome, :cpf, :email, :telefone, :endereco)");
            $stmt->execute([
                'nome' => $nome,
                'cpf' => $cpf,
                'email' => $email,
                'telefone' => $telefone,
                'endereco' => $endereco
            ]);
            return true; 
        } catch (Exception $e) {
            echo "Erro ao cadastrar cliente: " . $e->getMessage();
            return false;
        }
    }

    // Atualiza as informações do cliente
    public function updateCustomer($cpf, $nome, $email, $telefone, $endereco) {
        try {
            $stmt = $this->db->prepare("UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco WHERE cpf = :cpf");
            $stmt->execute([
                'cpf' => $cpf,
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'endereco' => $endereco
            ]);
            return $stmt->rowCount() > 0; 
        } catch (Exception $e) {
            echo "Erro ao atualizar cliente: " . $e->getMessage();
            return false; 
        }
    }

    // Recupera todos os clientes
    public function getAllCustomers() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM clientes");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Erro ao listar clientes: " . $e->getMessage();
            return false;
        }
    }

    // Atualiza o crédito de um cliente
    public function updateCredit($cpf, $valor) {
        try {
            $stmt = $this->db->prepare("UPDATE clientes SET credito = :credito WHERE cpf = :cpf");
            $stmt->execute([
                'cpf' => $cpf,
                'credito' => $valor
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            echo "Erro ao atualizar crédito do cliente: " . $e->getMessage();
            return false;
        }
    }

    // Adiciona histórico de compras para o cliente
    public function updatePurchaseHistory($cpf, $historico) {
        try {
            $stmt = $this->db->prepare("UPDATE clientes SET historico_compras = :historico WHERE cpf = :cpf");
            $stmt->execute([
                'cpf' => $cpf,
                'historico' => $historico
            ]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            echo "Erro ao atualizar histórico de compras: " . $e->getMessage();
            return false;
        }
    }
    public function getTotalClientes() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM clientes");
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }

    // Recupera o total de produtos
    public function getTotalProdutos() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM produtos");
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }

    // Recupera o total de vendas
  // Recupera o total de vendas
public function getTotalVendas() {
    $stmt = $this->db->prepare("SELECT SUM(total) FROM vendas");
    $stmt->execute();
    $totalVendas = $stmt->fetchColumn();
    return $totalVendas ? $totalVendas : 0;  // Retorna 0 se não houver vendas
}

    // No UserModel, adicione um método para pegar o total de despesas
    public function getTotalDespesas($usuarioId) {
        $stmt = $this->db->prepare("SELECT SUM(valor) FROM despesas WHERE usuario_id = :usuario_id");
        $stmt->execute(['usuario_id' => $usuarioId]);
        $totalDespesas = $stmt->fetchColumn();
        return $totalDespesas ? $totalDespesas : 0;
    }
    public function getUserById($id) {
        try {
            $stmt = $this->db->prepare("SELECT id, nome, email FROM usuarios WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return false;
        }
    }
   // Verifica se o fornecedor já existe pelo CNPJ ou CPF
public function fornecedorExists($cnpjCpf) {
    $stmt = $this->db->prepare("SELECT * FROM fornecedores WHERE cnpj_cpf = :cnpj_cpf");

    $stmt->execute(['cnpj_cpf' => $cnpjCpf]);
    return $stmt->fetch() !== false; 
}

// Cria um novo fornecedor
public function createFornecedor($nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $email, $site, $endereco, $status) {
    try {
        // Verifica se os campos obrigatórios estão preenchidos
        if (empty($nome) || empty($cnpjCpf) || empty($tipoProdutoServico) || empty($whatsapp) || empty($email) || empty($endereco) || empty($status)) {
            throw new Exception("Nome, CNPJ/CPF, tipo de produto/serviço, whatsapp, email, endereço e status são obrigatórios.");
        }

        $stmt = $this->db->prepare("INSERT INTO fornecedores (nome, cnpj_cpf, tipo_produto_servico, whatsapp, email, site, endereco, status) 
                                    VALUES (:nome, :cnpj_cpf, :tipo_produto_servico, :whatsapp, :email, :site, :endereco, :status)");
        $stmt->execute([
            'nome' => $nome,
            'cnpj_cpf' => $cnpjCpf,
            'tipo_produto_servico' => $tipoProdutoServico,
            'whatsapp' => $whatsapp,
            'email' => $email,
            'site' => $site,
            'endereco' => $endereco,
            'status' => $status
        ]);
        return true; 
    } catch (Exception $e) {
        echo "Erro ao cadastrar fornecedor: " . $e->getMessage();
        return false;
    }
}

// Atualiza as informações do fornecedor
public function updateFornecedor($cnpjCpf, $nome, $tipoProdutoServico, $whatsapp, $email, $site, $endereco, $status) {
    try {
        $stmt = $this->db->prepare("UPDATE fornecedores SET nome = :nome, tipo_produto_servico = :tipo_produto_servico, whatsapp = :whatsapp, email = :email, site = :site, endereco = :endereco, status = :status WHERE cnpj_cpf = :cnpj_cpf");
        $stmt->execute([
            'cnpj_cpf' => $cnpjCpf,
            'nome' => $nome,
            'tipo_produto_servico' => $tipoProdutoServico,
            'whatsapp' => $whatsapp,
            'email' => $email,
            'site' => $site,
            'endereco' => $endereco,
            'status' => $status
        ]);
        return $stmt->rowCount() > 0; 
    } catch (Exception $e) {
        echo "Erro ao atualizar fornecedor: " . $e->getMessage();
        return false; 
    }
}

// Recupera todos os fornecedores
public function getAllFornecedores() {
    try {
        $stmt = $this->db->prepare("SELECT * FROM fornecedores");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        echo "Erro ao listar fornecedores: " . $e->getMessage();
        return false;
    }
}

// Deleta um fornecedor
public function deleteFornecedor($cnpjCpf) {
    try {
        $stmt = $this->db->prepare("DELETE FROM fornecedores WHERE cnpj_cpf = :cnpj_cpf");
        $stmt->execute(['cnpj_cpf' => $cnpjCpf]);
        return true;
    } catch (Exception $e) {
        echo "Erro ao excluir fornecedor: " . $e->getMessage();
        return false;
    }
}

// Recupera fornecedor por CNPJ/CPF
public function getFornecedorByCnpjCpf($cnpjCpf) {
    try {
        $stmt = $this->db->prepare("SELECT * FROM fornecedores WHERE cnpj_cpf = :cnpj_cpf");
        $stmt->execute(['cnpj_cpf' => $cnpjCpf]);
        return $stmt->fetch(); // Retorna o fornecedor se encontrado, ou false se não encontrado
    } catch (Exception $e) {
        echo "Erro ao buscar fornecedor: " . $e->getMessage();
        return false; // Retorna false em caso de erro
    }
}

public function addSupplier($nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status) {
    try {
        // Verifica se o fornecedor já existe
        if ($this->supplierExists($email)) {
            throw new Exception("Fornecedor com este email já existe.");
        }

        $stmt = $this->db->prepare("INSERT INTO fornecedores (nome, cnpj_cpf, tipo_produto_servico, whatsapp, endereco, email, site, status) 
                                    VALUES (:nome, :cnpjCpf, :tipoProdutoServico, :whatsapp, :endereco, :email, :site, :status)");
        $stmt->execute([
            'nome' => $nome,
            'cnpjCpf' => $cnpjCpf,
            'tipoProdutoServico' => $tipoProdutoServico,
            'whatsapp' => $whatsapp,
            'endereco' => $endereco,
            'email' => $email,
            'site' => $site,
            'status' => $status
        ]);
        return true;
    } catch (Exception $e) {
        echo "Erro ao adicionar fornecedor: " . $e->getMessage();
        return false;
    }
}

// Verifica se o fornecedor já existe
public function supplierExists($email) {
    $stmt = $this->db->prepare("SELECT * FROM fornecedores WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch() !== false;
}

// Atualiza as informações de um fornecedor
public function updateSupplier($id, $nome, $cnpjCpf, $tipoProdutoServico, $whatsapp, $endereco, $email, $site, $status) {
    try {
        $stmt = $this->db->prepare("UPDATE fornecedores SET nome = :nome, cnpj_cpf = :cnpjCpf, tipo_produto_servico = :tipoProdutoServico, 
                                    whatsapp = :whatsapp, endereco = :endereco, email = :email, site = :site, status = :status WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'cnpjCpf' => $cnpjCpf,
            'tipoProdutoServico' => $tipoProdutoServico,
            'whatsapp' => $whatsapp,
            'endereco' => $endereco,
            'email' => $email,
            'site' => $site,
            'status' => $status
        ]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        echo "Erro ao atualizar fornecedor: " . $e->getMessage();
        return false;
    }
}

// Exclui um fornecedor
public function deleteSupplier($id) {
    try {
        $stmt = $this->db->prepare("DELETE FROM fornecedores WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return true;
    } catch (Exception $e) {
        echo "Erro ao excluir fornecedor: " . $e->getMessage();
        return false;
    }
}

// Recupera todos os fornecedores
public function getAllSuppliers() {
    try {
        $stmt = $this->db->prepare("SELECT * FROM fornecedores");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        echo "Erro ao listar fornecedores: " . $e->getMessage();
        return false;
    }
}
public function addToCart($usuarioId, $produtoId, $quantidade) {
    try {
        // Verifica se o produto já está no carrinho
        $stmt = $this->db->prepare("SELECT * FROM carrinho WHERE usuario_id = :usuario_id AND produto_id = :produto_id");
        $stmt->execute(['usuario_id' => $usuarioId, 'produto_id' => $produtoId]);
        $produtoCarrinho = $stmt->fetch();

        if ($produtoCarrinho) {
            // Se o produto já está no carrinho, apenas atualiza a quantidade
            $novaQuantidade = $produtoCarrinho['quantidade'] + $quantidade;
            $stmt = $this->db->prepare("UPDATE carrinho SET quantidade = :quantidade WHERE usuario_id = :usuario_id AND produto_id = :produto_id");
            $stmt->execute(['quantidade' => $novaQuantidade, 'usuario_id' => $usuarioId, 'produto_id' => $produtoId]);
        } else {
            // Se o produto não está no carrinho, insere ele
            $stmt = $this->db->prepare("INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES (:usuario_id, :produto_id, :quantidade)");
            $stmt->execute(['usuario_id' => $usuarioId, 'produto_id' => $produtoId, 'quantidade' => $quantidade]);
        }

        return true;
    } catch (Exception $e) {
        echo "Erro ao adicionar produto ao carrinho: " . $e->getMessage();
        return false;
    }
}

public function removeFromCart($usuarioId, $produtoId) {
    try {
        $stmt = $this->db->prepare("DELETE FROM carrinho WHERE usuario_id = :usuario_id AND produto_id = :produto_id");
        $stmt->execute(['usuario_id' => $usuarioId, 'produto_id' => $produtoId]);
        return true;
    } catch (Exception $e) {
        echo "Erro ao remover produto do carrinho: " . $e->getMessage();
        return false;
    }
}
public function finalizePurchase($usuarioId) {
    try {
        // Inicia a transação
        $this->db->beginTransaction();

        // Cria um pedido
        $stmt = $this->db->prepare("INSERT INTO vendas (usuario_id, total, data_venda) VALUES (:usuario_id, 0, :data_venda)");
        $stmt->execute(['usuario_id' => $usuarioId, 'data_venda' => date('Y-m-d H:i:s')]);
        $pedidoId = $this->db->lastInsertId();

        // Recupera os itens do carrinho
        $stmt = $this->db->prepare("SELECT c.produto_id, c.quantidade, p.preco_venda
                                    FROM carrinho c
                                    JOIN produtos p ON c.produto_id = p.id
                                    WHERE c.usuario_id = :usuario_id");
        $stmt->execute(['usuario_id' => $usuarioId]);
        $itens = $stmt->fetchAll();

        // Calcula o total da compra
        $total = 0;
        foreach ($itens as $item) {
            $total += $item['preco_venda'] * $item['quantidade'];
        }

        // Atualiza o total do pedido
        $stmt = $this->db->prepare("UPDATE vendas SET total = :total WHERE id = :pedido_id");
        $stmt->execute(['total' => $total, 'pedido_id' => $pedidoId]);

        // Cria os itens do pedido
        foreach ($itens as $item) {
            $stmt = $this->db->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade) VALUES (:pedido_id, :produto_id, :quantidade)");
            $stmt->execute(['pedido_id' => $pedidoId, 'produto_id' => $item['produto_id'], 'quantidade' => $item['quantidade']]);
        }

        // Limpa o carrinho
        $stmt = $this->db->prepare("DELETE FROM carrinho WHERE usuario_id = :usuario_id");
        $stmt->execute(['usuario_id' => $usuarioId]);

        // Commit da transação
        $this->db->commit();

        return true;
    } catch (Exception $e) {
        // Caso haja erro, desfaz a transação
        $this->db->rollBack();
        echo "Erro ao finalizar a compra: " . $e->getMessage();
        return false;
    }
}

}


?>
