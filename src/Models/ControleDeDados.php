<?php

    class ControleDeDados {

        private $caminhoArquivo;

        public function __construct($caminhoArquivo) {
            $this->caminhoArquivo = $caminhoArquivo;
            if (!file_exists($this->caminhoArquivo)) {
                file_put_contents($this->caminhoArquivo, json_encode([], JSON_UNESCAPED_UNICODE));
            }
        }

        public function ler() {
            if (!file_exists($this->caminhoArquivo)) {
                return [];
            }
            $conteudo = file_get_contents($this->caminhoArquivo);
            $dados = json_decode($conteudo, true);
            return $dados !== null ? $dados : [];
        }

        public function escrever(array $dados) {
            $diretorio = dirname($this->caminhoArquivo);
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0777, true);
            }
            file_put_contents($this->caminhoArquivo, json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

