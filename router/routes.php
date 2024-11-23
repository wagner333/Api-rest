<?php
// router/routes.php

$rotas = [
    '/' => 'index',                // Página inicial
    '/login' => 'login',           // Página de login
    '/register' => 'register',     // Página de cadastro
    '/dashboard' => 'dashboard',
    '/logout' => 'logout',
    '/CadastrarProdutos' => 'CadastrarProdutos',
    '/CadastrarClientes' => 'CadastrarClientes',
    '/cliente_cadastrado' => 'cliente_cadastrado',
    '/Perfil' => 'Perfil',
    '/CadastroFornecedores' => 'CadastroFornecedores',
    '/ControleCompras' => 'ControleCompras',
    '/ControleEstoque' => 'ControleEstoque',
    '/VendasCaixa' => 'VendasCaixa',
    '/SistemaPromocao' => 'SistemaPromocao',
    '/Funcionarios' => 'Funcionarios',
    '/ControleDespesas' => 'ControleDespesas',
    '/relatorios' => 'relatorios'

];

function relatorios(){
    require '../app/views/relatorios.php';
}
function ControleDespesas(){
    require '../app/views/ControleDespesas.php';
}
function Funcionarios(){
    require '../app/views/Funcionarios.php';
}
function SistemaPromocao(){
    require '../app/views/SistemaPromocao.php';
}
function VendasCaixa(){
    require '../app/views/VendasCaixa.php';
}
function ControleEstoque(){
    require '../app/views/ControleEstoque.php';
}
function ControleCompras(){
    require '../app/views/ControleCompras.php';
}
function index() {
    require '../app/views/index.php';  // Página inicial
}
function CadastroFornecedores(){
    require '../app/views/CadastroFornecedores.php';
}
function cliente_cadastrado(){
    require '../app/views/cliente_cadastrado.php'; 
}
function Perfil(){
    require '../app/views/Perfil.php'; 
}

function CadastrarClientes(){
    require '../app/views/CadastrarClientes.php'; 
}
function CadastrarProdutos(){
    require '../app/views/CadastrarProdutos.php'; 
}

function login() {
    require '../app/views/login.php';  // Página de login
}

function register() {
    require '../app/views/register.php';  // Página de cadastro
}

function dashboard() {
    require '../app/views/dashboard.php';  // Página do dashboard
}
function logout(){
    require '../app/views/logout.php';
}



function gerenciarRotas($url) {
    global $rotas;

    if (array_key_exists($url, $rotas)) {
        $acao = $rotas[$url];
        call_user_func($acao); // O nome da função correspondente ao array de rotas
    } else {
        header("HTTP/1.0 404 Not Found");
        require "/home/wagner/Documentos/miniframe/app/err/index.php";
        exit();
    }
}
