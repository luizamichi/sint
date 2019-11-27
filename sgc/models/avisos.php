<?php

	// TÍTULO DA PÁGINA
	$title = 'Avisos';

	// ÍCONE
	$favicon = 'img/models/aviso.png';

	// TABELA NO BANCO DE DADOS
	$table = 'AVISOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = false;
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os avisos são muito úteis para a exibição de uma mensagem importante na página inicial. Só é exibido o último aviso com a data vigente válida.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'TEXTO' => ['default' => '', 'domain' => 'string', 'label' => 'TEXTO', 'name' => 'texto', 'unique' => false],
		'INICIO' => ['default' => date('Y-m-d'), 'domain' => 'string', 'label' => 'INÍCIO', 'name' => 'inicio', 'unique' => false],
		'FIM' => ['default' => date('Y-m-d'), 'domain' => 'string', 'label' => 'FIM', 'name' => 'fim', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `AVISOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TEXTO` TEXT NOT NULL,
		`INICIO` DATE NOT NULL DEFAULT CURRENT_DATE,
		`FIM` DATE NOT NULL DEFAULT CURRENT_DATE,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'TEXTO' => ['tag' => 'textarea', 'attributes' => ['minlength' => 4, 'required' => 'required', 'rows' => 4]],
		'INICIO' => ['tag' => 'input', 'type' => 'date', 'attributes' => ['required' => 'required']],
		'FIM' => ['tag' => 'input', 'type' => 'date', 'attributes' => ['required' => 'required']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'TEXTO' => ['tag' => 'p'],
		'INICIO' => ['tag' => 'p', 'pretty' => fn(string $date) => date_format(date_create($date), 'd/m/Y')],
		'FIM' => ['tag' => 'p', 'pretty' => fn(string $date) => date_format(date_create($date), 'd/m/Y')]
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
