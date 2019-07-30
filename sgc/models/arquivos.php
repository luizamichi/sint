<?php
	// TÍTULO DA PÁGINA
	$title = 'Arquivos';

	// ÍCONE
	$favicon = 'img/models/arquivo.png';

	// TABELA NO BANCO DE DADOS
	$table = 'ARQUIVOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = array('ARQUIVO');
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os arquivos são úteis para o upload de imagens ou documentos em PDF que deseja-se anexar ou exibir em alguma publicação.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'ARQUIVO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'ARQUIVO', 'name'=> 'arquivo', 'unique'=> false),
		'OBSERVACAO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'OBSERVAÇÃO', 'name'=> 'observacao', 'unique'=> false),
		'TEMPO'=> array('default'=> date('Y-m-d H:i:s'), 'domain'=> 'string', 'label'=> 'TEMPO', 'name'=> 'tempo', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `ARQUIVOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`ARQUIVO` VARCHAR(64) NOT NULL,
		`OBSERVACAO` VARCHAR(64) NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'ARQUIVO'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'application/pdf,audio/mp3,image/jpeg,image/x-png', 'autofocus'=> 'autofocus', 'required'=> 'required')),
		'OBSERVACAO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('maxlength'=> 64, 'minlength'=> 4, 'placeholder'=> 'Opcional')),
		'TEMPO'=> array('tag'=> 'input', 'type'=> 'hidden', 'attributes'=> array('required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['ARQUIVO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'ARQUIVO'=> array('tag'=> 'a'),
		'OBSERVACAO'=> array('tag'=> 'p'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>