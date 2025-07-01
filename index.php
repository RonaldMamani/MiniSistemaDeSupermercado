<?php

session_start();

function flash_message($name, $message = '') {
    if (!empty($message)) {
        $_SESSION['flash_message'][$name] = $message;
    } elseif (isset($_SESSION['flash_message'][$name])) {
        $msg = $_SESSION['flash_message'][$name];
        unset($_SESSION['flash_message'][$name]);
        return "<div>{$msg}</div>";
    }
    return '';
}

spl_autoload_register(function ($className) {
    
    $baseDir = __DIR__ . '/src/';

    $prefixes = [
        'Controller' => $baseDir . 'Controllers/',
        'Model' => $baseDir . 'Models/',
    ];

    foreach ($prefixes as $prefix => $directory) {

        $filePath = $directory . $className . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
});

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
    require_once __DIR__ . '/src/Views/login.php';
    exit;
}

$perfil = $autenticacao->obterPerfil();
$controller = null;

switch ($perfil) {
    case 'caixa':
        $controller = new CaixaController($autenticacao, $produtosHandler);
        break;
    case 'estoque':
        $controller = new EstoqueController($autenticacao, $produtosHandler, $solicitacoesHandler);
        break;
    case 'admin':
        $controller = new AdminController($autenticacao, $produtosHandler, $solicitacoesHandler);
        break;
    case 'financeiro':
        $controller = new FinanceiroController($autenticacao, $solicitacoesHandler);
        break;
    default:
        $autenticacao->sair();
        header('Location: index.php');
        exit;
}

if ($controller) {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $controller->handleRequest($action);
}