<?php
	/**
	 * Testes unitários do DAO Jornal
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/JornalDAO.php");
	include_once("database.php");
	include_once("modelo_jornal.php");

	$jornalDAO = new JornalDAO();
	$jornalDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_jornal.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $jornalDAO;
	}
?>