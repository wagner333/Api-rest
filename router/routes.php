<?php
// router/routes.php

$rotas = [
    '/' => 'index',
];
function index(){
     require '../app/views/index.php';
}

function gerenciarRotas($url) {
    global $rotas;

    if (array_key_exists($url, $rotas)) {
        $acao = $rotas[$url];
        call_user_func($acao);
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "404 - Página não encontrada.";
        exit();
    }
}
?>
 