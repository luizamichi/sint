<?php
	if(session_status() != PHP_SESSION_ACTIVE) { // ACESSO SOMENTE COM UMA SESSÃO CRIADA COM OS DADOS DO USUÁRIO
		session_name('SINTEEMAR');
		session_start();
	}

	if(isset($_SESSION['user'])) // VERIFICA SE O USUÁRIO ESTÁ LOGADO
		$user = unserialize($_SESSION['user']);
	else {
		header('Location: panel.php');
		return false;
	}

	// DEFINE FUSO HORÁRIO E IDIOMA
	date_default_timezone_set('America/Sao_Paulo');
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese', 'pt_BR.iso-8859-1');

	// INSERE IMAGEM E COR DE FUNDO
	echo '<style>html { background-attachment: fixed; background-color: #1b5e20; background-image: url("../img/sgc.svg"); background-position: center; background-repeat: no-repeat; background-size: 300px 300px; }</style>';

	// AÇÕES PERMITIDAS NO CRUD
	$actions = array('insert', 'remove', 'update');

	// NOME DAS PÁGINAS DISPONÍVEIS
	$models = array_map(function($p) { return explode('.php', $p)[0]; }, array_slice(scandir('models'), 2));

	// VALORES PASSADOS POR GET
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);


	if(isset($_REQUEST) && $action && in_array($action, $actions) && $page && in_array($page, $models) && $user[strtoupper($page)]) { // INFORMOU A AÇÃO E PÁGINA CERTA, TAMBÉM POSSUI PERMISSÃO PARA GERENCIAMENTO DO MODELO
		include('models/' . $page . '.php');
		include('dao.php');

		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		$id = $id ? $id : filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

		if(!validate($_POST, $action, $page, $id)) // VALIDA SE O USUÁRIO NÃO QUIS BURLAR OS DADOS
			return false;

		if(strcmp($action, 'insert') == 0 && !empty($_POST) && $id && $id > 0) { // OPERAÇÃO DE INSERÇÃO DE REGISTRO
			if(insert($table=$table, $attributes=$_POST, $files_upload=empty($_FILES) ? null : $_FILES, $id)) {
				echo '<script>
					alert("O registro foi inserido na tabela de ' . mb_strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=insert";
					</script>';
				return true;
			}

			else {
				echo '<script>
					alert("Não foi possível inserir o registro na tabela de ' . mb_strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=insert";
					</script>';
				return false;
			}
		}

		elseif(strcmp($action, 'remove') == 0 && $id && $id > 0) { // OPERAÇÃO DE REMOÇÃO DE REGISTRO
			if(remove($table, $id)) {
				echo '<script>
					alert("O registro ' . $id . ' foi removido da tabela de ' . mb_strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=list";
					</script>';
				return true;
			}

			else {
				echo '<script>
					alert("Não foi possível remover o registro ' . $id . ' da tabela de ' . mb_strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=list";
					</script>';
				return false;
			}
		}

		elseif(strcmp($action, 'update') == 0 && !empty($_POST) && $id && $id > 0) { // OPERAÇÃO DE ATUALIZAÇÃO DE REGISTRO
			if(update($table=$table, $attributes=$_POST, $files_upload=empty($_FILES) ? null : $_FILES, $id)) {
				echo '<script>
					alert("O registro foi atualizado na tabela de ' . mb_strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=update&column=id&value=' . $id . '";
					</script>';
				return true;
			}

			else {
				echo '<script>
					alert("Não foi possível atualizar o registro na tabela de ' . mb_strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=update&column=id&value=' . $id . '";
					</script>';
				return false;
			}
		}

		echo '<script>
			alert("Não foi possível modificar o registro da tabela de ' . mb_strtolower($title) . '!");
			window.location.href = "panel.php?page=' . $page . '&action=' . $action . '";
			</script>';
		return false;
	}

	else // USUÁRIO NÃO POSSUI PERMISSÃO OU INFORMOU A PÁGINA E AÇÃO INCORRETAS
		echo '<script>window.location.href = "panel.php";</script>';


	// CRIA UM NOVO REGISTRO NO BANCO DE DADOS
	function insert($table, $attributes, $files_upload, $id) {
		global $hasFiles, $hasFolder, $columns, $insert, $user;
		$directory = 'uploads/';
		$tuples = array();

		foreach($attributes as $attribute => $value) { // ATRIBUI REGISTROS DO VETOR PARA O MODELO
			if($columns[strtoupper($attribute)]['unique']) { // VERIFICA SE O ATRIBUTO É EXCLUSIVO (ÚNICO)
				if(sql_read($table=$table, $condition=strtoupper($attribute) . '="' . $value . '"', $unique=true))
					return false;
			}

			if(isset($insert[strtoupper($attribute)]['attributes']['multiple']) && $insert[strtoupper($attribute)]['attributes']['multiple']) { // O ATRIBUTO É UM VETOR
				foreach($attributes[$attribute] as $attr) {
					if(strcmp($insert[strtoupper($attribute)]['type'], 'checkbox') == 0)
						$tuples[strtoupper($attr)] = '"1"';
				}
			}

			elseif(isset($columns[strtoupper($attribute)]['hash'])) // O ATRIBUTO DEVE SER CRIPTOGRAFADO
				$tuples[strtoupper($attribute)] = '"' . md5($_POST[$attribute]) . '"';

			else
				$tuples[strtoupper($attribute)] = empty($_POST[$attribute]) ? 'NULL' : trim('"' . addslashes($_POST[$attribute]) . '"');
		}

		if($hasFolder) { // INSERE ARQUIVOS EM UM NOVO DIRETÓRIO
			global $folder;
			$directory = 'uploads/' . strtolower($table) . date('YmdHis') . bin2hex(random_bytes(10)) . '/';
			mkdir('../' . $directory);

			for($i = 0; $i < count($files_upload[strtolower($folder)]['name']); $i++) {
				$filename = explode('.', $files_upload[strtolower($folder)]['name'][$i]);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				upload_file($files_upload[strtolower($folder)]['tmp_name'][$i], '../' . $filename);
			}
			$tuples[$folder] = '"' . $directory . '"';
		}

		if($hasFiles) { // INSERE ARQUIVOS NA PASTA DE UPLOADS
			global $files;

			foreach($files as $file) {
				$filename = explode('.', $files_upload[strtolower($file)]['name']);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				upload_file($files_upload[strtolower($file)]['tmp_name'], '../' . $filename);

				if(!empty($files_upload[strtolower($file)]['name']))
					$tuples[$file] = '"' . $filename . '"';
			}
		}

		$fields = implode(', ', array_keys($tuples));
		$values = implode(', ', array_values($tuples));
		$result = sql_insert($table=$table, $fields=$fields, $values=$values);

		sql_insert('REGISTROS', $fields='OPERACAO, TABELA, REGISTRO, USUARIO, DATA', $values='"CADASTRO", "' . $table . '", ' . $id . ', ' . $user['ID'] . ', "' . date('Y-m-d H:i:s') . '"'); // GRAVA O LOG DA OPERAÇÃO DE INSERÇÃO
		return (bool) $result;
	}


	// ALTERA UM REGISTRO DO BANCO DE DADOS
	function update($table, $attributes, $files_upload, $id) {
		global $hasFiles, $hasFolder, $columns, $update, $user;
		$directory = 'uploads/';
		$tuples = array();

		foreach($attributes as $attribute => $value) { // ATRIBUI REGISTROS DO VETOR PARA O MODELO
			if($columns[strtoupper($attribute)]['unique']) { // VERIFICA SE O ATRIBUTO É EXCLUSIVO (ÚNICO)
				$tuple = sql_read($table=$table, $condition=strtoupper($attribute) . '="' . $value . '"', $unique=true);
				if($tuple && $tuple['ID'] != $id)
					return false;
			}

			if(isset($update[strtoupper($attribute)]['attributes']['multiple']) && $update[strtoupper($attribute)]['attributes']['multiple']) { // O ATRIBUTO É UM VETOR
				foreach($update[strtoupper($attribute)]['names'] as $i)
					$tuples[$i] = 0;
				foreach($attributes[$attribute] as $attr) {
					if(strcmp($update[strtoupper($attribute)]['type'], 'checkbox') == 0)
						$tuples[strtoupper($attr)] = '"1"';
				}
			}

			elseif(isset($columns[strtoupper($attribute)]['hash'])) { // O ATRIBUTO DEVE SER CRIPTOGRAFADO
				if(!empty($_POST[$attribute]))
					$tuples[strtoupper($attribute)] = '"' . md5($_POST[$attribute]) . '"';
				else
					$tuples[strtoupper($attribute)] = '"' . sql_read($table=$table, $condition='ID="' . $id . '"', $unique=true)[strtoupper($attribute)] . '"';
			}

			else
				$tuples[strtoupper($attribute)] = empty($_POST[$attribute]) ? 'NULL' : trim('"'. addslashes($_POST[$attribute]) . '"');
		}

		$register = sql_read($table=$table, $condition='ID="' . $id . '"', $unique=true);

		if($hasFolder) { // INSERE ARQUIVOS EM UM NOVO DIRETÓRIO
			global $folder;
			$directory = $register[$folder];

			for($i = 0; $i < count($files_upload[strtolower($folder)]['name']); $i++) {
				$filename = explode('.', $files_upload[strtolower($folder)]['name'][$i]);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				upload_file($files_upload[strtolower($folder)]['tmp_name'][$i], '../' . $filename);
			}
			$tuples[$folder] = '"' . $directory . '"';
		}

		if($hasFiles) { // INSERE ARQUIVOS NA PASTA DE UPLOADS
			global $files;

			foreach($files as $file) {
				$filename = explode('.', $files_upload[strtolower($file)]['name']);
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end($filename);
				upload_file($files_upload[strtolower($file)]['tmp_name'], '../' . $filename);

				if(!empty($files_upload[strtolower($file)]['name']))
					$tuples[$file] = '"' . $filename . '"';
			}
		}

		$fields = implode(', ', array_map(function($x, $y) { return $x . '=' . $y; }, array_keys($tuples), array_values($tuples)));
		$result = sql_update($table=$table, $fields=$fields, $condition='ID=' . $id);

		sql_insert('REGISTROS', $fields='OPERACAO, TABELA, REGISTRO, USUARIO, DATA', $values='"ALTERAÇÃO", "' . $table . '", ' . $id . ', ' . $user['ID'] . ', "' . date('Y-m-d H:i:s') . '"'); // GRAVA O LOG DA OPERAÇÃO DE ALTERAÇÃO
		return (bool) $result;
	}


	// REMOVE UM REGISTRO DO BANCO DE DADOS
	function remove($table, $id) {
		global $user;
		$register = sql_read($table=$table, $condition='ID=' . $id, $unique=true);

		if($register) {
			global $hasFiles, $hasFolder;
			if($hasFiles) { // POSSUI ALGUM ARQUIVO SALVO EM DISCO
				global $files;
				foreach($files as $file) {
					remove_file('../' . $register[$file]);
				}
			}

			elseif($hasFolder) { // POSSUI ALGUM DIRETÓRIO COM ARQUIVOS
				global $folder;
				foreach(array_slice(scandir('../' . $register[$folder]), 2) as $file)
					remove_file('../' . $register[$folder] . $file);
				remove_folder('../' . $register[$folder]);
			}

			$result = sql_remove($table=$table, $field='ID', $value=$id);

			sql_insert('REGISTROS', $fields='OPERACAO, TABELA, REGISTRO, USUARIO, DATA', $values='"REMOÇÃO", "' . $table . '", ' . $id . ', ' . $user['ID'] . ', "' . date('Y-m-d H:i:s') . '"'); // GRAVA O LOG DA OPERAÇÃO DE REMOÇÃO
			return (bool) $result;
		}

		return false;
	}


	// REMOVE UM ARQUIVO FÍSICO DO SERVIDOR
	function remove_file($file) {
		if(is_file($file) && unlink($file)) // EXISTE O ARQUIVO E CONSEGUIU REMOVÊ-LO
			return true;
		return false;
	}


	// REMOVE UM DIRETÓRIO DO SERVIDOR
	function remove_folder($folder) {
		if(is_dir($folder) && rmdir($folder)) // EXISTE O DIRETÓRIO E CONSEGUIU REMOVÊ-LO
			return true;
		return false;
	}


	// SALVA UM ARQUIVO TEMPORÁRIO ENVIADO AO SERVIDOR
	function upload_file($tmp_name, $dest_name) {
		if(move_uploaded_file($tmp_name, $dest_name))
			return true;
		return false;
	}


	// VALIDA OS ATRIBUTOS (DADOS) FORNECIDOS PELO USUÁRIO
	function validate($attributes, $action, $page, $id) {
		global $columns, $title;
		$message = array();

		if(strcmp($action, 'insert') == 0) {
			global $insert;
			$rules = $insert;
		}

		elseif(strcmp($action, 'update') == 0) {
			global $update;
			$rules = $update;
		}

		foreach($attributes as $attribute => $value) {
			$attr = strtoupper($attribute);

			if(in_array($attr, array_keys($columns))) {
				$maxlength = $rules[$attr]['attributes']['maxlength'] ?? INF;
				$minlength = $rules[$attr]['attributes']['minlength'] ?? 0;
				$min = $rules[$attr]['attributes']['min'] ?? 0;
				$required = $rules[$attr]['attributes']['required'] ?? false;

				if(isset($rules[$attr]['type'])) { // VALIDA SE OS TIPOS (INPUT) ESTÃO CORRETOS (DATE, EMAIL, NUMBER, PASSWORD, SEARCH, TEL, TEXT, TIME, URL)
					if(in_array($rules[$attr]['type'], array('password', 'search', 'tel', 'text'))) {
						if(!is_string($value))
							array_push($message, $columns[$attr]['label'] . ' não é um texto');
						elseif(((strlen($value) < $minlength) || (strlen($value) > $maxlength)) && ($required || strlen($value) != 0))
							array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
					}

					elseif(strcmp($rules[$attr]['type'], 'number') == 0) {
						if(!is_numeric($value))
							array_push($message, $columns[$attr]['label'] . ' não é um número');
						elseif($value < $min)
							array_push($message, $columns[$attr]['label'] . ' possui valor inválido');
					}

					elseif(strcmp($rules[$attr]['type'], 'email') == 0) {
						if(((strlen($value) <= $minlength) || (strlen($value) >= $maxlength)) && ($required || strlen($value) != 0))
							array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
						elseif(!filter_var($value, FILTER_VALIDATE_EMAIL))
							array_push($message, $columns[$attr]['label'] . ' não é um e-mail');
					}

					elseif(strcmp($rules[$attr]['type'], 'url') == 0) {
						if(((strlen($value) <= $minlength) || (strlen($value) >= $maxlength)) && ($required || strlen($value) != 0))
							array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
						elseif(!filter_var($value, FILTER_VALIDATE_URL))
							array_push($message, $columns[$attr]['label'] . ' não é uma URL');
					}

					elseif(strcmp($rules[$attr]['type'], 'time') == 0) {
						if(!preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/', $value))
							array_push($message, $columns[$attr]['label'] . ' não é um horário');
					}

					elseif(strcmp($rules[$attr]['type'], 'date') == 0) {
						$date = explode('-', $value);
						if(count($date) != 3 || !checkdate($date[1], $date[2], $date[0]))
							array_push($message, $columns[$attr]['label'] . ' não é uma data');
					}
				}

				elseif(strcmp($rules[$attr]['tag'], 'textarea') == 0) { // VALIDA SE O TAMANHO DO CAMPO (TEXTAREA) É VÁLIDO
					$value = trim(strip_tags(html_entity_decode($value)));
					if(!is_string($value))
						array_push($message, $columns[$attr]['label'] . ' não é um texto');
					elseif(((strlen($value) <= $minlength) || (strlen($value) >= $maxlength)) && ($required || strlen($value) != 0))
						array_push($message, $columns[$attr]['label'] . ' possui tamanho inválido');
				}
			}
		}

		if(count($message) > 0) {
			echo '<script>
				alert("Não foi possível ' . (strcmp($action, 'insert') == 0 ? 'inserir' : 'atualizar') . ' o registro na tabela de ' . mb_strtolower($title) . '!\n\n' . implode(',\n', $message) . '.");
				window.location.href = "panel.php?page=' . $page . '&action=' . $action . (strcmp($action, 'insert') == 0 ? '' : '&column=id&value=' . $id) . '";
				</script>';
			return false;
		}
		return true;
	}
?>