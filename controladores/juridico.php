<?php
	/**
	 * Controlador de jurídico com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/JuridicoDAO.php");
	include_once("../modelos/Juridico.php");

	$juridicoDAO = new JuridicoDAO();
	$juridicoDAO->setDatabase($database);

	$juridicoModelo = new Juridico();
?>