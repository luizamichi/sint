<?php
	/**
	 * Controlador de boletim com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/BoletimDAO.php");
	include_once("../modelos/Boletim.php");

	$boletimDAO = new BoletimDAO();
	$boletimDAO->setDatabase($database);

	$boletimModelo = new Boletim();
?>