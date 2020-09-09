<?php
	/**
	 * Testes unitários do modelo Convenção
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Convencao.php");
	include_once("modelo_usuario.php");

	$convencaoModelo = new Convencao();
	$convencaoModelo->setId(1);
	$convencaoModelo->setTitulo("Convenção Coletiva de Trabalho (2019/2020) - SINACAD");
	$convencaoModelo->hashArquivo("sinacad2019-20.pdf"); // $convencaoModelo->setArquivo("uploads/convencoes/sinacad2019-20.pdf");
	$convencaoModelo->setTipo(TRUE);
	$convencaoModelo->setUsuario($usuarioModelo);
	$convencaoModelo->setData("2019-01-01 01:01:01");
	$convencaoModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_convencao.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $convencaoModelo;
	}
?>