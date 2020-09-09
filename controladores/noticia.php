<?php
	/**
	 * Controlador de notícia com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/NoticiaDAO.php");
	include_once("../modelos/Noticia.php");

	$noticiaDAO = new NoticiaDAO();
	$noticiaDAO->setDatabase($database);

	$noticiaModelo = new Noticia();
?>