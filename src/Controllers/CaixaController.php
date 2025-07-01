<?php

class CaixaController {
    private $autenticacao;
    private $produtosHandler;

    public function __construct(Autenticacao $autenticacao, ControleDeDados $produtosHandler) {
        $this->autenticacao = $autenticacao;
        $this->produtosHandler = $produtosHandler;
    }

    // Método principal para lidar com requisições GET e POST para o Caixa
    public function handleRequest($action = null) {
        // Acesso restrito: Garante que apenas o perfil 'caixa'
        if (!$this->autenticacao->estaLogado() || $this->autenticacao->obterPerfil() !== 'caixa') {
            header('Location: index.php');
            exit();
        }

        // Processa ações POST específicas do Caixa
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($action) {
                case 'vender':
                    $this->venderProduto(isset($_POST['id']) ? $_POST['id'] : null);
                    break;
            }
            header('Location: index.php');
            exit();
        }
        // Renderiza a página do Caixa
        $this->renderCaixaPage();
    }

    private function venderProduto($id_venda) {
        if (empty($id_venda)) {
            flash_message('error', 'Erro: ID do produto não fornecido para venda.');
            return;
        }

        $produtos = $this->produtosHandler->ler();
        $produto_vendido_nome = '';
        $indice_produto = -1;

        foreach ($produtos as $key => $produto) {
            if ($produto['id'] === (int)$id_venda) {
                $indice_produto = $key;
                $produto_vendido_nome = $produto['nome'];
                break;
            }
        }

        if ($indice_produto !== -1 && $produtos[$indice_produto]['quantidade'] > 0) {
            $produtos[$indice_produto]['quantidade']--;
            if ($produtos[$indice_produto]['quantidade'] <= 0) {
                unset($produtos[$indice_produto]);
                $produtos = array_values($produtos);
            }
            $this->produtosHandler->escrever($produtos);
            flash_message('success', 'Produto "' . htmlspecialchars($produto_vendido_nome) . '" vendido com sucesso!');
        } else {
            flash_message('error', 'Erro: Produto fora de estoque ou não encontrado.');
        }
    }

    private function renderCaixaPage() {
        $produtos = $this->produtosHandler->ler();

        $autenticacao = $this->autenticacao;
        $perfil = $autenticacao->obterPerfil();

        // Inclui os arquivos de visualização necessários
        require_once __DIR__ . '/../Views/header.php';
        require_once __DIR__ . '/../Views/caixa.php';
        require_once __DIR__ . '/../Views/footer.php';
    }
}