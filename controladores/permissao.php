<?php
	/**
	 * Controlador de permissão com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/PermissaoDAO.php");
	include_once("../modelos/Permissao.php");

	$permissaoDAO = new PermissaoDAO();
	$permissaoDAO->setDatabase($database);

	$permissaoModelo = new Permissao();
?>