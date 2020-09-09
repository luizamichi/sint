<?php
	/**
	 * Testes unitários do modelo Edital
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Edital.php");
	include_once("modelo_usuario.php");

	$editalModelo = new Edital();
	$editalModelo->setId(1);
	$editalModelo->setTitulo("Assembleia Geral Extraordinária");
	$editalModelo->setDescricao("<p><strong>Data</strong>: 18 de setembro de 2020</p><p><strong>Horário</strong>: 08:30</p><p><strong>Local</strong>: Auditório dos Trabalhadores - Sinteemar</p><p><strong>Pauta</strong>: <br>Apreciação e deliberação sobre a contraproposta patronal às reivindicações da categoria referente à Convenção Coletiva de Trabalho 2017/2018;<br>Assuntos gerais.</p>");
	$editalModelo->hashImagem("edital-convocacao15092017.jpg"); // $editalModelo->setImagem("uploads/editais/edital-convocacao15092017.jpg");
	$editalModelo->setUsuario($usuarioModelo);
	$editalModelo->setData("2017-09-15 01:01:01");
	$editalModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_edital.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $editalModelo;
	}
?>