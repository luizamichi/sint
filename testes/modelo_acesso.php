<?php
	/**
	 * Testes unitários do modelo Acesso
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Acesso.php");

	$acessoModelo = new Acesso();
	$acessoModelo->setId(1);
	$acessoModelo->setIp("127.0.0.1");
	$acessoModelo->setData("2020-02-10 19:30:00");

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_acesso.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $acessoModelo;
	}
?>