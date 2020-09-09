<?php
	/**
	 * Testes unitários do modelo Evento
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Evento.php");
	include_once("modelo_usuario.php");
	include_once("vetor_imagem.php");

	$eventoModelo = new Evento();
	$eventoModelo->setId(1);
	$eventoModelo->setTitulo("Confraternização de Posse da Nova Diretoria");
	$eventoModelo->setDescricao("O Sinteemar realizou no último dia 5 de julho, a confraternização anual e o jantar de posse da nova diretoria (gestão 2019-2023). A festa, que aconteceu no Clube Olímpico, contou com milhares de filiados, amigos e autoridades.");
	$eventoModelo->hashDiretorio("jantar2019-posse"); // $eventoModelo->setDiretorio("uploads/eventos/jantar2019-posse");
	$eventoModelo->setImagens($imagemVetor);
	$eventoModelo->setUsuario($usuarioModelo);
	$eventoModelo->setData("2019-07-10 01:01:01");
	$eventoModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_evento.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $eventoModelo;
	}
?>