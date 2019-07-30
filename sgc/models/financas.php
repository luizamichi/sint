<?php
	// TÍTULO DA PÁGINA
	$title = 'Finanças';

	// ÍCONE
	$favicon = 'img/models/financa.png';

	// TABELA NO BANCO DE DADOS
	$table = 'FINANCAS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = array('DOCUMENTO');
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'As finanças são publicações voltadas ao balanço econômico da organização. Todos os campos são obrigatórios e publicados de acordo com a data de cadastro.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'DOCUMENTO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'DOCUMENTO', 'name'=> 'documento', 'unique'=> false),
		'TEMPO'=> array('default'=> date('Y-m-d H:i:s'), 'domain'=> 'string', 'label'=> 'TEMPO', 'name'=> 'tempo', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `FINANCAS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(64) NOT NULL,
		`DOCUMENTO` VARCHAR(64) NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 64, 'minlength'=> 4, 'required'=> 'required')),
		'DOCUMENTO'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'application/pdf', 'required'=> 'required')),
		'TEMPO'=> array('tag'=> 'input', 'type'=> 'hidden', 'attributes'=> array('required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['DOCUMENTO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'DOCUMENTO'=> array('tag'=> 'a'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>