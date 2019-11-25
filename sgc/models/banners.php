<?php

	// TÍTULO DA PÁGINA
	$title = 'Banners';

	// ÍCONE
	$favicon = 'img/models/banner.png';

	// TABELA NO BANCO DE DADOS
	$table = 'BANNERS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['IMAGEM'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os banners dão dinâmica à página inicial, abrindo a possibilidade de inserir até 5 imagens deslizantes para exibição.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'IMAGEM' => ['default' => '', 'domain' => 'string', 'label' => 'IMAGEM', 'name' => 'imagem', 'unique' => false],
		'LINK' => ['default' => '', 'domain' => 'string', 'label' => 'LINK', 'name' => 'link', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `BANNERS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`IMAGEM` VARCHAR(64) NOT NULL,
		`LINK` VARCHAR(128) NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'IMAGEM' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'image/jpeg,image/x-png', 'autofocus' => 'autofocus', 'required' => 'required']],
		'LINK' => ['tag' => 'input', 'type' => 'url', 'attributes' => ['maxlength' => 128, 'minlength' => 4, 'placeholder' => 'Opcional']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'IMAGEM' => ['tag' => 'img'],
		'LINK' => ['tag' => 'a']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
