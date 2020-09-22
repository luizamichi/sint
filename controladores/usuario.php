<?php
	/**
	 * Controlador de usuário com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/UsuarioDAO.php");
	include_once("../modelos/Usuario.php");

	$usuarioDAO = new UsuarioDAO();
	$usuarioDAO->setDatabase($database);

	$usuarioModelo = new Usuario();
?>