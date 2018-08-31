<?php
	if(session_status() !== PHP_SESSION_ACTIVE)
		session_start();

	if(isset($_SESSION['user']))
		$user = unserialize($_SESSION['user']);
	else
		header('Location: panel.php');

	// INSERIR IMAGEM E COR DE FUNDO
	echo '<style>*{ background-attachment: fixed; background-color:#00d1b2; background-image:url("../img/sgc.svg"); background-position: center; background-repeat: no-repeat; background-size: 300px 300px; }</style>';

	// AÇÕES PERMITIDAS NO CRUD
	$actions = array('insert', 'remove', 'update');

	// NOME DAS PÁGINAS DISPONÍVEIS
	$models = array_map(function($p) { return explode('.php', $p)[0]; }, array_slice(scandir('models'), 2));

	// VALORES PASSADOS POR GET
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

	if(isset($_REQUEST) && $action && in_array($action, $actions) && $page && in_array($page, $models) && $user[strtoupper($page)]) { // INFORMOU A AÇÃO E PÁGINA CERTA
		include('models/' . $page . '.php');
		include('dao.php');
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		if(strcmp($action, 'insert') == 0 && !empty($_POST)) { // OPERAÇÃO DE INSERÇÃO DE REGISTRO
			if(insert($table=$table, $attributes=$_POST, $files_upload=empty($_FILES) ? null : $_FILES)) {
				echo '<script>
					alert("O registro foi inserido na tabela de '. strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=insert";
					</script>';
				return true;
			}
			else {
				echo '<script>
					alert("Não foi possível inserir o registro na tabela de '. strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=insert";
					</script>';
				return false;
			}
		}
		elseif(strcmp($action, 'remove') == 0 && $id && $id > 0) { // OPERAÇÃO DE REMOÇÃO DE REGISTRO
			if(remove($table, $id)) {
				echo '<script>
					alert("O registro ' . $id . ' foi removido da tabela de '. strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=list";
					</script>';
				return true;
			}
			else {
				echo '<script>
					alert("Não foi possível remover o registro ' . $id . ' da tabela de '. strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=list";
					</script>';
				return false;
			}
		}
		if(strcmp($action, 'update') == 0 && !empty($_POST) && $id && $id > 0) { // OPERAÇÃO DE ATUALIZAÇÃO DE REGISTRO
			if(update($table=$table, $attributes=$_POST, $files_upload=empty($_FILES) ? null : $_FILES, $id)) {
				echo '<script>
					alert("O registro foi atualizado na tabela de '. strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=update&column=id&value=' . $id . '";
					</script>';
				return true;
			}
			else {
				echo '<script>
					alert("Não foi possível atualizar o registro na tabela de '. strtolower($title) . '!");
					window.location.href = "panel.php?page=' . $page . '&action=update&column=id&value=' . $id . '";
					</script>';
				return false;
			}
		}
		echo '<script>window.location.href = "panel.php?page=' . $page . '&action=' . $action . '"</script>';
		return false;
	}
	else {
		echo '<script>window.location.href = "panel.php"</script>';
	}

	function insert($table, $attributes, $files_upload) {
		global $hasFiles, $hasFolder, $columns, $insert;
		$directory = 'uploads/';
		$tuples = array();
		foreach($attributes as $attribute => $value) { // ATRIBUIR REGISTROS DO VETOR PARA O MODELO
			if($columns[strtoupper($attribute)]['unique']) { // VERIFICA SE O ATRIBUTO É EXCLUSIVO (ÚNICO)
				if(sql_read($table=$table, $condition=strtoupper($attribute) . '="' . $value . '"', $unique=true))
					return false;
			}
			if($insert[strtoupper($attribute)]['attributes']['multiple']) {
				foreach($attributes[$attribute] as $attr) {
					if(strcmp($insert[strtoupper($attribute)]['type'], 'checkbox') == 0)
						$tuples[strtoupper($attr)] = '"1"';
				}
			}
			elseif(isset($columns[strtoupper($attribute)]['hash']))
				$tuples[strtoupper($attribute)] = '"'. md5($_POST[$attribute]) . '"';
			else
				$tuples[strtoupper($attribute)] = empty($_POST[$attribute]) ? 'NULL' : trim('"'. addslashes($_POST[$attribute]) . '"');
		}
		if($hasFolder) { // INSERIR ARQUIVOS EM UM NOVO DIRETÓRIO
			global $folder;
			$directory = 'uploads/' . strtolower($table) . date('YmdHis') . bin2hex(random_bytes(10)) . '/';
			mkdir('../' . $directory);
			for($i = 0; $i < count($files_upload[strtolower($folder)]['name']); $i++) {
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end(explode('.', $files_upload[strtolower($folder)]['name'][$i]));
				upload_file($files_upload[strtolower($folder)]['tmp_name'][$i], '../' . $filename);
			}
			$tuples[$folder] = '"' . $directory . '"';
		}
		if($hasFiles) { // INSERIR ARQUIVOS NA PASTA DE UPLOADS
			global $files;
			foreach($files as $file) {
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end(explode('.', $files_upload[strtolower($file)]['name']));
				upload_file($files_upload[strtolower($file)]['tmp_name'], '../' . $filename);
				if(!empty($files_upload[strtolower($file)]['name']))
					$tuples[$file] = '"' . $filename . '"';
			}
		}
		$fields = implode(', ', array_keys($tuples));
		$values = implode(', ', array_values($tuples));
		sql_insert($table=$table, $fields=$fields, $values=$values);
		return true;
	}

	function update($table, $attributes, $files_upload, $id) {
		global $hasFiles, $hasFolder, $columns, $insert;
		$directory = 'uploads/';
		$tuples = array();
		foreach($attributes as $attribute => $value) { // ATRIBUIR REGISTROS DO VETOR PARA O MODELO
			if($columns[strtoupper($attribute)]['unique']) { // VERIFICA SE O ATRIBUTO É EXCLUSIVO (ÚNICO)
				$tuple = sql_read($table=$table, $condition=strtoupper($attribute) . '="' . $value . '"', $unique=true);
				if($tuple && $tuple['ID'] != $id)
					return false;
			}
			if($insert[strtoupper($attribute)]['attributes']['multiple']) {
				foreach($insert[strtoupper($attribute)]['names'] as $i)
					$tuples[$i] = 0;
				foreach($attributes[$attribute] as $attr) {
					if(strcmp($insert[strtoupper($attribute)]['type'], 'checkbox') == 0)
						$tuples[strtoupper($attr)] = '"1"';
				}
			}
			elseif(isset($columns[strtoupper($attribute)]['hash']))
				$tuples[strtoupper($attribute)] = '"'. md5($_POST[$attribute]) . '"';
			else
				$tuples[strtoupper($attribute)] = empty($_POST[$attribute]) ? 'NULL' : trim('"' . addslashes($_POST[$attribute]) . '"');
		}
		$register = sql_read($table=$table, $condition='ID="' . $id . '"', $unique=true);
		if($hasFolder) { // INSERIR ARQUIVOS EM UM NOVO DIRETÓRIO
			global $folder;
			$directory = $register[$folder];
			for($i = 0; $i < count($files_upload[strtolower($folder)]['name']); $i++) {
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end(explode('.', $files_upload[strtolower($folder)]['name'][$i]));
				upload_file($files_upload[strtolower($folder)]['tmp_name'][$i], '../' . $filename);
			}
			$tuples[$folder] = '"' . $directory . '"';
		}
		if($hasFiles) { // INSERIR ARQUIVOS NA PASTA DE UPLOADS
			global $files;
			foreach($files as $file) {
				$filename = $directory . date('YmdHis') . bin2hex(random_bytes(10)) . '.' . end(explode('.', $files_upload[strtolower($file)]['name']));
				upload_file($files_upload[strtolower($file)]['tmp_name'], '../' . $filename);
				if(!empty($files_upload[strtolower($file)]['name']))
					$tuples[$file] = '"' . $filename . '"';
			}
		}
		$fields = implode(', ', array_map(function($x, $y) { return "$x=$y"; }, array_keys($tuples), array_values($tuples)));
		sql_update($table=$table, $fields=$fields, $condition='ID=' . $id);
		return true;
	}

	function remove($table, $id) {
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
			return sql_remove($table=$table, $field='ID', $value=$id);
		}
		return false;
	}

	function remove_file($file) {
		if(is_file($file)) { // EXISTE O ARQUIVO
			if(unlink($file))
				return true;
		}
		return false;
	}

	function remove_folder($folder) {
		if(is_dir($folder)) { // EXISTE O DIRETÓRIO
			if(rmdir($folder))
				return true;
		}
		return false;
	}

	function upload_file($tmp_name, $dest_name) {
		if(move_uploaded_file($tmp_name, $dest_name))
			return true;
		return false;
	}
?>