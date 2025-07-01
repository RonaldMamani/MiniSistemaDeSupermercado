<?php
$pode_gerenciar = ($liberado_estoque || (isset($admin_override_estoque_permission) && $admin_override_estoque_permission));
?>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-2xl font-bold text-blue-600 border-b-2 border-blue-200 pb-2 mb-4">Painel do Estoque</h2>
    <p class="text-gray-700 mb-6">Gerencie os produtos do estoque aqui.</p>

    <?php if ($perfil === 'estoque'):?>
        <?php if (!$pode_gerenciar): ?>
            <div class="p-4 mb-4 rounded-lg text-center font-semibold
                <?php if ($solicitacao_pendente): ?>
                    bg-yellow-100 text-yellow-800
                <?php else: ?>
                    bg-red-100 text-red-800
                <?php endif; ?>">
                <?php if ($solicitacao_pendente): ?>
                    Seu acesso para gerenciar o estoque está sob análise. Por favor, aguarde a aprovação do Financeiro/Admin.
                <?php else: ?>
                    Seu acesso para gerenciar o estoque está bloqueado.
                    <form action="index.php" method="post" class="mt-2">
                        <input type="hidden" name="action" value="solicitar_acesso">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded-md transition-colors">
                            Solicitar Acesso
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="p-4 mb-4 rounded-lg text-center font-semibold bg-green-100 text-green-800">
                Seu acesso para gerenciar o estoque está liberado!
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <h3 class="text-xl font-semibold mb-4">Lista de Produtos</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-3 px-4 text-left font-semibold">ID</th>
                    <th class="py-3 px-4 text-left font-semibold">Nome</th>
                    <th class="py-3 px-4 text-left font-semibold">Quantidade</th>
                    <th class="py-3 px-4 text-left font-semibold">Preço</th>
                    <?php if ($pode_gerenciar):?>
                        <th class="py-3 px-4 text-left font-semibold">Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="<?= $pode_gerenciar ? '5' : '4' ?>" class="py-4 text-center text-gray-500">Nenhum produto cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="py-3 px-4"><?= $produto['id'] ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($produto['nome']) ?></td>
                            <td class="py-3 px-4"><?= $produto['quantidade'] ?></td>
                            <td class="py-3 px-4">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <?php if ($pode_gerenciar): ?>
                                <td class="py-3 px-4 flex gap-2">
                                    <button onclick="openEditModal(<?= $produto['id'] ?>, '<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['quantidade'] ?>, <?= $produto['preco'] ?>)"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded-md transition-colors">
                                        Editar
                                    </button>
                                    <form class="inline-block" action="index.php" method="post" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                        <input type="hidden" name="action" value="deletar">
                                        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-md transition-colors">
                                            Deletar
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pode_gerenciar): ?>
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Adicionar Novo Produto</h3>
            <form action="index.php" method="post" class="bg-gray-50 p-4 rounded-lg shadow-inner">
                <input type="hidden" name="action" value="adicionar">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome do Produto:</label>
                        <input type="text" id="nome" name="nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div>
                        <label for="quantidade" class="block text-gray-700 text-sm font-bold mb-2">Quantidade:</label>
                        <input type="number" id="quantidade" name="quantidade" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                    </div>
                    <div>
                        <label for="preco" class="block text-gray-700 text-sm font-bold mb-2">Preço (R$):</label>
                        <input type="number" step="0.01" id="preco" name="preco" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md transition-colors">
                    Adicionar Produto
                </button>
            </form>
        </div>

        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 class="text-2xl font-bold text-blue-600 border-b-2 border-blue-200 pb-2 mb-4">Editar Produto</h3>
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="editar">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-4">
                        <label for="edit-nome" class="block text-gray-700 text-sm font-bold mb-2">Nome do Produto:</label>
                        <input type="text" id="edit-nome" name="nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit-quantidade" class="block text-gray-700 text-sm font-bold mb-2">Quantidade:</label>
                        <input type="number" id="edit-quantidade" name="quantidade" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                    </div>
                    <div class="mb-6">
                        <label for="edit-preco" class="block text-gray-700 text-sm font-bold mb-2">Preço (R$):</label>
                        <input type="number" step="0.01" id="edit-preco" name="preco" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required min="0">
                    </div>
                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="closeEditModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md transition-colors">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openEditModal(id, nome, quantidade, preco) {
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-nome').value = nome;
                document.getElementById('edit-quantidade').value = quantidade;
                document.getElementById('edit-preco').value = preco;
                document.getElementById('editModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            // Fechar modal ao clicar fora
            document.getElementById('editModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeEditModal();
                }
            });
        </script>
    <?php endif; ?>
</div>