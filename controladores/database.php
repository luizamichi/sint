<?php
	/**
	 * Controlador da base de dados
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	date_default_timezone_set("America/Sao_Paulo");
	ini_set("display_errors", "1");
	ini_set("display_startup_errors", "1");
	error_reporting(E_ALL);
	include_once("../modelos/Database.php");

	$database = new Database();
	$database->setHost("sinteemar.com.br");
	$database->setUsuario("sint");
	$database->setSenha("Senha do banco de dados");
	$database->setDatabase("sint2020");
	if(!$database->startConexao()) {
		header("Location: ../manutencao.html");
		return FALSE;
	}
?>