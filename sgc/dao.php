<?php
	function mysql() {
		$host = 'sinteemar.com.br';
		$user = 'sint';
		$password = 'Senha do banco de dados';
		$name = 'sint_2018';
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

	function anti_injection($query) {
		$query = preg_replace('/(from|select|insert|delete|where|drop table|show tables|--|\\\\)/i', '', $query); // REMOVE PALAVRAS QUE CONTENHAM SINTAXE SQL
		$query = trim($query); // LIMPA ESPAÇOS VAZIOS
		$query = strip_tags($query); // REMOVE TAGS HTML E PHP
		$query = addslashes($query); // ADICIONA BARRAS INVERTIDAS A UMA STRING
		return $query;
	}

	function sql_insert($table, $fields, $values) {
		$query = 'INSERT INTO ' . $table . '(' . $fields . ') VALUES(' . $values . ')';
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

	function sql_length($table, $condition=null) {
		$query = 'SELECT COUNT(*) FROM ' . $table . ($condition ? ' WHERE ' . $condition : '');
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

	function sql_read($table, $condition=null, $unique=false) {
		$query = 'SELECT * FROM ' . $table . ($condition ? ' WHERE ' . $condition : '');
		$rows = null;
		if($sql = mysql()) {
			try {
				$result = $sql->query($query);
				if($result->num_rows == 1)
					$rows = $unique ? $result->fetch_assoc() : [$result->fetch_assoc()];
				else
					$rows = $unique ? ($result->num_rows >= 1 ? $result->fetch_all(MYSQLI_ASSOC)[0] : []) : $result->fetch_all(MYSQLI_ASSOC);
			}
			finally {
				mysqli_close($sql);
			}
		}
		return $rows;
	}

	function sql_remove($table, $field, $value) {
		$query = 'DELETE FROM ' . $table . ' WHERE ' . $field . '="' . $value . '"';
		$result = false;
		if($sql = mysql()) {
			try {
				$sql->query($query);
			}
			finally {
				$result = true;
				mysqli_close($sql);
			}
		}
		return $result;
	}

	function sql_tables() {
		return array_map(function($p) { include_once('models/' . $p); return $table; }, array_slice(scandir('models'), 2));
	}

	function sql_update($table, $fields, $condition) {
		$query = 'UPDATE ' . $table . ' SET ' . $fields . ' WHERE ' . $condition;
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