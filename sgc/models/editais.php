<?php
	// TÍTULO DA PÁGINA
	$title = 'Editais';

	// ÍCONE
	$favicon = 'img/models/edital.png';

	// TABELA NO BANCO DE DADOS
	$table = 'EDITAIS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = array('IMAGEM');
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os editais assemelham-se muito às notícias. Na página principal são exibidos até 6 editais.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'IMAGEM'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'IMAGEM', 'name'=> 'imagem', 'unique'=> false),
		'TEXTO'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'TEXTO', 'name'=> 'texto', 'unique'=> false),
		'TEMPO'=> array('default'=> date('Y-m-d H:i:s'), 'domain'=> 'string', 'label'=> 'TEMPO', 'name'=> 'tempo', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `EDITAIS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`IMAGEM` VARCHAR(64) NOT NULL,
		`TEXTO` TEXT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'IMAGEM'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'image/jpeg,image/x-png', 'required'=> 'required')),
		'TEXTO'=> array('id'=> 'richtexteditor', 'tag'=> 'textarea', 'attributes'=> array('minlength'=> 4, 'rows'=> 6)),
		'TEMPO'=> array('tag'=> 'input', 'type'=> 'hidden', 'attributes'=> array('required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['IMAGEM']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'TEXTO'=> array('tag'=> 'p'),
		'IMAGEM'=> array('tag'=> 'img'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>