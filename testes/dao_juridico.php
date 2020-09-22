<?php
	/**
	 * Testes unitários do DAO Jurídico
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/JuridicoDAO.php");
	include_once("database.php");
	include_once("modelo_juridico.php");

	$juridicoDAO = new JuridicoDAO();
	$juridicoDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_juridico.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $juridicoDAO;
	}
?>