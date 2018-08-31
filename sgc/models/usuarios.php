<?php
	// TÍTULO DA PÁGINA
	$title = 'Usuários';

	// ÍCONE
	$favicon = 'img/usuario.svg';

	// TABELA NO BANCO DE DADOS
	$table = 'USUARIOS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = false;
	$hasFolder = false;

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'NOME'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'NOME', 'name'=> 'nome', 'unique'=> false),
		'EMAIL'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'E-MAIL', 'name'=> 'email', 'unique'=> true),
		'SENHA'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'SENHA', 'name'=> 'senha', 'hash'=> true, 'unique'=> false),
		'PERMISSAO'=> array('default'=> array(), 'domain'=> 'list', 'label'=> 'PERMISSÃO', 'name'=> 'permissao[]', 'unique'=> false,
			'BANNERS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'BANNERS', 'mask'=> array('0'=> '', '1'=> 'BANNERS'), 'name'=> 'permissao[]'),
			'BOLETINS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'BOLETINS', 'mask'=> array('0'=> '', '1'=> 'BOLETINS'), 'name'=> 'permissao[]'),
			'CONVENCOES'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'CONVENÇÕES', 'mask'=> array('0'=> '', '1'=> 'CONVENÇÕES'), 'name'=> 'permissao[]'),
			'CONVENIOS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'CONVÊNIOS', 'mask'=> array('0'=> '', '1'=> 'CONVÊNIOS'), 'name'=> 'permissao[]'),
			'DIRETORIA'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'DIRETORIA', 'mask'=> array('0'=> '', '1'=> 'DIRETORIA'), 'name'=> 'permissao[]'),
			'EDITAIS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'EDITAIS', 'mask'=> array('0'=> '', '1'=> 'EDITAIS'), 'name'=> 'permissao[]'),
			'ESTATUTO'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'ESTATUTO', 'mask'=> array('0'=> '', '1'=> 'ESTATUTO'), 'name'=> 'permissao[]'),
			'EVENTOS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'EVENTOS', 'mask'=> array('0'=> '', '1'=> 'EVENTOS'), 'name'=> 'permissao[]'),
			'FINANCAS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'FINANÇAS', 'mask'=> array('0'=> '', '1'=> 'FINANÇAS'), 'name'=> 'permissao[]'),
			'HISTORICO'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'HISTÓRICO', 'mask'=> array('0'=> '', '1'=> 'HISTÓRICO'), 'name'=> 'permissao[]'),
			'JORNAIS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'JORNAIS', 'mask'=> array('0'=> '', '1'=> 'JORNAIS'), 'name'=> 'permissao[]'),
			'JURIDICOS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'JURÍDICO', 'mask'=> array('0'=> '', '1'=> 'JURÍDICO'), 'name'=> 'permissao[]'),
			'NOTICIAS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'NOTÍCIAS', 'mask'=> array('0'=> '', '1'=> 'NOTÍCIAS'), 'name'=> 'permissao[]'),
			'USUARIOS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'USUÁRIOS', 'mask'=> array('0'=> '', '1'=> 'USUÁRIOS'), 'name'=> 'permissao[]'),
			'VIDEOS'=> array('default'=> 0, 'domain'=> 'boolean', 'label'=> 'VÍDEOS', 'mask'=> array('0'=> '', '1'=> 'VÍDEOS'), 'name'=> 'permissao[]'),
		),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `USUARIOS`(
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`NOME` VARCHAR(64) NOT NULL,
		`EMAIL` VARCHAR(64) NOT NULL UNIQUE,
		`SENHA` VARCHAR(32) NOT NULL,
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
		`USUARIOS` TINYINT(1) NOT NULL DEFAULT 0,
		`VIDEOS` TINYINT(1) NOT NULL DEFAULT 0
	)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('readonly'=> 'readonly')),
		'NOME'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 64, 'minlength'=> 4, 'required'=> 'required')),
		'EMAIL'=> array('tag'=> 'input', 'type'=> 'email', 'attributes'=> array('maxlength'=> 64, 'minlength'=> 4, 'required'=> 'required')),
		'SENHA'=> array('tag'=> 'input', 'type'=> 'password', 'attributes'=> array('minlength'=> 4, 'required'=> 'required')),
		'PERMISSAO'=> array('tag'=> 'input', 'type'=> 'checkbox', 'labels'=> array('Banners', 'Boletins', 'Convenções', 'Convênios', 'Diretoria', 'Editais', 'Estatuto', 'Eventos', 'Finanças', 'Histórico', 'Jornais', 'Jurídicos', 'Notícias', 'Usuários', 'Vídeos'), 'names'=> array('BANNERS', 'BOLETINS', 'CONVENCOES', 'CONVENIOS', 'DIRETORIA', 'EDITAIS', 'ESTATUTO', 'EVENTOS', 'FINANCAS', 'HISTORICO', 'JORNAIS', 'JURIDICOS', 'NOTICIAS', 'USUARIOS', 'VIDEOS'), 'values'=> array('banners', 'boletins', 'convencoes', 'convenios', 'diretoria', 'editais', 'estatuto', 'eventos', 'financas', 'historico', 'jornais', 'juridicos', 'noticias', 'usuarios', 'videos'), 'attributes'=> array('multiple'=> 'multiple')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	$update['SENHA']['attributes'] = array('minlength'=> 4, 'placeholder'=> 'Opcional');

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'NOME'=> array('tag'=> 'p'),
		'EMAIL'=> array('tag'=> 'p'),
		'SENHA'=> array('tag'=> 'p'),
		'PERMISSAO'=> array('BANNERS', 'BOLETINS', 'CONVENCOES', 'CONVENIOS', 'DIRETORIA', 'EDITAIS', 'ESTATUTO', 'EVENTOS', 'FINANCAS', 'HISTORICO', 'JORNAIS', 'JURIDICOS', 'NOTICIAS', 'USUARIOS', 'VIDEOS'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
	unset($list['PERMISSAO']);
?>