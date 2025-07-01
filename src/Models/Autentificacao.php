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
                $_SESSION['usuario_logado'] = $usuario;
                $_SESSION['perfil_usuario'] = $this->usuarios[$usuario]['perfil'];
                return true;
            }
            return false;
        }

        public function sair() {
            session_start();
            session_destroy();
        }

        public function estaLogado() {
            return isset($_SESSION['usuario_logado']);
        }

        public function obterPerfil() {
            return isset($_SESSION['perfil_usuario']) ? $_SESSION['perfil_usuario'] : null;
        }

        public function obterUsuario() {
            return isset($_SESSION['usuario_logado']) ? $_SESSION['usuario_logado'] : null;
        }
    }

?>