<?php

class FinanceiroController {
    private $autenticacao;
    private $solicitacoesHandler;

    public function __construct(Autenticacao $autenticacao, ControleDeDados $solicitacoesHandler) {
        $this->autenticacao = $autenticacao;
        $this->solicitacoesHandler = $solicitacoesHandler;
    }

    public function handleRequest($action = null) {
        if (!$this->autenticacao->estaLogado() || ($this->autenticacao->obterPerfil() !== 'financeiro' && $this->autenticacao->obterPerfil() !== 'admin')) {
            header('Location: index.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($action) {
                case 'aprovar_acesso':
                    $this->aprovarAcesso();
                    break;
                case 'bloquear_acesso':
                    $this->bloquearAcesso();
                    break;
            }
            header('Location: index.php');
            exit();
        }

        $this->renderFinanceiroPage();
    }

    private function aprovarAcesso() {
        $solicitacao = $this->solicitacoesHandler->ler();
        $solicitacao['Liberado_estoque'] = true;
        $solicitacao['solicitacao_pendente'] = false;
        $this->solicitacoesHandler->escrever($solicitacao);
        flash_message('success', 'Acesso ao estoque aprovado!');
    }

    private function bloquearAcesso() {
        $solicitacao = $this->solicitacoesHandler->ler();
        $solicitacao['Liberado_estoque'] = false;
        $solicitacao['solicitacao_pendente'] = false;
        $this->solicitacoesHandler->escrever($solicitacao);
        flash_message('success', 'Acesso ao estoque bloqueado!');
    }

    private function renderFinanceiroPage() {
        
        $solicitacao = $this->solicitacoesHandler->ler();

        $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;
        $solicitacao_pendente = isset($solicitacao['solicitacao_pendente']) ? $solicitacao['solicitacao_pendente'] : false;

        $autenticacao = $this->autenticacao;
        $perfil = $autenticacao->obterPerfil();

        require_once __DIR__ . '/../Views/header.php';
        require_once __DIR__ . '/../Views/financeiro.php';
        require_once __DIR__ . '/../Views/footer.php';
    }
}