<?php
	/**
	 * Controlador de evento com classes instanciadas
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("database.php");
	include_once("../dao/EventoDAO.php");
	include_once("../modelos/Evento.php");

	$eventoDAO = new EventoDAO();
	$eventoDAO->setDatabase($database);

	$eventoModelo = new Evento();
?>