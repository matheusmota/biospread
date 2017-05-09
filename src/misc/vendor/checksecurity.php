<?php
// Inclui o arquivo com a classe de login
require_once("class/User.class.php");

// Instancia a classe
$userClass = new User();

// Verifica se não há um usuário logado
if ( $userClass->usuarioLogado() === false ) {
	// Não há um usuário logado, redireciona pra tela de login
	header("Location: login.php");
	exit;
}
?>