<?php
	/**
	 * Testes unitários do modelo Podcast
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Podcast.php");
	include_once("modelo_usuario.php");

	$podcastModelo = new Podcast();
	$podcastModelo->setId(1);
	$podcastModelo->setTitulo("Podcast 1");
	$podcastModelo->hashAudio("podcast1.mp3"); // $podcastModelo->setAudio("uploads/podcasts/podcast1.mp3");
	$podcastModelo->setUsuario($usuarioModelo);
	$podcastModelo->setData("2019-01-01 01:01:01");
	$podcastModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_podcast.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $podcastModelo;
	}
?>