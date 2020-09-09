<?php
	/**
	 * Testes unitários do modelo Boletim
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Boletim.php");
	include_once("modelo_usuario.php");

	$boletimModelo = new Boletim();
	$boletimModelo->setId(1);
	$boletimModelo->setTitulo("Boletim Online (Dezembro de 2018) - Informativo Jurídico");
	$boletimModelo->hashArquivo("sinteemar-dezembro2018.pdf"); // $boletimModelo->setArquivo("uploads/boletins/sinteemar-dezembro2018.pdf");
	$boletimModelo->hashImagem("sinteemar-dezembro2018.png"); // $boletimModelo->setImagem("uploads/boletins/sinteemar-dezembro2018.png");
	$boletimModelo->setUsuario($usuarioModelo);
	$boletimModelo->setData("2018-12-12 01:01:01");
	$boletimModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_boletim.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $boletimModelo;
	}
?>