<?php

class AdminController {
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
        if (!$this->autenticacao->estaLogado() || $this->autenticacao->obterPerfil() !== 'admin') {
            header('Location: index.php');
            exit();
        }

        // Processa ações POST específicas do Admin
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($action) {
                case 'adicionar':
                    $this->produtosHandler->adicionarProduto(
                        isset($_POST['nome']) ? $_POST['nome'] : null,
                        isset($_POST['quantidade']) ? $_POST['quantidade'] : null,
                        isset($_POST['preco']) ? $_POST['preco'] : null
                    );
                    break;
                case 'editar':
                    $this->produtosHandler->editarProduto(
                        isset($_POST['id']) ? $_POST['id'] : null,
                        isset($_POST['nome']) ? $_POST['nome'] : null,
                        isset($_POST['quantidade']) ? $_POST['quantidade'] : null,
                        isset($_POST['preco']) ? $_POST['preco'] : null
                    );
                    break;
                case 'deletar':
                    $this->produtosHandler->deletarProduto(isset($_POST['id']) ? $_POST['id'] : null);
                    break;
                case 'solicitar_acesso':
                    $this->solicitarAcesso();
                    break;
                case 'liberar_acesso':
                    $this->solicitarAcesso();
                    break;
                case 'aprovar_acesso':
                    $this->aprovarAcesso();
                    break;
                case 'bloquear_acesso':
                    $this->bloquearAcesso();
            }
            header('Location: index.php');
            exit();
        }

        // Renderiza a página do Admin
        $this->renderAdminPage();
    }

    private function adicionarProduto($nome, $quantidade, $preco) {
        if (empty($nome) || empty($quantidade) || empty($preco)) {
            flash_message('error', 'Erro: Todos os campos são obrigatórios para adicionar um produto.');
            return;
        }

        $produtos = $this->produtosHandler->ler();
        $ultimo_id = end($produtos)['id'];
        $novoId = !empty($ultimo_id) ? $ultimo_id + 1 : 1;
        $novo_produto = [
            'id' => count($produtos) + 1,
            'nome' => $nome,
            'quantidade' => (int)$quantidade,
            'preco' => (float)$preco
        ];
        $this->produtosHandler->escrever($produtos);
        flash_message('success', 'Produto adicionado com sucesso!');
    }

    private function editarProduto($id, $nome, $quantidade, $preco) {
        if (empty($id) || empty($nome) || empty($quantidade) || empty($preco)) {
            flash_message('error', 'Erro: Todos os campos são obrigatórios para editar um produto.');
            return;
        }

        $produtos = $this->produtosHandler->ler();
        foreach ($produtos as &$produto) {
            if ($produto['id'] == $id) {
                $produto['nome'] = $nome;
                $produto['quantidade'] = (int)$quantidade;
                $produto['preco'] = (float)$preco;
                $this->produtosHandler->escrever($produtos);
                flash_message('success', 'Produto atualizado com sucesso!');
                return;
            }
        }
        flash_message('error', 'Erro: Produto não encontrado para edição.');
    }

    private function deletarProduto($id) {
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
        $solicitacoes = $this->solicitacoesHandler->ler();
        if (empty($solicitacoes)) {
            $solicitacoes = [];
        }
        $solicitacoes['solicitacao_pendente'] = true;
        $this->solicitacoesHandler->escrever($solicitacoes);
        flash_message('success', 'Solicitação de acesso enviada com sucesso!');
    }

    private function aprovarAcesso() {
        $solicitacoes = $this->solicitacoesHandler->ler();
        if (empty($solicitacoes)) {
            flash_message('error', 'Erro: Nenhuma solicitação pendente encontrada.');
            return;
        }
        $solicitacoes['Liberado_estoque'] = true;
        $solicitacoes['solicitacao_pendente'] = false;
        $this->solicitacoesHandler->escrever($solicitacoes);
        flash_message('success', 'Acesso ao estoque aprovado!');
    }

    private function bloquearAcesso() {
        $solicitacoes = $this->solicitacoesHandler->ler();
        if (empty($solicitacoes)) {
            flash_message('error', 'Erro: Nenhuma solicitação pendente encontrada.');
            return;
        }
        $solicitacoes['Liberado_estoque'] = false;
        $solicitacoes['solicitacao_pendente'] = true;
        $this->solicitacoesHandler->escrever($solicitacoes);
        flash_message('success', 'Acesso ao estoque bloqueado!');
    }

    private function renderAdminPage() {
        $produtos = $this->produtosHandler->ler();
        $solicitacao = $this->solicitacoesHandler->ler();

        $liberado_estoque = isset($solicitacao['Liberado_estoque']) ? $solicitacao['Liberado_estoque'] : false;
        $solicitacao_pendente = isset($solicitacao['solicitacao_pendente']) ? $solicitacao['solicitacao_pendente'] : false;

        $autenticacao = $this->autenticacao;
        $perfil = $autenticacao->obterPerfil();

        // Inclui os arquivos de visualização necessários
        require_once __DIR__ . '/../Views/header.php';
        require_once __DIR__ . '/../Views/admin.php';
        require_once __DIR__ . '/../Views/footer.php';
    }
}