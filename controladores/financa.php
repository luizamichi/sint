<?php
	/**
	 * Controlador de finança com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/FinancaDAO.php");
	include_once("../modelos/Financa.php");

	$financaDAO = new FinancaDAO();
	$financaDAO->setDatabase($database);

	$financaModelo = new Financa();
?>