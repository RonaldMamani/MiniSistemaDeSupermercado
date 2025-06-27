<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Supermercado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-lg shadow-xl max-w-sm w-full">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Login</h2>
        <?php if (isset($erro_login) && $erro_login): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded-lg mb-4 border border-red-200">
                <?= $erro_login ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-4">
                <label for="usuario" class="block text-gray-700 font-semibold mb-2">Usuário</label>
                <input type="text" id="usuario" name="usuario" placeholder="Usuário" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-6">
                <label for="senha" class="block text-gray-700 font-semibold mb-2">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Senha" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors">Entrar</button>
        </form>
    </div>
</body>
</html>