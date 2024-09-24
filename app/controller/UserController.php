<?php
require_once '/home/wagner/Downloads/Mini-FrameWork-2.0-main/app/model/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

public function registerUser($nome, $email, $senha) {
    // Verifica se o usuário já existe
    if ($this->userModel->userExists($email)) {
        error_log("Tentativa de cadastro com e-mail já existente: $email");
        return "Erro: Usuário com este e-mail já existe.";
    }

    // Tenta criar o usuário
    if ($this->userModel->createUser($nome, $email, $senha)) {
        return "Parabéns, cadastrado com sucesso!";
    } else {
        error_log("Erro ao cadastrar usuário: $nome, $email");
        return "Erro ao cadastrar usuário.";
    }
}


   public function login($email, $senha){
    if($this->userModel->login($email, $senha)){
        return"login realizado";

    }else{
        return "Erro ao logar";
        
    }

   }
}
?>
