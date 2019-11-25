<?php

	// TÍTULO DA PÁGINA
	$title = 'Diretoria';

	// ÍCONE
	$favicon = 'img/models/diretoria.png';

	// TABELA NO BANCO DE DADOS
	$table = 'DIRETORIA';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['IMAGEM'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'As diretorias são exibidas em duas páginas. A última diretoria publicada é exibida na página de diretores e as demais na página de histórico.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'IMAGEM' => ['default' => null, 'domain' => 'string', 'label' => 'IMAGEM', 'name' => 'imagem', 'unique' => false],
		'TEXTO' => ['default' => '', 'domain' => 'string', 'label' => 'TEXTO', 'name' => 'texto', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `DIRETORIA` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`IMAGEM` VARCHAR(64) NULL,
		`TEXTO` TEXT NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'IMAGEM' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'image/jpeg,image/x-png']],
		'TEXTO' => ['id' => 'richtexteditor', 'tag' => 'textarea', 'attributes' => ['minlength' => 4, 'required' => 'required', 'rows' => 6]],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'IMAGEM' => ['tag' => 'img'],
		'TEXTO' => ['tag' => 'p']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
