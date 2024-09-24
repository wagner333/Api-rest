<?php
require_once "/home/wagner/Downloads/Mini-FrameWork-2.0-main/core/database.php"; // Inclua seu arquivo de conexão com o banco de dados

class UserModel {
    private $db;

    public function __construct() {
        $this->db = conectarMongoDB(); // Conexão com o MongoDB
    }

    // Verifica se o usuário já existe
    public function userExists($email) {
        return $this->db->usuarios->findOne(['email' => $email]) !== null;
    }

    // Cria um novo usuário
    public function createUser($nome, $email, $senha) {
        try {
            $this->db->usuarios->insertOne([
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
    public function update($email, $novoNome, $novaSenha) {
    try {
        $atualizacao = [];
        
        if (!empty($novoNome)) {
            $atualizacao['nome'] = $novoNome;
        }
        
        if (!empty($novaSenha)) {
            $atualizacao['senha'] = password_hash($novaSenha, PASSWORD_DEFAULT);
        }

        $resultado = $this->db->usuarios->updateOne(
            ['email' => $email], 
            ['$set' => $atualizacao] 
        );

        return $resultado->getModifiedCount() > 0; 
    } catch (Exception $e) {
        echo "Erro ao atualizar usuário: " . $e->getMessage();
        return false; // Retorna falso em caso de erro
    }
}
public function login($email, $senha) {
    $usuario = $this->db->usuarios->findOne(['email' => $email]);

    // Verifica se o usuário existe
    if ($usuario) {
        // Verifica se a senha está correta
        if (password_verify($senha, $usuario['senha'])) {
            return true; // Login bem-sucedido
        } else {
            return false; // Senha incorreta
        }
    } else {
        return false; // Usuário não encontrado
    }
}


}

?>
