<?php
	/**
	 * Testes unitários do DAO Convênio
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/ConvenioDAO.php");
	include_once("database.php");
	include_once("modelo_convenio.php");

	$convenioDAO = new ConvenioDAO();
	$convenioDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_convenio.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $convenioDAO;
	}
?>