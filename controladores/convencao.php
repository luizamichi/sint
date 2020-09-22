<?php
	/**
	 * Controlador de convenção com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/ConvencaoDAO.php");
	include_once("../modelos/Convencao.php");

	$convencaoDAO = new ConvencaoDAO();
	$convencaoDAO->setDatabase($database);

	$convencaoModelo = new Convencao();
?>