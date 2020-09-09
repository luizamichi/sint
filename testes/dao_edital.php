<?php
	/**
	 * Testes unitários do DAO Edital
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/EditalDAO.php");
	include_once("database.php");
	include_once("modelo_edital.php");

	$editalDAO = new EditalDAO();
	$editalDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_edital.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $editalDAO;
	}
?>