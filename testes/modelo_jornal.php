<?php
	/**
	 * Testes unitários do modelo Jornal
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Jornal.php");
	include_once("modelo_usuario.php");

	$jornalModelo = new Jornal();
	$jornalModelo->setId(1);
	$jornalModelo->setTitulo("Situação do Ensino Médio Superior");
	$jornalModelo->setEdicao(101);
	$jornalModelo->hashArquivo("setembro2017-101.pdf"); // $jornalModelo->setArquivo("uploads/jornais/setembro2017-101.pdf");
	$jornalModelo->hashImagem("setembro2017-101.png"); // $jornalModelo->setImagem("uploads/jornais/setembro2017-101.png");
	$jornalModelo->setUsuario($usuarioModelo);
	$jornalModelo->setData("2017-09-01 01:01:01");
	$jornalModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_jornal.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $jornalModelo;
	}
?>