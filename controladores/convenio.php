<?php
	/**
	 * Controlador de convênio com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/ConvenioDAO.php");
	include_once("../modelos/Convenio.php");

	$convenioDAO = new ConvenioDAO();
	$convenioDAO->setDatabase($database);

	$convenioModelo = new Convenio();
?>