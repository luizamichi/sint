<?php
	// TÍTULO DA PÁGINA
	$title = 'Notícias';

	// ÍCONE
	$favicon = 'img/models/noticia.png';

	// TABELA NO BANCO DE DADOS
	$table = 'NOTICIAS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = array('IMAGEM');
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'As notícias possuem características diferentes das demais opções. Nela é possível informar se deverá ou não ser exibida, além de ser ordenada de acordo com a data e hora informados. Na página principal são exibidas até 29 notícias.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'TEXTO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TEXTO', 'name'=> 'texto', 'unique'=> false),
		'IMAGEM'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'IMAGEM', 'name'=> 'imagem', 'unique'=> false),
		'DATA'=> array('default'=> date('Y-m-d'), 'domain'=> 'string', 'label'=> 'DATA', 'name'=> 'data', 'unique'=> false),
		'HORA'=> array('default'=> date('H:i'), 'domain'=> 'string', 'label'=> 'HORA', 'name'=> 'hora', 'unique'=> false),
		'STATUS'=> array('default'=> true, 'domain'=> 'boolean', 'label'=> 'STATUS', 'mask'=> array('0'=> 'INATIVO', '1'=> 'ATIVO'), 'name'=> 'status', 'unique'=> false),
		'TEMPO'=> array('default'=> date('Y-m-d H:i:s'), 'domain'=> 'string', 'label'=> 'TEMPO', 'name'=> 'tempo', 'unique'=> false),
	);

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
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'TEXTO'=> array('id'=> 'richtexteditor', 'tag'=> 'textarea', 'attributes'=> array('minlength'=> 4, 'required'=> 'required', 'rows'=> 6)),
		'IMAGEM'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'image/jpeg,image/x-png')),
		'DATA'=> array('tag'=> 'input', 'type'=> 'date', 'attributes'=> array('required'=> 'required')),
		'HORA'=> array('tag'=> 'input', 'type'=> 'time', 'attributes'=> array('required'=> 'required')),
		'STATUS'=> array('tag'=> 'select', 'attributes'=> array('required'=> 'required'), 'options'=> array(
			array('label'=> 'INATIVO', 'value'=> '0'),
			array('label'=> 'ATIVO', 'value'=> '1')),
		),
		'TEMPO'=> array('tag'=> 'input', 'type'=> 'hidden', 'attributes'=> array('required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'TEXTO'=> array('tag'=> 'p'),
		'IMAGEM'=> array('tag'=> 'img'),
		'DATA'=> array('tag'=> 'p'),
		'HORA'=> array('tag'=> 'p'),
		'STATUS'=> array('tag'=> 'p'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>