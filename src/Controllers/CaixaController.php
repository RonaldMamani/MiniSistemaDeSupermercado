<?php

class CaixaController {
    private $autenticacao;
    private $produtosHandler;

    public function __construct(Autenticacao $autenticacao, ControleDeDados $produtosHandler) {
        $this->autenticacao = $autenticacao;
        $this->produtosHandler = $produtosHandler;
    }

    public function handleRequest($action = null) {

        if (!$this->autenticacao->estaLogado() || $this->autenticacao->obterPerfil() !== 'caixa') {
            header('Location: index.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($action) {
                case 'vender':
                    $this->venderProduto(
                        isset($_POST['id']) ? $_POST['id'] : null,
                        isset($_POST['quantidade_venda']) ? $_POST['quantidade_venda'] : 1
                    );
                    break;
            }

            header('Location: index.php');
            exit();
        }

        $this->renderCaixaPage();
    }

    private function venderProduto($id_venda, $quantidade_venda) {
        if (empty($id_venda) || !is_numeric($quantidade_venda) || $quantidade_venda <= 0) {
            flash_message('error', 'Erro: ID do produto ou quantidade inválida para venda.');
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

        if ($indice_produto !== -1) {

            if ($produtos[$indice_produto]['quantidade'] >= $quantidade_venda) {
                $produtos[$indice_produto]['quantidade'] -= $quantidade_venda;
                if ($produtos[$indice_produto]['quantidade'] <= 0) {
                    unset($produtos[$indice_produto]);
                    $produtos = array_values($produtos);
                }
                $this->produtosHandler->escrever($produtos);
                flash_message('success', htmlspecialchars($quantidade_venda) . ' unidades de ' . htmlspecialchars($produto_vendido_nome) . ' vendidas com sucesso!');
            } else {
                flash_message('error', 'Erro: Quantidade insuficiente em estoque para ' . htmlspecialchars($produto_vendido_nome) . '. Disponível: ' . $produtos[$indice_produto]['quantidade'] . '.');
            }
        } else {
            flash_message('error', 'Erro: Produto não encontrado.');
        }
    }

    private function renderCaixaPage() {
        $produtos = $this->produtosHandler->ler();

        $autenticacao = $this->autenticacao;
        $perfil = $autenticacao->obterPerfil();

        require_once __DIR__ . '/../Views/header.php';
        require_once __DIR__ . '/../Views/caixa.php';
        require_once __DIR__ . '/../Views/footer.php';
    }
}