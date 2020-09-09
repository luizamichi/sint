<?php
	/**
	 * Testes unitários do DAO Banner
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	setlocale(LC_CTYPE, "pt_BR.UTF8");
	include_once("../dao/BannerDAO.php");
	include_once("database.php");
	include_once("modelo_banner.php");

	$bannerDAO = new BannerDAO();
	$bannerDAO->setDatabase($database);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "dao_banner.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $bannerDAO;
	}
?>