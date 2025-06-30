<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-2xl font-bold text-blue-600 border-b-2 border-blue-200 pb-2 mb-4">Painel do Estoque</h2>

    <?php
    $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;
    $solicitacao_pendente = isset($solicitacao['solicitacao_pendente']) ? $solicitacao['solicitacao_pendente'] : false;
    ?>
    
    <?php if (!$liberado_estoque): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg mb-6 border border-yellow-200">
            <?php if ($solicitacao_pendente): ?>
                <p class="font-semibold">⌛ Solicitação de acesso já foi enviada. Aguarde a aprovação do Financeiro/Admin.</p>
            <?php else: ?>
                <p class="font-semibold">⚠️ Atenção: Você não pode fazer alterações. </p>
                <form method="post" class="mt-2">
                    <input type="hidden" name="action" value="solicitar_acesso">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Solicitar Acesso</button>
                </form>
            <?php endif; ?>
        </div>
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
                    <?php if ($liberado_estoque): ?>
                        <th class="py-3 px-4 text-left font-semibold flex justify-around items-center">Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-3 px-4"><?= $produto['id'] ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($produto['nome']) ?></td>
                        <td class="py-3 px-4"><?= $produto['quantidade'] ?></td>
                        <td class="py-3 px-4">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        
                        <?php if ($liberado_estoque): ?>
                            <td class="py-3 px-4 flex justify-end gap-4">
                                <form class="flex flex-wrap items-center gap-4" method="post">
                                    <input type="hidden" name="action" value="editar">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" placeholder="Nome" class="border border-gray-300 rounded-md p-1 w-30 text-sm" required>
                                    <input type="number" name="quantidade" value="<?= $produto['quantidade'] ?>" placeholder="Qtd" class="border border-gray-300 rounded-md p-1 w-16 text-sm" required>
                                    <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" placeholder="Preço" class="border border-gray-300 rounded-md p-1 w-20 text-sm" required>
                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded-md transition-colors">Salvar</button>
                                </form>
                                <form class="inline-block mt-2 md:mt-0" method="post" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                    <input type="hidden" name="action" value="deletar">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-md transition-colors">Deletar</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($liberado_estoque): ?>
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Adicionar Novo Produto</h3>
            <form class="flex flex-wrap items-end gap-3" method="post">
                <input type="hidden" name="action" value="adicionar">
                <input type="text" name="nome" placeholder="Nome" required class="flex-1 min-w-40 border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <input type="number" name="quantidade" placeholder="Quantidade" required class="w-24 border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <input type="number" step="0.01" name="preco" placeholder="Preço" required class="w-24 border border-gray-300 p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">Adicionar Produto</button>
            </form>
        </div>
    <?php endif; ?>
</div>