<?php

class EstoqueController {
    private $autenticacao;
    private $produtosHandler;
    private $solicitacoesHandler;

    public function __construct(Autenticacao $autenticacao, ControleDeDados $produtosHandler, ControleDeDados $solicitacoesHandler) {
        $this->autenticacao = $autenticacao;
        $this->produtosHandler = $produtosHandler;
        $this->solicitacoesHandler = $solicitacoesHandler;
    }

    public function handleRequest($action = null) {
        // Acesso restrito
        if (!$this->autenticacao->estaLogado() || $this->autenticacao->obterPerfil() !== 'estoque') {
            header('Location: index.php');
            exit();
        }

        // Processa ações POST específicas do Estoque
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($action) {
                case 'adicionar':
                    $this->adicionarProduto(
                        isset($_POST['nome']) ? $_POST['nome'] : null,
                        isset($_POST['quantidade']) ? $_POST['quantidade'] : null,
                        isset($_POST['preco']) ? $_POST['preco'] : null
                    );
                    break;
                case 'editar':
                    $this->editarProduto(
                        isset($_POST['id']) ? $_POST['id'] : null,
                        isset($_POST['nome']) ? $_POST['nome'] : null,
                        isset($_POST['quantidade']) ? $_POST['quantidade'] : null,
                        isset($_POST['preco']) ? $_POST['preco'] : null
                    );
                    break;
                case 'deletar':
                    $this->deletarProduto(isset($_POST['id']) ? $_POST['id'] : null);
                    break;
                case 'solicitar_acesso':
                    $this->solicitarAcesso();
                    break;
            }
            header('Location: index.php');
            exit();
        }

        // Renderiza a página do Estoque
        $this->renderEstoquePage();
    }

    private function adicionarProduto($nome, $quantidade, $preco) {
        $solicitacao = $this->solicitacoesHandler->ler();
        // Usando a sintaxe compatível com PHP 5.x
        $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;

        if (!$liberado_estoque) {
            flash_message('error', 'Acesso negado: Você não tem permissão para adicionar produtos.');
            return;
        }

        if (empty($nome) || !is_numeric($quantidade) || !is_numeric($preco)) {
            flash_message('error', 'Erro: Dados inválidos para adicionar produto.');
            return;
        }

        $produtos = $this->produtosHandler->ler();
        $ultimoProduto = end($produtos);
        $novoId = !empty($ultimoProduto) ? $ultimoProduto['id'] + 1 : 1;
        $produtos[] = [
            'id' => $novoId,
            'nome' => htmlspecialchars($nome),
            'quantidade' => (int)$quantidade,
            'preco' => (float)$preco
        ];
        $this->produtosHandler->escrever($produtos);
        flash_message('success', 'Produto adicionado com sucesso!');
    }

    private function editarProduto($id, $nome, $quantidade, $preco) {
        $solicitacao = $this->solicitacoesHandler->ler();
        // Usando a sintaxe compatível com PHP 5.x
        $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;

        if (!$liberado_estoque) {
            flash_message('error', 'Acesso negado: Você não tem permissão para editar produtos.');
            return;
        }

        if (empty($id) || empty($nome) || !is_numeric($quantidade) || !is_numeric($preco)) {
            flash_message('error', 'Erro: Dados inválidos para editar produto.');
            return;
        }

        $produtos = $this->produtosHandler->ler();
        foreach ($produtos as &$p) {
            if ($p['id'] == $id) {
                $p['nome'] = htmlspecialchars($nome);
                $p['quantidade'] = (int)$quantidade;
                $p['preco'] = (float)$preco;
                $this->produtosHandler->escrever($produtos);
                flash_message('success', 'Produto atualizado com sucesso!');
                return;
            }
        }
        flash_message('error', 'Erro: Produto não encontrado para edição.');
    }

    private function deletarProduto($id) {
        $solicitacao = $this->solicitacoesHandler->ler();
        // Usando a sintaxe compatível com PHP 5.x
        $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;

        if (!$liberado_estoque) {
            flash_message('error', 'Acesso negado: Você não tem permissão para deletar produtos.');
            return;
        }

        if (empty($id)) {
            flash_message('error', 'Erro: ID do produto não fornecido para deleção.');
            return;
        }

        $produtos = $this->produtosHandler->ler();
        $produtos_filtrados = array_filter($produtos, function($p) use ($id) { return $p['id'] != $id; });
        if (count($produtos_filtrados) < count($produtos)) {
            $this->produtosHandler->escrever(array_values($produtos_filtrados)); // Reindexa o array
            flash_message('success', 'Produto deletado com sucesso!');
        } else {
            flash_message('error', 'Erro: Produto não encontrado para deleção.');
        }
    }

    private function solicitarAcesso() {
        $solicitacao = $this->solicitacoesHandler->ler();
        $solicitacao['solicitacao_pendente'] = true;
        $this->solicitacoesHandler->escrever($solicitacao);
        flash_message('success', 'Solicitação de acesso enviada para o Financeiro/Admin.');
    }

    private function renderEstoquePage() {
        // Prepara os dados que serão exibidos na View
        $produtos = $this->produtosHandler->ler();
        $solicitacao = $this->solicitacoesHandler->ler();
        // Usando a sintaxe compatível com PHP 5.x
        $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;
        $solicitacao_pendente = isset($solicitacao['solicitacao_pendente']) ? $solicitacao['solicitacao_pendente'] : false;

        // --- NOVO: Torna as variáveis $autenticacao e $perfil disponíveis para o header.php ---
        $autenticacao = $this->autenticacao;
        $perfil = $autenticacao->obterPerfil();
        // --- FIM NOVO ---

        // Inclui as partes da página
        require_once __DIR__ . '/../Views/header.php';
        require_once __DIR__ . '/../Views/estoque.php';
        require_once __DIR__ . '/../Views/footer.php';
    }
}