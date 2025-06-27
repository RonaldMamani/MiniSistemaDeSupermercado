<!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Supermercado - <?= ucfirst($perfil) ?></title>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    </head>
    <body class="min-h-screen flex flex-col">
        <header class="bg-white shadow-lg p-6 flex justify-between items-center">
            <h3 class="text-3xl font-bold text-blue-700">Painel do Supermercado</h3>
            <div class="flex items-center space-x-4">
                <p class="text-gray-600">
                    Usuário: <strong class="text-blue-800"><?= htmlspecialchars($_SESSION['usuario']) ?></strong>
                </p>
                <a href="?logout" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Sair</a>
            </div>
        </header>
        <main class="container mx-auto p-6 flex-grow">