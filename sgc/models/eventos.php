<?php
	// TÍTULO DA PÁGINA
	$title = 'Eventos';

	// ÍCONE
	$favicon = 'img/models/evento.png';

	// TABELA NO BANCO DE DADOS
	$table = 'EVENTOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = false;
	$hasFolder = true;
	$folder = 'IMAGENS';

	// TEXTO DE AJUDA
	$help = 'Os eventos permitem o envio de diversas imagens associadas a uma única publicação. A exclusão de imagens individualmente não é possível. Na página principal são exibidos até 3 eventos.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'TEXTO'=> array('default'=> null, 'domain'=> 'string', 'label'=> 'TEXTO', 'name'=> 'texto', 'unique'=> false),
		'IMAGENS'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'IMAGENS', 'name'=> 'imagens[]', 'unique'=> false),
		'TEMPO'=> array('default'=> date('Y-m-d H:i:s'), 'domain'=> 'string', 'label'=> 'TEMPO', 'name'=> 'tempo', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `EVENTOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TEXTO` TEXT NULL,
		`IMAGENS` VARCHAR(64) NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'TEXTO'=> array('id'=> 'richtexteditor', 'tag'=> 'textarea', 'attributes'=> array('minlength'=> 4, 'rows'=> 6)),
		'IMAGENS'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'image/jpeg,image/x-png', 'multiple'=> 'multiple', 'required'=> 'required')),
		'TEMPO'=> array('tag'=> 'input', 'type'=> 'hidden', 'attributes'=> array('required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['IMAGENS']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'TEXTO'=> array('tag'=> 'p'),
		'IMAGENS'=> array('tag'=> 'a'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>