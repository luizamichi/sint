<?php
	/**
	 * Testes unitários do modelo Database
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Database.php");

	$database = new Database();
	$database->setHost("sinteemar.com.br");
	$database->setPorta(3306);
	$database->setUsuario("sint");
	$database->setSenha("Senha do banco de dados");
	$database->setDatabase("sint2020");
	$database->startConexao();

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "database.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $database;
	}
?>