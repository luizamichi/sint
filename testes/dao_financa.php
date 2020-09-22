<?php
	/**
	 * Testes unitários do DAO Finança
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/FinancaDAO.php");
	include_once("database.php");
	include_once("modelo_financa.php");

	$financaDAO = new FinancaDAO();
	$financaDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_financa.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $financaDAO;
	}
?>