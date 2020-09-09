<?php
	/**
	 * Testes unitários do modelo Registro
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Registro.php");

	$registroModelo = new Registro();
	$registroModelo->setId(1);
	$registroModelo->setDescricao("Luiz Joaquim Aderaldo Amichi entrou.");
	$registroModelo->setIp("192.168.1.1");
	$registroModelo->setData("2020-02-18 19:30:00");

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_registro.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $registroModelo;
	}
?>