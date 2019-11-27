<?php

	// TÍTULO DA PÁGINA
	$title = 'Podcasts';

	// ÍCONE
	$favicon = 'img/models/podcast.png';

	// TABELA NO BANCO DE DADOS
	$table = 'PODCASTS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['AUDIO'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os podcasts permitem o anexo de áudio no formato MP3, que são disponibilizados para as pessoas escutarem e também para fazerem o download do arquivo. Na página principal é exibido o último podcast publicado.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'AUDIO' => ['default' => '', 'domain' => 'string', 'label' => 'ÁUDIO', 'name' => 'audio', 'unique' => false],
		'DATA' => ['default' => date('Y-m-d'), 'domain' => 'string', 'label' => 'DATA', 'name' => 'data', 'unique' => false],
		'HORA' => ['default' => date('H:i'), 'domain' => 'string', 'label' => 'HORA', 'name' => 'hora', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `PODCASTS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(64) NOT NULL,
		`AUDIO` VARCHAR(64) NOT NULL,
		`DATA` DATE NOT NULL DEFAULT CURRENT_DATE,
		`HORA` TIME NOT NULL DEFAULT CURRENT_TIME,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'AUDIO' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'audio/mp3', 'required' => 'required']],
		'DATA' => ['tag' => 'input', 'type' => 'date', 'attributes' => ['data-mask' => '0000-00-00', 'required' => 'required']],
		'HORA' => ['tag' => 'input', 'type' => 'time', 'attributes' => ['data-mask' => '00:00', 'required' => 'required']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['AUDIO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'AUDIO' => ['tag' => 'audio'],
		'DATA' => ['tag' => 'p', 'pretty' => fn(string $date) => date_format(date_create($date), 'd/m/Y')],
		'HORA' => ['tag' => 'p']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
