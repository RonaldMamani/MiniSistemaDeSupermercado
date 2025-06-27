<?php
session_start();

$autenticacao = new Autenticacao();

$perfil = $autenticacao->obterPerfil();

require 'src/views/login.php';
