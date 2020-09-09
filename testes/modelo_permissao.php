<?php
	/**
	 * Testes unitários do modelo Permissão
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Permissao.php");

	$permissaoModelo = new Permissao();
	$permissaoModelo->setId(1);
	$permissaoModelo->setBanner(TRUE);
	$permissaoModelo->setBoletim(TRUE);
	$permissaoModelo->setConvencao(TRUE);
	$permissaoModelo->setConvenio(TRUE);
	$permissaoModelo->setDiretorio(TRUE);
	$permissaoModelo->setEdital(TRUE);
	$permissaoModelo->setEvento(TRUE);
	$permissaoModelo->setFinanca(TRUE);
	$permissaoModelo->setJornal(TRUE);
	$permissaoModelo->setJuridico(TRUE);
	$permissaoModelo->setNoticia(TRUE);
	$permissaoModelo->setPodcast(TRUE);
	$permissaoModelo->setRegistro(TRUE);
	$permissaoModelo->setTabela(TRUE);
	$permissaoModelo->setUsuario(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_permissao.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $permissaoModelo;
	}
?>