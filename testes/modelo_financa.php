<?php
	/**
	 * Testes unitários do modelo Finança
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Financa.php");
	include_once("modelo_usuario.php");

	$financaModelo = new Financa();
	$financaModelo->setId(1);
	$financaModelo->setPeriodo("Janeiro de 2018");
	$financaModelo->hashArquivo("janeiro2018.pdf"); // $financaModelo->setArquivo("uploads/financas/janeiro2018.pdf");
	$financaModelo->setUsuario($usuarioModelo);
	$financaModelo->setData("2018-01-01 01:01:01");
	$financaModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_financa.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $financaModelo;
	}
?>