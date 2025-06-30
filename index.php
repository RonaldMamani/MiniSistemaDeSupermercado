<?php
session_start();

// Instanciar as classes
$autenticacao = new Autenticacao();
$produtosHandler = new ControleDeDados(__DIR__ . '/data/produtos.json');
$solicitacoesHandler = new ControleDeDados(__DIR__ . '/data/solicitacoes.json');

// --- AÇÃO DE LOGOUT ---
if (isset($_GET['logout'])) {
    $autenticacao->sair();
    header('Location: index.php');
    exit;
}

// --- LÓGICA DA TELA DE LOGIN ---
if (!$autenticacao->estaLogado()) {
    $erro_login = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        if (!$autenticacao->entrar($_POST['usuario'], $_POST['senha'])) {
            $erro_login = 'Usuário ou senha inválidos.';
        } else {
            header('Location: index.php');
            exit;
        }
    }
    require 'src/views/login.php';
    exit;
}

// --- LÓGICA DE NEGÓCIO APÓS O LOGIN ---
$perfil = $autenticacao->obterPerfil();
$produtos = $produtosHandler->ler();
$solicitacao = $solicitacoesHandler->ler();

require 'src/views/login.php';
