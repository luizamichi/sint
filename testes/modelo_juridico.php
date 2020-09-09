<?php
	/**
	 * Testes unitários do modelo Jurídico
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Juridico.php");
	include_once("modelo_usuario.php");

	$juridicoModelo = new Juridico();
	$juridicoModelo->setId(1);
	$juridicoModelo->setTitulo("Projeto de Lei Complementar nº 4/2019");
	$juridicoModelo->setDescricao("Estabelece princípios e normas de gestão administrativa e de finanças públicas no âmbito do Estado do Paraná e dá outras providências.");
	$juridicoModelo->hashArquivo("plc04-2019.pdf"); // $juridicoModelo->setArquivo("uploads/juridicos/plc04-2019.pdf");
	$juridicoModelo->setUsuario($usuarioModelo);
	$juridicoModelo->setData("2019-04-16 01:01:01");
	$juridicoModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_juridico.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $juridicoModelo;
	}
?>