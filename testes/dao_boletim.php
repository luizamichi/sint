<?php
	/**
	 * Testes unitários do DAO Boletim
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/BoletimDAO.php");
	include_once("database.php");
	include_once("modelo_boletim.php");

	$boletimDAO = new BoletimDAO();
	$boletimDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_boletim.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $boletimDAO;
	}
?>