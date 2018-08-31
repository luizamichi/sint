<?php
	// TÍTULO DA PÁGINA
	$title = 'Jornais';

	// ÍCONE
	$favicon = 'img/jornal.svg';

	// TABELA NO BANCO DE DADOS
	$table = 'JORNAIS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['DOCUMENTO', 'IMAGEM'];
	$hasFolder = false;

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'EDICAO'=> array('default'=> '', 'domain'=> 'integer', 'label'=> 'EDIÇÃO', 'name'=> 'edicao', 'unique'=> true),
		'DOCUMENTO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'DOCUMENTO', 'name'=> 'documento', 'unique'=> false),
		'IMAGEM'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'IMAGEM', 'name'=> 'imagem', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `JORNAIS`(
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`EDICAO` INT NOT NULL,
		`DOCUMENTO` VARCHAR(64) NOT NULL,
		`IMAGEM` VARCHAR(64) NULL
	)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'EDICAO'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('min'=> 1, 'required'=> 'required')),
		'DOCUMENTO'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'application/pdf', 'required'=> 'required')),
		'IMAGEM'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'image/jpeg,image/x-png')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['DOCUMENTO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'EDICAO'=> array('tag'=> 'p'),
		'DOCUMENTO'=> array('tag'=> 'a'),
		'IMAGEM'=> array('tag'=> 'img'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>