<?php
	/**
	 * Controlador de banner com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/BannerDAO.php");
	include_once("../modelos/Banner.php");

	$bannerDAO = new BannerDAO();
	$bannerDAO->setDatabase($database);

	$bannerModelo = new Banner();
?>