<?php

	// TÍTULO DA PÁGINA
	$title = 'Convenções';

	// ÍCONE
	$favicon = 'img/models/convencao.png';

	// TABELA NO BANCO DE DADOS
	$table = 'CONVENCOES';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['DOCUMENTO'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'As convenções são agrupadas pelo tipo (vigente ou anterior) e ordenadas de acordo com a data de publicação.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'TIPO' => ['default' => true, 'domain' => 'boolean', 'label' => 'TIPO', 'mask' => ['0' => 'ANTERIOR', '1' => 'VIGENTE'], 'name' => 'tipo', 'unique' => false],
		'DOCUMENTO' => ['default' => '', 'domain' => 'string', 'label' => 'DOCUMENTO', 'name' => 'documento', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `CONVENCOES` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TIPO` TINYINT(1) NOT NULL DEFAULT 1,
		`DOCUMENTO` VARCHAR(64) NOT NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'TIPO' => ['tag' => 'select', 'attributes' => ['required' => 'required'], 'options' => [
			['label' => 'VIGENTE', 'value' => '1'],
			['label' => 'ANTERIOR', 'value' => '0']]
		],
		'DOCUMENTO' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'application/pdf', 'required' => 'required']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['DOCUMENTO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'TIPO' => ['tag' => 'p'],
		'DOCUMENTO' => ['tag' => 'a']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
