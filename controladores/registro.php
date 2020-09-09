<?php
	/**
	 * Controlador de registro com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/RegistroDAO.php");
	include_once("../modelos/Registro.php");

	$registroDAO = new RegistroDAO();
	$registroDAO->setDatabase($database);

	$registroModelo = new Registro();
?>