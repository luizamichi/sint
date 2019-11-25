<?php

	// TÍTULO DA PÁGINA
	$title = 'Usuários';

	// ÍCONE
	$favicon = 'img/models/usuario.png';

	// TABELA NO BANCO DE DADOS
	$table = 'USUARIOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = false;
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'O usuário é a entidade principal do sistema. As permissões são os mecanismos que restringem o acesso permitido para cada utilizador.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = [
		'ID' => ['default' => 1, 'domain' => 'integer', 'label' => 'ID', 'name' => 'id', 'unique' => true],
		'NOME' => ['default' => '', 'domain' => 'string', 'label' => 'NOME', 'name' => 'nome', 'unique' => false],
		'EMAIL' => ['default' => '', 'domain' => 'string', 'label' => 'E-MAIL', 'name' => 'email', 'unique' => true],
		'SENHA' => ['default' => '', 'domain' => 'string', 'label' => 'SENHA', 'name' => 'senha', 'hash' => true, 'unique' => false],
		'PERMISSAO' => ['default' => [], 'domain' => 'list', 'label' => 'PERMISSÃO', 'name' => 'permissao[]', 'unique' => false,
			'ARQUIVOS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'ARQUIVOS', 'mask' => ['0' => '', '1' => 'ARQUIVOS']],
			'AVISOS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'AVISOS', 'mask' => ['0' => '', '1' => 'AVISOS']],
			'BANNERS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'BANNERS', 'mask' => ['0' => '', '1' => 'BANNERS']],
			'BOLETINS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'BOLETINS', 'mask' => ['0' => '', '1' => 'BOLETINS']],
			'CONVENCOES' => ['default' => 0, 'domain' => 'boolean', 'label' => 'CONVENÇÕES', 'mask' => ['0' => '', '1' => 'CONVENÇÕES']],
			'CONVENIOS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'CONVÊNIOS', 'mask' => ['0' => '', '1' => 'CONVÊNIOS']],
			'DIRETORIA' => ['default' => 0, 'domain' => 'boolean', 'label' => 'DIRETORIA', 'mask' => ['0' => '', '1' => 'DIRETORIA']],
			'EDITAIS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'EDITAIS', 'mask' => ['0' => '', '1' => 'EDITAIS']],
			'ESTATUTO' => ['default' => 0, 'domain' => 'boolean', 'label' => 'ESTATUTO', 'mask' => ['0' => '', '1' => 'ESTATUTO']],
			'EVENTOS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'EVENTOS', 'mask' => ['0' => '', '1' => 'EVENTOS']],
			'FINANCAS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'FINANÇAS', 'mask' => ['0' => '', '1' => 'FINANÇAS']],
			'HISTORICO' => ['default' => 0, 'domain' => 'boolean', 'label' => 'HISTÓRICO', 'mask' => ['0' => '', '1' => 'HISTÓRICO']],
			'JORNAIS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'JORNAIS', 'mask' => ['0' => '', '1' => 'JORNAIS']],
			'JURIDICOS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'JURÍDICO', 'mask' => ['0' => '', '1' => 'JURÍDICO']],
			'NOTICIAS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'NOTÍCIAS', 'mask' => ['0' => '', '1' => 'NOTÍCIAS']],
			'PODCASTS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'PODCASTS', 'mask' => ['0' => '', '1' => 'PODCASTS']],
			'USUARIOS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'USUÁRIOS', 'mask' => ['0' => '', '1' => 'USUÁRIOS']],
			'VIDEOS' => ['default' => 0, 'domain' => 'boolean', 'label' => 'VÍDEOS', 'mask' => ['0' => '', '1' => 'VÍDEOS']]
		],
		'TEMPO' => ['default' => date('Y-m-d H:i:s'), 'domain' => 'string', 'label' => 'TEMPO', 'name' => 'tempo', 'unique' => false]
	];

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `USUARIOS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`NOME` VARCHAR(64) NOT NULL,
		`EMAIL` VARCHAR(64) NOT NULL UNIQUE,
		`SENHA` VARCHAR(32) NOT NULL,
		`ARQUIVOS` TINYINT(1) NOT NULL DEFAULT 0,
		`AVISOS` TINYINT(1) NOT NULL DEFAULT 0,
		`BANNERS` TINYINT(1) NOT NULL DEFAULT 0,
		`BOLETINS` TINYINT(1) NOT NULL DEFAULT 0,
		`CONVENCOES` TINYINT(1) NOT NULL DEFAULT 0,
		`CONVENIOS` TINYINT(1) NOT NULL DEFAULT 0,
		`DIRETORIA` TINYINT(1) NOT NULL DEFAULT 0,
		`EDITAIS` TINYINT(1) NOT NULL DEFAULT 0,
		`ESTATUTO` TINYINT(1) NOT NULL DEFAULT 0,
		`EVENTOS` TINYINT(1) NOT NULL DEFAULT 0,
		`FINANCAS` TINYINT(1) NOT NULL DEFAULT 0,
		`HISTORICO` TINYINT(1) NOT NULL DEFAULT 0,
		`JORNAIS` TINYINT(1) NOT NULL DEFAULT 0,
		`JURIDICOS` TINYINT(1) NOT NULL DEFAULT 0,
		`NOTICIAS` TINYINT(1) NOT NULL DEFAULT 0,
		`PODCASTS` TINYINT(1) NOT NULL DEFAULT 0,
		`USUARIOS` TINYINT(1) NOT NULL DEFAULT 0,
		`VIDEOS` TINYINT(1) NOT NULL DEFAULT 0,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = [
		'ID' => ['tag' => 'input', 'type' => 'number', 'attributes' => ['disabled' => 'disabled', 'readonly' => 'readonly']],
		'NOME' => ['tag' => 'input', 'type' => 'text', 'attributes' => ['autofocus' => 'autofocus', 'maxlength' => 64, 'minlength' => 4, 'required' => 'required']],
		'EMAIL' => ['tag' => 'input', 'type' => 'email', 'attributes' => ['maxlength' => 64, 'minlength' => 4, 'required' => 'required']],
		'SENHA' => ['tag' => 'input', 'type' => 'password', 'attributes' => ['minlength' => 4, 'required' => 'required']],
		'PERMISSAO' => ['tag' => 'input', 'type' => 'checkbox', 'labels' => ['Arquivos', 'Avisos', 'Banners', 'Boletins', 'Convenções', 'Convênios', 'Diretoria', 'Editais', 'Estatuto', 'Eventos', 'Finanças', 'Histórico', 'Jornais', 'Jurídicos', 'Notícias', 'Podcasts', 'Usuários', 'Vídeos'], 'names' => ['ARQUIVOS', 'AVISOS', 'BANNERS', 'BOLETINS', 'CONVENCOES', 'CONVENIOS', 'DIRETORIA', 'EDITAIS', 'ESTATUTO', 'EVENTOS', 'FINANCAS', 'HISTORICO', 'JORNAIS', 'JURIDICOS', 'NOTICIAS', 'PODCASTS', 'USUARIOS', 'VIDEOS'], 'values' => ['arquivos', 'avisos', 'banners', 'boletins', 'convencoes', 'convenios', 'diretoria', 'editais', 'estatuto', 'eventos', 'financas', 'historico', 'jornais', 'juridicos', 'noticias', 'podcasts', 'usuarios', 'videos'], 'attributes' => ['multiple' => 'multiple']],
		'TEMPO' => ['tag' => 'input', 'type' => 'hidden']
	];

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	$update['SENHA']['attributes'] = ['minlength' => 4, 'placeholder' => 'Opcional'];

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = [
		'ID' => ['tag' => 'p'],
		'NOME' => ['tag' => 'p'],
		'EMAIL' => ['tag' => 'p'],
		'SENHA' => ['tag' => 'p'],
		'PERMISSAO' => ['ARQUIVOS', 'AVISOS', 'BANNERS', 'BOLETINS', 'CONVENCOES', 'CONVENIOS', 'DIRETORIA', 'EDITAIS', 'ESTATUTO', 'EVENTOS', 'FINANCAS', 'HISTORICO', 'JORNAIS', 'JURIDICOS', 'NOTICIAS', 'PODCASTS', 'USUARIOS', 'VIDEOS']
	];

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
	unset($list['SENHA']);
