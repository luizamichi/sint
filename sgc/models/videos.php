<?php

	// TÍTULO DA PÁGINA
	$title = 'Vídeos';

	// ÍCONE
	$favicon = 'img/models/video.png';

	// TABELA NO BANCO DE DADOS
	$table = 'VIDEOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = false;
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os vídeos permitem vincular um link (seja do Facebook, Instagram, YouTube, etc.) de um conteúdo externo, de preferência um vídeo.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'URL' => ['default' => '', 'domain' => 'string', 'label' => 'URL', 'name' => 'url', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `VIDEOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`URL` VARCHAR(128) NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'URL' => ['tag' => 'input', 'type' => 'url', 'attributes' => ['maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'URL' => ['tag' => 'iframe']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
