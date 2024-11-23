<?php
// Inicia a sessão no início do arquivo
session_start();

// Verifica se há uma sessão ativa
if (isset($_SESSION['usuario_id'])) {
    // Destrói a sessão
    session_unset();  // Libera todas as variáveis de sessão
    session_destroy(); // Destrói a sessão
}

// Redireciona o usuário para a página de login após o logout
header('Location: /login');
exit(); // Garante que o código abaixo não seja executado
?>
