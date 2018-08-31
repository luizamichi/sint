<?php
	// TÍTULO DA PÁGINA
	$title = 'Vídeos';

	// ÍCONE
	$favicon = 'img/video.svg';

	// TABELA NO BANCO DE DADOS
	$table = 'VIDEOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = false;
	$hasFolder = false;

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'URL'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'URL', 'name'=> 'url', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `VIDEOS`(
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`URL` VARCHAR(128) NOT NULL
	)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'URL'=> array('tag'=> 'input', 'type'=> 'url', 'attributes'=> array('maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'URL'=> array('tag'=> 'a'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>