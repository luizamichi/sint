<?php
	/**
	 * Testes unitários do DAO Notícia
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/NoticiaDAO.php");
	include_once("database.php");
	include_once("modelo_noticia.php");

	$noticiaDAO = new NoticiaDAO();
	$noticiaDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_noticia.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $noticiaDAO;
	}
?>