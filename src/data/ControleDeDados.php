<?php

    class ControleDeDados {

        private $filePath;

        public function __construct($filePath) {
            $this->filePath = $filePath;
            if (!file_exists($this->filePath)) {
                file_put_contents($this->filePath, json_encode([], JSON_UNESCAPED_UNICODE));
            }
        }

        public function ler() {
            $conteudo = file_get_contents($this->filePath);
            return json_decode($conteudo, true);
        }

        public function escrever($dados) {
            file_put_contents($this->filePath, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

