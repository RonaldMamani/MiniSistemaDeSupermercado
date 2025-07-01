<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-2xl font-bold text-blue-600 border-b-2 border-blue-200 pb-2 mb-4">Painel do Caixa</h2>
    <p class="text-gray-700 mb-6">Aqui você pode registrar vendas e visualizar produtos.</p>

    <h3 class="text-xl font-semibold mb-4">Lista de Produtos</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-3 px-4 text-left font-semibold">ID</th>
                    <th class="py-3 px-4 text-left font-semibold">Nome</th>
                    <th class="py-3 px-4 text-left font-semibold">Quantidade em Estoque</th>
                    <th class="py-3 px-4 text-left font-semibold">Preço Unitário</th>
                    <th class="py-3 px-4 text-left font-semibold">Quantidade para Vender</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">Nenhum produto disponível.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="py-3 px-4"><?= $produto['id'] ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($produto['nome']) ?></td>
                            <td class="py-3 px-4"><?= $produto['quantidade'] ?></td>
                            <td class="py-3 px-4">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                            <td class="py-3 px-4">
                                <form class="flex items-center gap-2" action="index.php" method="post">
                                    <input type="hidden" name="action" value="vender">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <input type="number" name="quantidade_venda" value="1" min="1" max="<?= $produto['quantidade'] ?>"
                                           class="w-20 border rounded py-1 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           <?= ($produto['quantidade'] <= 0) ? 'disabled' : '' ?>>
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded-md transition-colors"
                                            <?= ($produto['quantidade'] <= 0) ? 'disabled' : '' ?>>
                                        Vender
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>