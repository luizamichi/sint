<?php
	// TÍTULO DA PÁGINA
	$title = 'Banners';

	// ÍCONE
	$favicon = 'img/banner.svg';

	// TABELA NO BANCO DE DADOS
	$table = 'BANNERS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['IMAGEM'];
	$hasFolder = false;

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'IMAGEM'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'IMAGEM', 'name'=> 'imagem', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `BANNERS`(
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`IMAGEM` VARCHAR(64) NOT NULL
	)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('readonly'=> 'readonly')),
		'IMAGEM'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'image/jpeg,image/x-png', 'autofocus'=> 'autofocus', 'required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'IMAGEM'=> array('tag'=> 'img'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>