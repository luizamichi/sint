<?php
	if(count(get_included_files()) <= 1) { // DESABILITA O ACESSO A PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
		header('Location: panel.php');
		return false;
	}


	// LIMPA CARACTERES MALICIOSOS
	function anti_injection($query) {
		$query = preg_replace('/(from|select|insert|delete|where|drop table|show tables|--|\\\\)/i', '', $sql); // REMOVE PALAVRAS QUE CONTENHAM SINTAXE SQL
		$query = trim($query); // LIMPA ESPAÇOS VAZIOS
		$query = strip_tags($query); // REMOVE TAGS HTML E PHP
		$query = addslashes($query); // ADICIONA BARRAS INVERTIDAS
		return $query;
	}


	// CRIA UMA STRING CHAVE-VALOR
	function associative($key, $value) {
		return $key . '=' . '"' . $value . '"';
	}


	// ESTABELE CONEXÃO COM O BANCO DE DADOS MYSQL
	function mysql() {
		$host = 'sinteemar.com.br';
		$user = 'sint';
		$password = 'Senha do banco de dados';
		$name = 'sint_2019';
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		try {
			$connection = new mysqli($host, $user, $password, $name);
			$connection->set_charset('utf8');
		}
		catch(Exception $e) {
			$connection = null;
		}
		finally {
			return $connection;
		}
	}


	// INSERE REGISTRO NO BANCO DE DADOS
	function sql_insert($table, $fields, $values) {
		$query = 'INSERT INTO ' . $table . '(' . $fields . ') VALUES(' . $values . ');';
		$result = false;

		if($sql = mysql()) {
			try {
				$sql->query($query);
			}
			catch(Exception $e) {
				return false;
			}
			finally {
				$result = $sql->affected_rows > 0;
				mysqli_close($sql);
			}
		}

		return $result;
	}


	// RETORNA A QUANTIDADE DE REGISTROS DE UMA TABELA
	function sql_length($table, $condition=null) {
		$query = 'SELECT COUNT(*) FROM ' . $table . ($condition ? ' WHERE ' . $condition : '') . ';';
		$rows = 0;

		if($sql = mysql()) {
			try {
				$result = $sql->query($query);
				$rows = $result->fetch_assoc()['COUNT(*)'];
			}
			finally {
				mysqli_close($sql);
			}
		}

		return $rows;
	}


	// RETORNA O MAIOR (ÚLTIMO) ID DE UMA TABELA
	function sql_max($table, $condition=null) {
		$query = 'SELECT MAX(ID) FROM ' . $table . ($condition ? ' WHERE ' . $condition : '') . ';';
		$rows = 0;

		if($sql = mysql()) {
			try {
				$result = $sql->query($query);
				$rows = $result->fetch_assoc()['MAX(ID)'];
			}
			finally {
				mysqli_close($sql);
			}
		}

		return $rows;
	}


	// LÊ REGISTROS DE UMA TABELA
	function sql_read($table, $condition=null, $unique=false) {
		$query = 'SELECT @ROW_NUMBER:=@ROW_NUMBER+1 AS ROWNUM, 1 FIX, ' . $table . '.* FROM ' . $table . ', (SELECT @ROW_NUMBER:=0) AS RN ' . ($condition ? ' WHERE ' . $condition : '') . ';';
		$rows = null;

		if($sql = mysql()) {
			try {
				$result = $sql->query($query);
				if($result->num_rows == 0)
					$rows = array();
				elseif($result->num_rows == 1)
					$rows = $unique ? $result->fetch_assoc() : array($result->fetch_assoc());
				else
					$rows = $unique ? $result->fetch_all(MYSQLI_ASSOC)[0] : $result->fetch_all(MYSQLI_ASSOC);
			}
			finally {
				mysqli_close($sql);
			}
		}

		return $rows;
	}


	// REMOVE UM REGISTRO DA TABELA
	function sql_remove($table, $field, $value) {
		$query = 'DELETE FROM ' . $table . ' WHERE ' . $field . '="' . $value . '";';
		$result = false;

		if($sql = mysql()) {
			try {
				$sql->query($query);
			}
			finally {
				$result = true;
				$result = $sql->affected_rows > 0;
				mysqli_close($sql);
			}
		}

		return $result;
	}


	// LISTA TODAS AS TABELAS DEFINIDAS NOS MODELOS
	function sql_tables() {
		return array_map(function($p) { include_once('models/' . $p); return $table; }, array_slice(scandir('models'), 2));
	}


	// ATUALIZA UM REGISTRO DA TABELA
	function sql_update($table, $fields, $condition) {
		$query = 'UPDATE ' . $table . ' SET ' . $fields . ' WHERE ' . $condition . ';';
		$result = false;

		if($sql = mysql()) {
			try {
				$sql->query($query);
			}
			catch(Exception $e) {
				return false;
			}
			finally {
				$result = $sql->num_rows > 0;
				mysqli_close($sql);
			}
		}

		return $result;
	}
?>