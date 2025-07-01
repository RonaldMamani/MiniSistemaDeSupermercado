<?php

$usuario_logado = isset($autenticacao) && $autenticacao->estaLogado() ? $autenticacao->obterUsuario() : 'Convidado';

$perfil_display = '';
if (isset($autenticacao) && $autenticacao->estaLogado()) {
    $perfil_from_auth = $autenticacao->obterPerfil();
    if (is_string($perfil_from_auth) && !empty($perfil_from_auth)) {
        $perfil_display = ucfirst($perfil_from_auth);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Supermercado - <?= htmlspecialchars(ucfirst(isset($perfil) && is_string($perfil) && !empty($perfil) ? $perfil : 'Login')) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal min-h-screen flex flex-col">

    <nav class="bg-blue-600 p-4 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-bold">Sistema Supermercado</h1>
            <?php if (isset($autenticacao) && $autenticacao->estaLogado()): ?>
                <div class="flex items-center space-x-4">
                    <span class="text-lg">Olá, <span class="font-semibold"><?= htmlspecialchars(ucfirst($usuario_logado)) ?></span> (<span class="font-semibold"><?= htmlspecialchars($perfil_display) ?></span>)</span>
                    <a href="index.php?logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition-colors flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <main class="flex-1">
        <div class="container mx-auto mt-8 p-4">
            <?php
            // Exibe mensagens flash aqui no layout principal
            echo flash_message('success');
            echo flash_message('error');
            ?>