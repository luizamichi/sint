<?php
	/**
	 * Testes unitários do modelo Convênio
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Convenio.php");
	include_once("modelo_usuario.php");

	$convenioModelo = new Convenio();
	$convenioModelo->setId(1);
	$convenioModelo->setTitulo("CLIP (Clínica Para Todos)");
	$convenioModelo->setDescricao("Consultas e procedimentos médicos em diversas especialidades e exames laboratoriais.");
	$convenioModelo->setCidade("Maringá - PR");
	$convenioModelo->setTelefone("(44) 3305-9252");
	$convenioModelo->setCelular("(44) 99941-6816");
	$convenioModelo->setSite("http://www.clinicaclip.com.br/");
	$convenioModelo->setEmail("contato@clinicaclip.com.br");
	$convenioModelo->hashImagem("clip.png"); // $convenioModelo->setImagem("uploads/convenios/clip.png");
	$convenioModelo->hashArquivo("clip.pdf"); // $convenioModelo->setArquivo("uploads/convenios/clip.pdf");
	$convenioModelo->setUsuario($usuarioModelo);
	$convenioModelo->setData("2020-01-01 01:01:01");
	$convenioModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_convenio.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $convenioModelo;
	}
?>