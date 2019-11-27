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

	// TEXTO DE AJUDA
	$help = 'Os convênios são ordenadas de acordo com a data da publicação. A quantidade de palavras no texto influencia na disposição dos elementos na página.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'TITULO' => ['default' => '', 'domain' => 'string', 'label' => 'TÍTULO', 'name' => 'titulo', 'unique' => false],
		'TEXTO' => ['default' => '', 'domain' => 'string', 'label' => 'TEXTO', 'name' => 'texto', 'unique' => false],
		'TELEFONE' => ['default' => null, 'domain' => 'string', 'label' => 'TELEFONE', 'name' => 'telefone', 'unique' => false],
		'CELULAR' => ['default' => null, 'domain' => 'string', 'label' => 'CELULAR', 'name' => 'celular', 'unique' => false],
		'EMAIL' => ['default' => null, 'domain' => 'string', 'label' => 'E-MAIL', 'name' => 'email', 'unique' => false],
		'URL' => ['default' => '', 'domain' => 'string', 'label' => 'URL', 'name' => 'url', 'unique' => false],
		'IMAGEM' => ['default' => '', 'domain' => 'string', 'label' => 'IMAGEM', 'name' => 'imagem', 'unique' => false],
		'DOCUMENTO' => ['default' => null, 'domain' => 'string', 'label' => 'DOCUMENTO', 'name' => 'documento', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `CONVENIOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(128) NOT NULL,
		`TEXTO` TEXT NOT NULL,
		`TELEFONE` VARCHAR(16) NULL,
		`CELULAR` VARCHAR(16) NULL,
		`EMAIL` VARCHAR(64) NULL,
		`URL` VARCHAR(128) NOT NULL,
		`IMAGEM` VARCHAR(64) NOT NULL,
		`DOCUMENTO` VARCHAR(64) NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'TITULO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 128, 'minlength' => 4, 'required' => 'required']],
		'TEXTO' => ['tag' => 'textarea', 'attributes' => ['minlength' => 4, 'required' => 'required', 'rows' => 4]],
		'TELEFONE' => ['tag' => 'input', 'type' => 'tel', 'attributes' => ['data-mask' => '(00) 0000-0000', 'maxlength' => 16, 'minlength' => 4, 'pattern' => '(\([0-9]{2}\))\s([9]{1})?([0-9]{4})-([0-9]{4})', 'placeholder' => 'Opcional']],
		'CELULAR' => ['tag' => 'input', 'type' => 'tel', 'attributes' => ['data-mask' => '(00) 00000-0000', 'maxlength' => 16, 'minlength' => 4, 'pattern' => '(\([0-9]{2}\))\s([9]{1})?([0-9]{5})-([0-9]{4})', 'placeholder' => 'Opcional']],
		'EMAIL' => ['tag' => 'input', 'type' => 'email', 'attributes' => ['maxlength' => 64, 'minlength' => 4, 'placeholder' => 'Opcional']],
		'URL' => ['tag' => 'input', 'type' => 'url', 'attributes' => ['maxlength' => 128, 'minlength' => 4, 'placeholder' => 'Opcional']],
		'IMAGEM' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'image/jpeg,image/x-png', 'required' => 'required']],
		'DOCUMENTO' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'application/pdf']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['IMAGEM']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'TITULO' => ['tag' => 'p'],
		'TEXTO' => ['tag' => 'p'],
		'TELEFONE' => ['tag' => 'p'],
		'CELULAR' => ['tag' => 'p'],
		'EMAIL' => ['tag' => 'p'],
		'URL' => ['tag' => 'a'],
		'IMAGEM' => ['tag' => 'img'],
		'DOCUMENTO' => ['tag' => 'a']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
	unset($list['URL'], $list['IMAGEM'], $list['DOCUMENTO']);
