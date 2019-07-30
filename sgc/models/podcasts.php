<?php
	// TÍTULO DA PÁGINA
	$title = 'Podcasts';

	// ÍCONE
	$favicon = 'img/models/podcast.png';

	// TABELA NO BANCO DE DADOS
	$table = 'PODCASTS';

	// DEFINIÇÃO DE ARQUIVOS E DIRETÓRIOS
	$hasFiles = true;
	$files = array('AUDIO');
	$hasFolder = false;

	// TEXTO DE AJUDA
	$help = 'Os podcasts permitem o anexo de áudio no formato MP3, que são disponibilizados para as pessoas escutarem e também para fazerem o download do arquivo. Na página principal é exibido o último podcast publicado.';

	// COLUNAS DO REGISTRO NO BANCO DE DADOS
	$columns = array(
		'ID'=> array('default'=> 1, 'domain'=> 'integer', 'label'=> 'ID', 'name'=> 'id', 'unique'=> true),
		'TITULO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'TÍTULO', 'name'=> 'titulo', 'unique'=> false),
		'AUDIO'=> array('default'=> '', 'domain'=> 'string', 'label'=> 'ÁUDIO', 'name'=> 'audio', 'unique'=> false),
		'DATA'=> array('default'=> date('Y-m-d'), 'domain'=> 'string', 'label'=> 'DATA', 'name'=> 'data', 'unique'=> false),
		'HORA'=> array('default'=> date('H:i'), 'domain'=> 'string', 'label'=> 'HORA', 'name'=> 'hora', 'unique'=> false),
		'TEMPO'=> array('default'=> date('Y-m-d H:i:s'), 'domain'=> 'string', 'label'=> 'TEMPO', 'name'=> 'tempo', 'unique'=> false),
	);

	// INFORMAÇÕES DO REGISTRO NO BANCO DE DADOS
	$sql = 'CREATE TABLE IF NOT EXISTS `PODCASTS` (
		`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`TITULO` VARCHAR(64) NOT NULL,
		`AUDIO` VARCHAR(64) NOT NULL,
		`DATA` DATE NOT NULL DEFAULT CURRENT_DATE,
		`HORA` TIME NOT NULL DEFAULT CURRENT_TIME,
		`TEMPO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;';

	// INFORMAÇÕES PARA INSERÇÃO DE REGISTRO
	$insert = array(
		'ID'=> array('tag'=> 'input', 'type'=> 'number', 'attributes'=> array('disabled'=> 'disabled', 'readonly'=> 'readonly')),
		'TITULO'=> array('tag'=> 'input', 'type'=> 'text', 'attributes'=> array('autofocus'=> 'autofocus', 'maxlength'=> 128, 'minlength'=> 4, 'required'=> 'required')),
		'AUDIO'=> array('tag'=> 'input', 'type'=> 'file', 'attributes'=> array('accept'=> 'audio/mp3', 'required'=> 'required')),
		'DATA'=> array('tag'=> 'input', 'type'=> 'date', 'attributes'=> array('data-mask'=> '0000-00-00', 'required'=> 'required')),
		'HORA'=> array('tag'=> 'input', 'type'=> 'time', 'attributes'=> array('data-mask'=> '00:00', 'required'=> 'required')),
		'TEMPO'=> array('tag'=> 'input', 'type'=> 'hidden', 'attributes'=> array('required'=> 'required')),
	);

	// INFORMAÇÕES PARA ALTERAÇÃO DE REGISTRO
	$update = $insert;
	unset($update['AUDIO']['attributes']['required']);

	// INFORMAÇÕES PARA VISUALIZAÇÃO DE REGISTRO ÚNICO
	$view = array(
		'ID'=> array('tag'=> 'p'),
		'TITULO'=> array('tag'=> 'p'),
		'AUDIO'=> array('tag'=> 'audio'),
		'DATA'=> array('tag'=> 'p'),
		'HORA'=> array('tag'=> 'p'),
	);

	// INFORMAÇÕES PARA LISTAGEM DE REGISTROS
	$list = $view;
?>