<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Supermercado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-300 font-sans leading-normal tracking-normal flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Login</h2>

        <?php if (!empty($erro_login)): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded-md mb-4 text-center">
                <?= htmlspecialchars($erro_login) ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="post">
            <div class="mb-4">
                <label for="usuario" class="block text-gray-700 text-sm font-bold mb-2">Usuário:</label>
                <input type="text" id="usuario" name="usuario" 
                    class="
                        shadow appearance-none border-2 border-b-blue-300 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight 
                        focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="senha" class="block text-gray-700 text-sm font-bold mb-2">Senha:</label>
                <input type="password" id="senha" name="senha" 
                    class="
                        shadow appearance-none border-2 border-b-blue-300 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight 
                        focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" name="login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Entrar
                </button>
            </div>
        </form>
    </div>

</body>
</html>