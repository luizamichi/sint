<?php

	// TÍTULO DA PÁGINA
	$title = 'Boletins';

	// ÍCONE
	$favicon = 'img/models/boletim.png';

	// TABELA NO BANCO DE DADOS
	$table = 'BOLETINS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['IMAGEM'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os boletins são de categoria mais simples, dando a possibilidade de anexar uma imagem ao conteúdo. Na página principal são exibidos até 6 boletins.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'IMAGEM' => ['default' => '', 'domain' => 'string', 'label' => 'IMAGEM', 'name' => 'imagem', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `BOLETINS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`IMAGEM` VARCHAR(64) NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'IMAGEM' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'image/jpeg,image/x-png', 'required' => 'required']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['IMAGEM']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'IMAGEM' => ['tag' => 'img']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
