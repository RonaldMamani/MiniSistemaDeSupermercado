<?php

$status_acesso_class = 'bg-gray-100 text-gray-800';
$status_acesso_text = 'Status Desconhecido';

if ($liberado_estoque) {
    $status_acesso_class = 'bg-green-100 text-green-800';
    $status_acesso_text = 'Acesso do Estoque: LIBERADO';
} elseif ($solicitacao_pendente) {
    $status_acesso_class = 'bg-yellow-100 text-yellow-800';
    $status_acesso_text = 'Acesso do Estoque: SOLICITAÇÃO PENDENTE';
} else {
    $status_acesso_class = 'bg-red-100 text-red-800';
    $status_acesso_text = 'Acesso do Estoque: BLOQUEADO';
}
?>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-2xl font-bold text-blue-600 border-b-2 border-blue-200 pb-2 mb-4">Painel do Financeiro</h2>
    <p class="text-gray-700 mb-6">Gerencie o acesso do estoque ao sistema.</p>

    <div class="p-4 mb-6 rounded-lg text-center font-semibold <?= $status_acesso_class ?>">
        <?= $status_acesso_text ?>
    </div>

    <h3 class="text-xl font-semibold mb-4">Ações de Controle de Acesso</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if (!$liberado_estoque && $solicitacao_pendente): ?>
            <div class="bg-yellow-50 p-4 rounded-lg shadow-inner flex flex-col items-center">
                <p class="text-yellow-800 font-semibold mb-4 text-center">Há uma solicitação de acesso pendente do Estoque.</p>
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="aprovar_acesso">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md transition-colors">
                        Aprovar Acesso
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($liberado_estoque): ?>
            <div class="bg-green-50 p-4 rounded-lg shadow-inner flex flex-col items-center">
                <p class="text-green-800 font-semibold mb-4 text-center">O acesso do Estoque está liberado atualmente.</p>
                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="bloquear_acesso">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition-colors">
                        Bloquear Acesso
                    </button>
                </form>
            </div>
        <?php else: // Se não estiver liberado, pode estar bloqueado sem solicitação ou com solicitação pendente ?>
            <?php if (!$solicitacao_pendente): // Apenas exibe o botão de bloquear se já não estiver bloqueado E não houver solicitação pendente ?>
                <div class="bg-red-50 p-4 rounded-lg shadow-inner flex flex-col items-center">
                    <p class="text-red-800 font-semibold mb-4 text-center">O acesso do Estoque está bloqueado.</p>
                    <p class="text-sm text-gray-500">Aguardando solicitação ou decisão.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>