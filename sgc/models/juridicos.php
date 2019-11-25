<?php

	// TÍTULO DA PÁGINA
	$title = 'Jurídicos';

	// ÍCONE
	$favicon = 'img/models/juridico.png';

	// TABELA NO BANCO DE DADOS
	$table = 'JURIDICOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['DOCUMENTO'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os documentos jurídicos permitem o anexo de um documento em PDF com um texto de orientação.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'TEXTO' => ['default' => null, 'domain' => 'string', 'label' => 'TEXTO', 'name' => 'texto', 'unique' => false],
		'DOCUMENTO' => ['default' => '', 'domain' => 'string', 'label' => 'DOCUMENTO', 'name' => 'documento', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `JURIDICOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TEXTO` TEXT NULL,
		`DOCUMENTO` VARCHAR(64) NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'TEXTO' => ['tag' => 'textarea', 'attributes' => ['minlength' => 4, 'placeholder' => 'Opcional', 'rows' => 4]],
		'DOCUMENTO' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'application/pdf', 'required' => 'required']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['DOCUMENTO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'TEXTO' => ['tag' => 'p'],
		'DOCUMENTO' => ['tag' => 'a']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
