<?php
// core/database.php

require_once '../vendor/autoload.php'; // Certifique-se de que o autoload do Composer está incluído

function conectarSQLite() {
    $dbPath = $_ENV['DB_NAME'] ?? 'database.sqlite'; // Valor padrão

    try {
        $db = new PDO("sqlite:$dbPath");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db; // Retorna a instância do banco de dados
    } catch (PDOException $e) {
        echo "Erro na conexão: " . $e->getMessage();
        exit();
    }
}
?>
