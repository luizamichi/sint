<?php
	/**
	 * Controlador de podcast com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/PodcastDAO.php");
	include_once("../modelos/Podcast.php");

	$podcastDAO = new PodcastDAO();
	$podcastDAO->setDatabase($database);

	$podcastModelo = new Podcast();
?>