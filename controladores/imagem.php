<?php
	/**
	 * Controlador de imagem com classe instanciada
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/ImagemDAO.php");

	$imagemDAO = new ImagemDAO();
	$imagemDAO->setDatabase($database);
?>