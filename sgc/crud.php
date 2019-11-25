<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/load.php');

	// ROTA RETORNA APENAS JSON COMO RESPOSTA
	header('Content-type: application/json; charset=utf-8');

	// INFORMOU A AÇÃO E PÁGINA CERTA, TAMBÉM POSSUI PERMISSÃO PARA GERENCIAMENTO DO MODELO
	if(in_array(REQUIRED_ACTION, CRUD_ACTIONS) && in_array(REQUIRED_PAGE, SYSTEM_MODELS) && LOGGED_USER[REQUIRED_PAGE]) {
		include(__DIR__ . '/models/' . mb_strtolower(REQUIRED_PAGE) . '.php');

		// VALIDA SE O USUÁRIO NÃO QUIS BURLAR OS DADOS
		$message = validate($_POST, $_FILES, REQUIRED_ACTION);
		if($message) {
			http_response_code(400);
			exit(json_encode([
				'message' => 'Não foi possível ' . mb_strtolower(CRUD_SUBTITLE) . ' o registro na tabela de ' . mb_strtolower($title) . '!' . PHP_EOL . PHP_EOL . implode(',' . PHP_EOL, $message) . '.'
			]));
		}

		// OPERAÇÃO DE LISTAGEM DE REGISTRO
		if(in_array(REQUIRED_ACTION, ['LIST', 'SHOW'])) {
			$rows = sqlRead(REQUIRED_PAGE);
			exit(json_encode([
				'message' => 'Foram encontrados: ' . count($rows) . ' ' . (count($rows) === 1 ? 'registro.' : 'registros.'),
				'data' => $rows
			]));
		}

		// OPERAÇÃO DE CONSULTA DE REGISTRO
		elseif(in_array(REQUIRED_ACTION, ['SELECT', 'VIEW']) && REQUIRED_ID > 0) {
			$rows = sqlRead(table: REQUIRED_PAGE, condition: 'ID = ' . REQUIRED_ID, unique: true);
			exit(json_encode([
				'message' => empty($rows) ? 'Não foi encontrado nenhum registro.' : 'O registro solicitado foi encontrado.',
				'data' => $rows
			]));
		}

		// OPERAÇÃO DE INSERÇÃO DE REGISTRO
		elseif(in_array(REQUIRED_ACTION, ['CREATE', 'INSERT']) && !empty(POST_PARAMS) && REQUIRED_ID > 0) {
			if(insert(table: REQUIRED_PAGE, attributes: $_POST, filesUpload: empty($_FILES) ? null : $_FILES, id: REQUIRED_ID)) {
				http_response_code(201);
				exit(json_encode([
					'message' => 'O registro foi inserido na tabela de ' . mb_strtolower($title) . '!'
				]));
			}

			else {
				http_response_code(400);
				exit(json_encode([
					'message' => 'Não foi possível inserir o registro na tabela de ' . mb_strtolower($title)
				]));
			}
		}

		// OPERAÇÃO DE REMOÇÃO DE REGISTRO
		elseif(in_array(REQUIRED_ACTION, ['DELETE', 'REMOVE']) && REQUIRED_ID > 0) {
			if(remove(REQUIRED_PAGE, REQUIRED_ID)) {
				http_response_code(200);
				exit(json_encode([
					'message' => 'O registro ' . REQUIRED_ID . ' foi removido da tabela de ' . mb_strtolower($title) . '!'
				]));
			}

			else {
				http_response_code(400);
				exit(json_encode([
					'message' => 'Não foi possível remover o registro ' . REQUIRED_ID . ' da tabela de ' . mb_strtolower($title) . '!'
				]));
			}
		}

		// OPERAÇÃO DE ATUALIZAÇÃO DE REGISTRO
		elseif(in_array(REQUIRED_ACTION, ['UPDATE', 'CHANGE']) && !empty(POST_PARAMS) && REQUIRED_ID > 0) {
			if(update(table: REQUIRED_PAGE, attributes: $_POST, filesUpload: empty($_FILES) ? null : $_FILES, id: REQUIRED_ID)) {
				http_response_code(200);
				exit(json_encode([
					'message' => 'O registro foi atualizado na tabela de ' . mb_strtolower($title) . '!'
				]));
			}

			else {
				http_response_code(400);
				exit(json_encode([
					'message' => 'Não foi possível atualizar o registro na tabela de ' . mb_strtolower($title) . '!'
				]));
			}
		}

		http_response_code(400);
		exit(json_encode([
			'message' => 'Não foi possível modificar o registro da tabela de ' . mb_strtolower($title) . '!'
		]));
	}

	// USUÁRIO NÃO POSSUI PERMISSÃO OU INFORMOU A PÁGINA E AÇÃO INCORRETAS
	else {
		http_response_code(400);
		exit(json_encode([
			'message' => 'A requisição está mal formulada ou você não possui permissão para realizar esta operação!'
		]));
	}


	// CRIA UM NOVO REGISTRO NO BANCO DE DADOS
	function insert(string $table, array $attributes, ?array $filesUpload, int $id): bool {
		global $hasFiles, $hasFolder, $columns, $insert;
		$directory = 'uploads/';
		$tuples = [];

		foreach($attributes as $attribute => $value) { // ATRIBUI REGISTROS DO VETOR PARA O MODELO
			if($columns[mb_strtoupper($attribute)]['unique'] && sqlRead(table: $table, condition: mb_strtoupper($attribute) . ' = "' . $value . '"', unique: true)) { // VERIFICA SE O ATRIBUTO É EXCLUSIVO (ÚNICO)
				return false;
			}

			if(isset($insert[mb_strtoupper($attribute)]['attributes']['multiple']) && $insert[mb_strtoupper($attribute)]['attributes']['multiple']) { // O ATRIBUTO É UM VETOR
				foreach($attributes[$attribute] as $attr) {
					if($insert[mb_strtoupper($attribute)]['type'] === 'checkbox') {
						$tuples[mb_strtoupper($attr)] = '"1"';
					}
				}
			}

			elseif(isset($columns[mb_strtoupper($attribute)]['hash'])) { // O ATRIBUTO DEVE SER CRIPTOGRAFADO
				$tuples[mb_strtoupper($attribute)] = '"' . md5($_POST[$attribute]) . '"';
			}

			else {
				$tuples[mb_strtoupper($attribute)] = strlen($_POST[$attribute]) === 0 ? 'NULL' : '"' . trim(addslashes($_POST[$attribute])) . '"';
			}
		}

		if($hasFolder) { // INSERE ARQUIVOS EM UM NOVO DIRETÓRIO
			global $folder;
			$directory = 'uploads/' . mb_strtolower($table) . date('YmdHis') . bin2hex(random_bytes(10)) . '/';
			mkdir('../' . $directory);

			for($i = 0; $i < count($filesUpload[mb_strtolower($folder)]['name']); $i++) {
				$filename = explode('.', $filesUpload[mb_strtolower($folder)]['name'][$i]);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				uploadFile($filesUpload[mb_strtolower($folder)]['tmp_name'][$i], '../' . $filename);
			}
			$tuples[$folder] = '"' . $directory . '"';
		}

		if($hasFiles) { // INSERE ARQUIVOS NA PASTA DE UPLOADS
			global $files;

			foreach($files as $file) {
				$filename = explode('.', $filesUpload[mb_strtolower($file)]['name']);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				uploadFile($filesUpload[mb_strtolower($file)]['tmp_name'], '../' . $filename);

				if(!empty($filesUpload[mb_strtolower($file)]['name'])) {
					$tuples[$file] = '"' . $filename . '"';
				}
			}
		}

		$fields = implode(', ', array_keys($tuples));
		$values = implode(', ', array_values($tuples));
		$result = sqlInsert(table: $table, fields: $fields, values: $values);

		if($result) {
			sqlInsert(table: 'REGISTROS', fields: 'OPERACAO, TABELA, ERRO, REGISTRO, USUARIO, DATA', values: '"CADASTRO", "' . $table . '", "' . print_r(error_get_last(), true) . '", ' . $id . ', ' . LOGGED_USER['ID'] . ', "' . SYSDATE . '"'); // GRAVA O LOG DA OPERAÇÃO DE INSERÇÃO
		}
		return (bool) $result;
	}


	// ALTERA UM REGISTRO DO BANCO DE DADOS
	function update(string $table, array $attributes, ?array $filesUpload, int $id): bool {
		global $hasFiles, $hasFolder, $columns, $update;
		$directory = 'uploads/';
		$tuples = [];

		foreach($attributes as $attribute => $value) { // ATRIBUI REGISTROS DO VETOR PARA O MODELO
			if($columns[mb_strtoupper($attribute)]['unique']) { // VERIFICA SE O ATRIBUTO É EXCLUSIVO (ÚNICO)
				$tuple = sqlRead(table: $table, condition: mb_strtoupper($attribute) . ' = "' . $value . '"', unique: true);
				if($tuple && (int) $tuple['ID'] !== $id) {
					return false;
				}
			}

			if(isset($update[mb_strtoupper($attribute)]['attributes']['multiple']) && $update[mb_strtoupper($attribute)]['attributes']['multiple']) { // O ATRIBUTO É UM VETOR
				foreach($update[mb_strtoupper($attribute)]['names'] as $i) {
					$tuples[$i] = 0;
				}
				foreach($attributes[$attribute] as $attr) {
					if($update[mb_strtoupper($attribute)]['type'] === 'checkbox') {
						$tuples[mb_strtoupper($attr)] = '"1"';
					}
				}
			}

			elseif(isset($columns[mb_strtoupper($attribute)]['hash'])) { // O ATRIBUTO DEVE SER CRIPTOGRAFADO
				if(strlen($_POST[$attribute]) !== 0) {
					$tuples[mb_strtoupper($attribute)] = '"' . md5($_POST[$attribute]) . '"';
				}
				else {
					$tuples[mb_strtoupper($attribute)] = '"' . sqlRead(table: $table, condition: 'ID = "' . $id . '"', unique: true)[mb_strtoupper($attribute)] . '"';
				}
			}

			else {
				$tuples[mb_strtoupper($attribute)] = strlen($_POST[$attribute]) === 0 ? 'NULL' : '"'. trim(addslashes($_POST[$attribute])) . '"';
			}
		}

		$register = sqlRead(table: $table, condition: 'ID = "' . $id . '"', unique: true);

		if($hasFolder) { // INSERE ARQUIVOS EM UM NOVO DIRETÓRIO
			global $folder;
			$directory = $register[$folder];

			for($i = 0; $i < count($filesUpload[mb_strtolower($folder)]['name']); $i++) {
				$filename = explode('.', $filesUpload[mb_strtolower($folder)]['name'][$i]);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				uploadFile($filesUpload[mb_strtolower($folder)]['tmp_name'][$i], '../' . $filename);
			}
			$tuples[$folder] = '"' . $directory . '"';
		}

		if($hasFiles) { // INSERE ARQUIVOS NA PASTA DE UPLOADS
			global $files;

			foreach($files as $file) {
				$filename = explode('.', $filesUpload[mb_strtolower($file)]['name']);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				uploadFile($filesUpload[mb_strtolower($file)]['tmp_name'], '../' . $filename);

				if(!empty($filesUpload[mb_strtolower($file)]['name'])) {
					$tuples[$file] = '"' . $filename . '"';
				}
			}
		}

		$tuples = array_diff_key($tuples, ['ID' => REQUIRED_ID]);
		$fields = implode(', ', array_map(fn(string $key, int|string $value) => associative($key, $value, true), array_keys($tuples), array_values($tuples)));

		$result = sqlUpdate(table: $table, fields: $fields, condition: 'ID = ' . $id);

		if($result) {
			sqlInsert(table: 'REGISTROS', fields: 'OPERACAO, TABELA, ERRO, REGISTRO, USUARIO, DATA', values: '"ALTERAÇÃO", "' . $table . '", "' . print_r(error_get_last(), true) . '", ' . $id . ', ' . LOGGED_USER['ID'] . ', "' . SYSDATE . '"'); // GRAVA O LOG DA OPERAÇÃO DE ALTERAÇÃO
		}
		return (bool) $result;
	}


	// REMOVE UM REGISTRO DO BANCO DE DADOS
	function remove(string $table, int $id): bool {
		$register = sqlRead(table: $table, condition: 'ID = ' . $id, unique: true);

		if($register) {
			global $hasFiles, $hasFolder;
			if($hasFiles) { // POSSUI ALGUM ARQUIVO SALVO EM DISCO
				global $files;
				foreach($files as $file) {
					removeFile('../' . $register[$file]);
				}
			}

			elseif($hasFolder) { // POSSUI ALGUM DIRETÓRIO COM ARQUIVOS
				global $folder;
				foreach(array_slice(scandir(__DIR__ . '/../' . $register[$folder]), 2) as $file) {
					removeFile('../' . $register[$folder] . $file);
				}
				removeFolder('../' . $register[$folder]);
			}

			$result = sqlRemove(table: $table, field: 'ID', value: $id);

			if($result) {
				sqlInsert(table: 'REGISTROS', fields: 'OPERACAO, TABELA, ERRO, REGISTRO, USUARIO, DATA', values: '"REMOÇÃO", "' . $table . '", "' . print_r(error_get_last(), true) . '", ' . $id . ', ' . LOGGED_USER['ID'] . ', "' . SYSDATE . '"'); // GRAVA O LOG DA OPERAÇÃO DE REMOÇÃO
			}
			return (bool) $result;
		}

		return false;
	}


	// REMOVE UM ARQUIVO FÍSICO DO SERVIDOR
	function removeFile(string $file): bool {
		return is_file($file) && unlink($file);
	}


	// REMOVE UM DIRETÓRIO DO SERVIDOR
	function removeFolder(string $folder): bool {
		return is_dir($folder) && rmdir($folder);
	}


	// SALVA UM ARQUIVO TEMPORÁRIO ENVIADO AO SERVIDOR
	function uploadFile(string $temporaryName, string $destinyName): bool {
		return move_uploaded_file($temporaryName, $destinyName);
	}


	// VALIDA OS ATRIBUTOS (DADOS) FORNECIDOS PELO USUÁRIO
	function validate(array $attributes, array $files, string $action): array {
		global $columns;
		$message = [];

		if(in_array($action, ['CREATE', 'INSERT'])) {
			global $insert;
			$rules = $insert;
		}
		elseif(in_array($action, ['UPDATE', 'CHANGE'])) {
			global $update;
			$rules = $update;
		}

		foreach($attributes as $attribute => $value) {
			$attr = mb_strtoupper($attribute);

			if(in_array($attr, array_keys($columns))) {
				$maxlength = $rules[$attr]['attributes']['maxlength'] ?? INF;
				$minlength = $rules[$attr]['attributes']['minlength'] ?? 0;
				$min = $rules[$attr]['attributes']['min'] ?? 0;
				$max = $rules[$attr]['attributes']['max'] ?? INF;
				$required = $rules[$attr]['attributes']['required'] ?? false;

				if(isset($rules[$attr]['type'])) { // VALIDA SE OS TIPOS (INPUT) ESTÃO CORRETOS (COLOR, DATE, DATETIME-LOCAL, EMAIL, FILE, HIDDEN, NUMBER, PASSWORD, RADIO, RANGE, SEARCH, TEL, TEXT, TIME, URL, WEEK)
					if(in_array($rules[$attr]['type'], ['password', 'search', 'tel', 'text'])) {
						if(!is_string($value)) {
							array_push($message, $columns[$attr]['label'] . ' não é um texto');
						}
						elseif(((strlen($value) < $minlength) || (strlen($value) > $maxlength)) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
						}
					}

					elseif(in_array($rules[$attr]['type'], ['number', 'range'])) {
						if(!is_numeric($value) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é um número');
						}
						elseif($value < $min || $value > $max) {
							array_push($message, $columns[$attr]['label'] . ' possui valor inválido');
						}
					}

					elseif($rules[$attr]['type'] === 'email') {
						if(!filter_var($value, FILTER_VALIDATE_EMAIL) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é um e-mail');
						}
						elseif(((strlen($value) <= $minlength) || (strlen($value) >= $maxlength)) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
						}
					}

					elseif($rules[$attr]['type'] === 'url') {
						if(!filter_var($value, FILTER_VALIDATE_URL) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é uma URL');
						}
						elseif(((strlen($value) <= $minlength) || (strlen($value) >= $maxlength)) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
						}
					}

					elseif($rules[$attr]['type'] === 'time') {
						if($min === 0) {
							unset($min);
						}
						if($max === INF) {
							unset($max);
						}
						if(!preg_match('/^(?:2[0-3]|[01]\d):[0-5]\d$/', $value) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é um horário');
						}
						elseif(isset($min, $max) && (strtotime($value) > strtotime($max) || strtotime($value) < strtotime($min))) {
							array_push($message, $columns[$attr]['label'] . ' possui valor inválido');
						}
					}

					elseif($rules[$attr]['type'] === 'date') {
						if($min === 0) {
							unset($min);
						}
						if($max === INF) {
							unset($max);
						}
						$date = explode('-', $value);
						if((count($date) !== 3 || !checkdate((int) $date[1], (int) $date[2], (int) $date[0])) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é uma data');
						}
						elseif(isset($min, $max) && (strtotime($value) > strtotime($max) || strtotime($value) < strtotime($min))) {
							array_push($message, $columns[$attr]['label'] . ' possui valor inválido');
						}
					}

					elseif($rules[$attr]['type'] === 'datetime-local') {
						if($min === 0) {
							unset($min);
						}
						if($max === INF) {
							unset($max);
						}
						$date = substr($value, 0, 10);
						$time = substr($value, 11, 5);
						$token = explode('-', $date);
						if((count($token) !== 3 || !checkdate((int) $token[1], (int) $token[2], (int) $token[0]) || !preg_match('/^(?:2[0-3]|[01]\d):[0-5]\d$/', $time)) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é um tempo');
						}
						elseif(isset($min, $max) && (strtotime($date . $time) > strtotime($max) || strtotime($date . $time) < strtotime($min))) {
							array_push($message, $columns[$attr]['label'] . ' possui valor inválido');
						}
					}

					elseif($rules[$attr]['type'] === 'week') {
						if($min === 0) {
							unset($min);
						}
						if($max === INF) {
							unset($max);
						}
						if(date('Y-m-d', strtotime($value)) === '1969-12-31' && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é uma semana');
						}
						elseif(isset($min, $max) && (strtotime($value) > strtotime($max) || strtotime($value) < strtotime($min))) {
							array_push($message, $columns[$attr]['label'] . ' possui valor inválido');
						}
					}

					elseif($rules[$attr]['type'] === 'color') {
						$color = substr($value, 1);
						if((!ctype_xdigit($color) || !in_array(strlen($color), [3, 6])) && ($required || !empty($value))) {
							array_push($message, $columns[$attr]['label'] . ' não é uma cor');
						}
					}

					elseif($rules[$attr]['type'] === 'radio') {
						if($required && empty($value)) {
							array_push($message, $columns[$attr]['label'] . ' não foi selecionado');
						}
					}

					elseif($rules[$attr]['type'] === 'hidden') {
						if($required && empty($value)) {
							array_push($message, $columns[$attr]['label'] . ' não possui valor');
						}
					}
				}

				elseif($rules[$attr]['tag'] === 'textarea') { // VALIDA SE O TAMANHO DO CAMPO (TEXTAREA) É VÁLIDO
					$value = trim(strip_tags(html_entity_decode($value)));
					if(!is_string($value)) {
						array_push($message, $columns[$attr]['label'] . ' não é um texto');
					}
					elseif(((strlen($value) <= $minlength) || (strlen($value) >= $maxlength)) && ($required || !empty($value))) {
						array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
					}
				}
			}
		}

		if(!empty($files)) {
			$problems = [];
			foreach($files as $attr => $file) {
				if(is_array($file['name'])) {
					foreach($file['error'] as $error) {
						if($error !== UPLOAD_ERR_OK) {
							$problems[$attr] = isset($problems[$attr]) ? [...$problems[$attr], $error] : [$error];
						}
					}
				}
				elseif($file['error'] !== UPLOAD_ERR_OK) {
					$problems[$attr] = isset($problems[$attr]) ? [...$problems[$attr], $file['error']] : [$file['error']];
				}
			}
			if(!empty($problems)) {
				foreach($problems as $attribute => $problem) {
					$text = [];
					$required = $rules[$attr]['attributes']['required'] ?? false;
					$attr = mb_strtoupper($attribute);
					$problem = array_count_values($problem);

					array_push($text, isset($problem[UPLOAD_ERR_INI_SIZE]) ? $problem[UPLOAD_ERR_INI_SIZE] . ' arquivo(s) enviado(s) que excede(m) o limite do PHP' : '');
					array_push($text, isset($problem[UPLOAD_ERR_FORM_SIZE]) ? $problem[UPLOAD_ERR_FORM_SIZE] . ' arquivo(s) enviado(s) que excede(m) o limite do formulário HTML' : '');
					array_push($text, isset($problem[UPLOAD_ERR_PARTIAL]) ? $problem[UPLOAD_ERR_PARTIAL] . ' upload(s) feito(s) parcialmente' : '');
					array_push($text, isset($problem[UPLOAD_ERR_NO_FILE]) ? 'nenhum arquivo enviado' : '');
					array_push($text, isset($problem[UPLOAD_ERR_NO_TMP_DIR]) ? 'pasta temporária ausente' : '');
					array_push($text, isset($problem[UPLOAD_ERR_CANT_WRITE]) ? 'falha ao escrever ' . $problem[UPLOAD_ERR_CANT_WRITE] . ' arquivo(s) no disco' : '');
					array_push($text, isset($problem[UPLOAD_ERR_EXTENSION]) ? 'uma extensão do PHP que interrompeu o upload de ' . $problem[UPLOAD_ERR_EXTENSION] . ' arquivo(s)' : '');

					$text = array_filter($text);
					if(count($text) > 0 && $required) {
						array_push($message, $columns[$attr]['label'] . ' possui ' . implode(', ', $text));
					}
				}
			}
		}

		return $message;
	}
