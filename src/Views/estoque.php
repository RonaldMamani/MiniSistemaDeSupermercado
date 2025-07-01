<div>
    <h2>Painel do Estoque</h2>

    <?php
    $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;
    $solicitacao_pendente = isset($solicitacao['solicitacao_pendente']) ? $solicitacao['solicitacao_pendente'] : false;
    ?>
    
    <?php if (!$liberado_estoque): ?>
        <div>
            <?php if ($solicitacao_pendente): ?>
                <p>⌛ Solicitação de acesso já foi enviada. Aguarde a aprovação do Financeiro/Admin.</p>
            <?php else: ?>
                <p>⚠️ Atenção: Você não pode fazer alterações. </p>
                <form method="post" class="mt-2">
                    <input type="hidden" name="action" value="solicitar_acesso">
                    <button type="submit">Solicitar Acesso</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h3>Lista de Produtos</h3>
    <div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <?php if ($liberado_estoque): ?>
                        <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= $produto['id'] ?></td>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td><?= $produto['quantidade'] ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        
                        <?php if ($liberado_estoque): ?>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="action" value="editar">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" placeholder="Nome" required>
                                    <input type="number" name="quantidade" value="<?= $produto['quantidade'] ?>" placeholder="Qtd" required>
                                    <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" placeholder="Preço" required>
                                    <button type="submit">Salvar</button>
                                </form>
                                <form method="post" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                    <input type="hidden" name="action" value="deletar">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <button type="submit">Deletar</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($liberado_estoque): ?>
        <div>
            <h3>Adicionar Novo Produto</h3>
            <form method="post">
                <input type="hidden" name="action" value="adicionar">
                <input type="text" name="nome" placeholder="Nome" required>
                <input type="number" name="quantidade" placeholder="Quantidade" required>
                <input type="number" step="0.01" name="preco" placeholder="Preço" required>
                <button type="submit" >Adicionar Produto</button>
            </form>
        </div>
    <?php endif; ?>
</div>