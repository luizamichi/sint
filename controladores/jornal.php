<?php
	/**
	 * Controlador de jornal com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/JornalDAO.php");
	include_once("../modelos/Jornal.php");

	$jornalDAO = new JornalDAO();
	$jornalDAO->setDatabase($database);

	$jornalModelo = new Jornal();
?>