<?php
	/**
	 * Testes unitários do DAO Imagem
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/ImagemDAO.php");
	include_once("database.php");
	include_once("vetor_imagem.php");

	$imagemDAO = new ImagemDAO();
	$imagemDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_imagem.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $imagemDAO;
	}
?>