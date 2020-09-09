<?php
	/**
	 * Testes unitários do DAO Evento
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/EventoDAO.php");
	include_once("database.php");
	include_once("modelo_evento.php");

	$eventoDAO = new EventoDAO();
	$eventoDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_evento.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $eventoDAO;
	}
?>