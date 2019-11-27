<?php

	// TÍTULO DA PÁGINA
	$title = 'Eventos';

	// ÍCONE
	$favicon = 'img/models/evento.png';

	// TABELA NO BANCO DE DADOS
	$table = 'EVENTOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = false;
	$hasFolder = true;
	$folder = 'IMAGENS';

	// TEXTO DE AJUDA
	$help = 'Os eventos permitem o envio de diversas imagens associadas a uma única publicação. A exclusão de imagens individualmente não é possível. Na página principal são exibidos até 3 eventos.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'TEXTO' => ['default' => null, 'domain' => 'string', 'label' => 'TEXTO', 'name' => 'texto', 'unique' => false],
		'IMAGENS' => ['default' => '', 'domain' => 'string', 'label' => 'IMAGENS', 'name' => 'imagens[]', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `EVENTOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TEXTO` TEXT NULL,
		`IMAGENS` VARCHAR(64) NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'TEXTO' => ['id' => 'richtexteditor', 'tag' => 'textarea', 'attributes' => ['minlength' => 4, 'rows' => 6]],
		'IMAGENS' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'image/jpeg,image/x-png', 'multiple' => 'multiple', 'required' => 'required']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['IMAGENS']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'TEXTO' => ['tag' => 'p'],
		'IMAGENS' => ['tag' => 'a']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
