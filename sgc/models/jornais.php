<?php

	// TÍTULO DA PÁGINA
	$title = 'Jornais';

	// ÍCONE
	$favicon = 'img/models/jornal.png';

	// TABELA NO BANCO DE DADOS
	$table = 'JORNAIS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['DOCUMENTO', 'IMAGEM'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os jornais se assemelham aos boletins. São ordenados pela edição, que deve ser única.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'EDICAO' => ['default' => '', 'domain' => 'integer', 'label' => 'EDIÇÃO', 'name' => 'edicao', 'unique' => true],
		'DOCUMENTO' => ['default' => '', 'domain' => 'string', 'label' => 'DOCUMENTO', 'name' => 'documento', 'unique' => false],
		'IMAGEM' => ['default' => null, 'domain' => 'string', 'label' => 'IMAGEM', 'name' => 'imagem', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `JORNAIS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`EDICAO` INT NOT NULL,
		`DOCUMENTO` VARCHAR(64) NOT NULL,
		`IMAGEM` VARCHAR(64) NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'EDICAO' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['min' => 1, 'required' => 'required']],
		'DOCUMENTO' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'application/pdf', 'required' => 'required']],
		'IMAGEM' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'image/jpeg,image/x-png']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['DOCUMENTO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'EDICAO' => ['tag' => 'p'],
		'DOCUMENTO' => ['tag' => 'a'],
		'IMAGEM' => ['tag' => 'img']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
