<?php

	// TÍTULO DA PÁGINA
	$title = 'Notícias';

	// ÍCONE
	$favicon = 'img/models/noticia.png';

	// TABELA NO BANCO DE DADOS
	$table = 'NOTICIAS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['IMAGEM'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'As notícias possuem características diferentes das demais opções. Nela é possível informar se deverá ou não ser exibida, além de ser ordenada de acordo com a data e hora informados. Na página principal são exibidas até 29 notícias.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'TEXTO' => ['default' => '', 'domain' => 'string', 'label' => 'TEXTO', 'name' => 'texto', 'unique' => false],
		'IMAGEM' => ['default' => null, 'domain' => 'string', 'label' => 'IMAGEM', 'name' => 'imagem', 'unique' => false],
		'DATA' => ['default' => date('Y-m-d'), 'domain' => 'string', 'label' => 'DATA', 'name' => 'data', 'unique' => false],
		'HORA' => ['default' => date('H:i'), 'domain' => 'string', 'label' => 'HORA', 'name' => 'hora', 'unique' => false],
		'STATUS' => ['default' => true, 'domain' => 'boolean', 'label' => 'STATUS', 'mask' => ['0' => 'INATIVO', '1' => 'ATIVO'], 'name' => 'status', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `NOTICIAS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TEXTO` TEXT NOT NULL,
		`IMAGEM` VARCHAR(64) NULL,
		`DATA` DATE NOT NULL DEFAULT CURRENT_DATE,
		`HORA` TIME NOT NULL DEFAULT CURRENT_TIME,
		`STATUS` TINYINT(1) NOT NULL DEFAULT 1,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'TEXTO' => ['id' => 'richtexteditor', 'tag' => 'textarea', 'attributes' => ['minlength' => 4, 'required' => 'required', 'rows' => 6]],
		'IMAGEM' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'image/jpeg,image/x-png']],
		'DATA' => ['tag' => 'input', 'type' => 'date', 'attributes' => ['required' => 'required']],
		'HORA' => ['tag' => 'input', 'type' => 'time', 'attributes' => ['required' => 'required']],
		'STATUS' => ['tag' => 'select', 'attributes' => ['required' => 'required'], 'options' => [
			['label' => 'INATIVO', 'value' => '0'],
			['label' => 'ATIVO', 'value' => '1']],
		],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'TEXTO' => ['tag' => 'p'],
		'IMAGEM' => ['tag' => 'img'],
		'DATA' => ['tag' => 'p', 'pretty' => fn(string $date) => date_format(date_create($date), 'd/m/Y')],
		'HORA' => ['tag' => 'p'],
		'STATUS' => ['tag' => 'p']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
