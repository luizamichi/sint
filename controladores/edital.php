<?php
	/**
	 * Controlador de edital com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/EditalDAO.php");
	include_once("../modelos/Edital.php");

	$editalDAO = new EditalDAO();
	$editalDAO->setDatabase($database);

	$editalModelo = new Edital();
?>