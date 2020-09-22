<?php
	/**
	 * Controlador de acesso com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/AcessoDAO.php");
	include_once("../modelos/Acesso.php");

	$acessoDAO = new AcessoDAO();
	$acessoDAO->setDatabase($database);

	$acessoModelo = new Acesso();
?>