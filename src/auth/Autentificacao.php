<?php
    class Autenticacao {

        private $usuarios = [
            'caixa' => ['senha' => '123', 'perfil' => 'caixa'],
            'estoque' => ['senha' => '123456', 'perfil' => 'estoque'],
            'admin' => ['senha' => '123456789', 'perfil' => 'admin'],
            'financeiro' => ['senha' => '123456789', 'perfil' => 'financeiro'],
        ];

        public function entrar($usuario, $senha) {
            if (isset($this->usuarios[$usuario]) && $this->usuarios[$usuario]['senha'] === $senha) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['perfil'] = $this->usuarios[$usuario]['perfil'];
                return true;
            }
            return false;
        }

        public function sair() {
            session_destroy();
        }

        public function estaLogado() {
            return isset($_SESSION['perfil']);
        }

        public function obterPerfil() {
            return isset($_SESSION['perfil']) ? $_SESSION['perfil'] : null;
        }
    }

?>