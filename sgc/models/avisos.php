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
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'TEXTO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TEXTO', 'name'=> 'texto', 'unique'=> false),
		'INICIO'=> array('default'=> date('Y-m-d'), 'domain'=> 'string', 'label'=> 'INÍCIO', 'name'=> 'inicio', 'unique'=> false),
		'FIM'=> array('default'=> date('Y-m-d'), 'domain'=> 'string', 'label'=> 'FIM', 'name'=> 'fim', 'unique'=> false),
		'TEMPO'=> array('default'=> date('Y-m-d H:i:s'), 'domain'=> 'string', 'label'=> 'TEMPO', 'name'=> 'tempo', 'unique'=> false),
	);

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
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'TEXTO'=> array('tag'=> 'textarea', 'attributes'=> array('minlength'=> 4, 'required'=> 'required', 'rows'=> 4)),
		'INICIO'=> array('tag'=> 'input', 'type'=> 'date', 'attributes'=> array('required'=> 'required')),
		'FIM'=> array('tag'=> 'input', 'type'=> 'date', 'attributes'=> array('required'=> 'required')),
		'TEMPO'=> array('tag'=> 'input', 'type'=> 'hidden', 'attributes'=> array('required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'TEXTO'=> array('tag'=> 'p'),
		'INICIO'=> array('tag'=> 'p'),
		'FIM'=> array('tag'=> 'p'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>