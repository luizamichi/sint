<?php

	// DESABILITA O ACESSO À PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
	if(count(get_included_files()) <= 1) {
		header('Location: ../index.php');
		exit;
	}

	// NOME DO SISTEMA
	define('SYSTEM_NAME', 'SINTEEMAR');

	// TÍTULO DO SISTEMA
	define('SYSTEM_TITLE', 'Sistema de Gerenciamento de Conteúdo');

	// DESCRIÇÃO DO SISTEMA
	define('SYSTEM_DESCRIPTION', 'O SGC é um sistema gestor de websites, portais e intranets que integra ferramentas necessárias para criar, gerir (editar e remover) conteúdos em tempo real, sem a necessidade de programação de código, cujo objetivo é estruturar e facilitar a criação, administração, distribuição, publicação e disponibilidade da informação. A sua maior característica é a grande quantidade de funções presentes.');

	// VERSÃO DO SISTEMA
	define('SYSTEM_VERSION', '1.2.1');

	// DATA DA REVISÃO
	define('RELEASE_DATE', '20/12/2019');

	// HOST DO BANCO DE DADOS
	define('MYSQL_HOST', 'sinteemar.com.br');

	// USUÁRIO DO BANCO DE DADOS
	define('MYSQL_USER', 'sint');

	// SENHA DO BANCO DE DADOS
	define('MYSQL_PASSWORD', 'Senha do banco de dados');

	// NOME DO BANCO DE DADOS
	define('MYSQL_SCHEMA', 'sint_2019');

	// NÚMERO MÁXIMO DE TENTATIVAS PARA CONEXÃO
	define('MYSQL_MAX_TRIES', 1);

	// CLASSES DO SISTEMA
	define('SYSTEM_MODELS', array_map(function(string $page): string {
		return mb_strtoupper(pathinfo($page, PATHINFO_FILENAME));
	}, array_diff(scandir(__DIR__ . '/models'), ['.', '..'])));

	$titles = ['' => SYSTEM_TITLE];

	// CAMINHOS DAS CLASSES DO SISTEMA
	define('SYSTEM_PAGES', array_merge(...array_map(function(string $model) use(&$titles): array {
		$page = __DIR__ . '/models/' . mb_strtolower($model) . '.php';
		include($page);
		$titles[$model] = $title ?? 'Indefinido';
		return [$model => $page];
	}, SYSTEM_MODELS)));

	// URL BASE DO SISTEMA
	$baseUrl = ($_SERVER['REQUEST_SCHEME'] ?? 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
	define('BASE_URL', $baseUrl);

	// ATIVA A SESSÃO DO PHP
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_name(SYSTEM_NAME);
		session_start();
		session_regenerate_id();
	}

	// MÉTODO DE REQUISIÇÃO - PADRÃO GET
	define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD'] ?? 'GET');

	// USUÁRIO AUTENTICADO NO SISTEMA - PADRÃO NULO
	define('LOGGED_USER', isset($_SESSION['USER']) ? unserialize($_SESSION['USER']) : null);

	// TEMPO DE ATIVIDADE NO SISTEMA
	define('ACTIVE_TIME', $_SESSION['ACTIVE_TIME'] ?? time());

	// AÇÕES DE CRUD PERMITIDAS NO SISTEMA
	define('CRUD_ACTIONS', [
		'CREATE', 'INSERT', // INSERÇÃO
		'LIST', 'SHOW', // LISTAGEM
		'DELETE', 'REMOVE', // REMOÇÃO
		'SELECT', 'VIEW', // VISUALIZAÇÃO
		'UPDATE', 'CHANGE' // ALTERAÇÃO
	]);

	// VALORES DE ENTRADA FORNECIDOS VIA GET
	define('GET_PARAMS', array_change_key_case($_GET, CASE_UPPER));

	// VALORES DE ENTRADA FORNECIDOS VIA POST
	define('POST_PARAMS', array_change_key_case($_POST, CASE_UPPER));

	// VALORES DE ENTRADA FORNECIDOS NO CORPO DA REQUISIÇÃO
	define('BODY_PARAMS', json_decode(file_get_contents('php://input')));

	// TÍTULOS DEFINIDOS PARA CADA CLASSE DO SISTEMA
	define('MODELS_TITLES', $titles);

	// ID SOLICITADO VIA GET OU POST
	define('REQUIRED_ID', (int) (POST_PARAMS['ID'] ?? GET_PARAMS['ID'] ?? 0));

	// PÁGINA SOLICITADA VIA GET
	$requiredPage = mb_strtoupper(GET_PARAMS['PAGE'] ?? '');
	define('REQUIRED_PAGE', in_array($requiredPage, SYSTEM_MODELS) ? $requiredPage : '');

	// AÇÃO DE CRUD SOLICITADA
	$requiredAction = mb_strtoupper(GET_PARAMS['ACTION'] ?? '');
	define('REQUIRED_ACTION', in_array($requiredAction, CRUD_ACTIONS) ? $requiredAction : '');

	// COLUNA DE PESQUISA SOLICITADA VIA GET
	$requiredColumn = mb_strtoupper(GET_PARAMS['COLUMN'] ?? '');
	define('REQUIRED_COLUMN', call_user_func(function(string $column, string $page): string {
		$path = __DIR__ . '/models/' . mb_strtolower($page) . '.php';
		if(!empty($page) && file_exists($path)) {
			include($path);
			return isset($columns[$column]) ? $column : '';
		}
		return '';
	}, $requiredColumn, REQUIRED_PAGE));

	// VALOR DE PESQUISA SOLICITADO VIA GET
	define('REQUIRED_VALUE', mb_strtoupper(GET_PARAMS['VALUE'] ?? ''));

	// SUBTÍTULOS DOS CRUDS PERMITIDOS NO SISTEMA
	define('CRUD_SUBTITLE', match(REQUIRED_ACTION) {
		'CREATE', 'INSERT' => 'Inserir',
		'LIST', 'SHOW' => 'Listar',
		'DELETE', 'REMOVE' => 'Remover',
		'SELECT', 'VIEW' => 'Visualizar',
		'UPDATE', 'CHANGE' => 'Atualizar',
		default => 'Gerenciar'
	});

	// DEFINE FUSO HORÁRIO E IDIOMA
	date_default_timezone_set('America/Sao_Paulo');
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese', 'pt_BR.iso-8859-1');

	// HORA E DATA ATUAL
	define('SYSDATE', date('Y-m-d H:i:s'));

	// INCLUI O DATA ACCESS OBJECT
	require_once(__DIR__ . '/dao.php');
