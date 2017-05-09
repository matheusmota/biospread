<?php
// Inclui o arquivo com a classe de login
require_once("class/User.class.php");

// Instancia a classe
$userClass = new User();

// Verifica se n�o h� um usu�rio logado
if ( $userClass->usuarioLogado() === false ) {
	// N�o h� um usu�rio logado, redireciona pra tela de login
	header("Location: login.php");
	exit;
}
?>