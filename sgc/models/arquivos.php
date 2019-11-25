<?php

	// TÍTULO DA PÁGINA
	$title = 'Arquivos';

	// ÍCONE
	$favicon = 'img/models/arquivo.png';

	// TABELA NO BANCO DE DADOS
	$table = 'ARQUIVOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = ['ARQUIVO'];
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os arquivos são úteis para o upload de imagens, áudios ou documentos em PDF que deseja-se anexar ou exibir em alguma publicação.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'ARQUIVO' => ['default' => '', 'domain' => 'string', 'label' => 'ARQUIVO', 'name' => 'arquivo', 'unique' => false],
		'OBSERVACAO' => ['default' => '', 'domain' => 'string', 'label' => 'OBSERVAÇÃO', 'name' => 'observacao', 'unique' => false],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `ARQUIVOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`ARQUIVO` VARCHAR(64) NOT NULL,
		`OBSERVACAO` VARCHAR(64) NULL,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'ARQUIVO' => ['tag' => 'input', 'type' => 'file', 'attributes' => ['accept' => 'application/pdf,audio/mp3,image/jpeg,image/x-png', 'autofocus' => 'autofocus', 'required' => 'required']],
		'OBSERVACAO' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['maxlength' => 64, 'minlength' => 4, 'placeholder' => 'Opcional']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['ARQUIVO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'ARQUIVO' => ['tag' => 'a'],
		'OBSERVACAO' => ['tag' => 'p']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
