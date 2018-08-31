<?php
	// TÍTULO DA PÁGINA
	$title = 'Convênios';

	// ÍCONE
	$favicon = 'img/models/convenio.png';

	// TABELA NO BANCO DE DADOS
	$table = 'CONVENIOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['DOCUMENTO', 'IMAGEM'];
	$hasFolder = false;

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'TEXTO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TEXTO', 'name'=> 'texto', 'unique'=> false),
		'TELEFONE'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'TELEFONE', 'name'=> 'telefone', 'unique'=> false),
		'CELULAR'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'CELULAR', 'name'=> 'celular', 'unique'=> false),
		'EMAIL'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'E-MAIL', 'name'=> 'email', 'unique'=> false),
		'IMAGEM'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'IMAGEM', 'name'=> 'imagem', 'unique'=> false),
		'DOCUMENTO'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'DOCUMENTO', 'name'=> 'documento', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `CONVENIOS`(
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TEXTO` TEXT NOT NULL,
		`TELEFONE` VARCHAR(16) NULL,
		`CELULAR` VARCHAR(16) NULL,
		`EMAIL` VARCHAR(64) NULL,
		`IMAGEM` VARCHAR(64) NOT NULL,
		`DOCUMENTO` VARCHAR(64) NULL
	)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'TEXTO'=> array('tag'=> 'textarea', 'attributes'=> array('minlength'=> 4, 'required'=> 'required', 'rows'=> 4)),
		'TELEFONE'=> array('tag'=> 'input', 'type'=> 'tel', 'attributes'=> array('maxlength'=> 16, 'minlength'=> 4, 'pattern'=> '(\([0-9]{2}\))\s([9]{1})?([0-9]{4})-([0-9]{4})', 'placeholder'=> '(99) 9999-9999')),
		'CELULAR'=> array('tag'=> 'input', 'type'=> 'tel', 'attributes'=> array('maxlength'=> 16, 'minlength'=> 4, 'pattern'=> '(\([0-9]{2}\))\s([9]{1})?([0-9]{5})-([0-9]{4})', 'placeholder'=> '(99) 99999-9999')),
		'EMAIL'=> array('tag'=> 'input', 'type'=> 'email', 'attributes'=> array('maxlength'=> 64, 'minlength'=> 4)),
		'IMAGEM'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'image/jpeg,image/x-png', 'required'=> 'required')),
		'DOCUMENTO'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'application/pdf')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['IMAGEM']['attributes']['required']);
	unset($update['DOCUMENTO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'TEXTO'=> array('tag'=> 'p'),
		'TELEFONE'=> array('tag'=> 'p'),
		'CELULAR'=> array('tag'=> 'p'),
		'EMAIL'=> array('tag'=> 'p'),
		'IMAGEM'=> array('tag'=> 'img'),
		'DOCUMENTO'=> array('tag'=> 'a'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>