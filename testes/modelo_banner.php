<?php
	/**
	 * Testes unitários do modelo Banner
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Banner.php");
	include_once("modelo_usuario.php");

	$bannerModelo = new Banner();
	$bannerModelo->setId(1);
	$bannerModelo->hashImagem("banner1.png"); // $bannerModelo->setImagem("uploads/banners/banner1.png");
	$bannerModelo->setUsuario($usuarioModelo);
	$bannerModelo->setData("2018-01-01 01:01:01");
	$bannerModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_banner.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $bannerModelo;
	}
?>