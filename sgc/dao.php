<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/load.php');

	// DESABILITA O ACESSO À PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
	if(count(get_included_files()) <= 1) {
		header('Location: ../index.php');
		exit;
	}


	// LIMPA CARACTERES MALICIOSOS
	function antiInjection(string $query): string {
		if($sql = mysql()) {
			$query = mysqli_real_escape_string($sql, $query); // REMOVE PALAVRAS QUE CONTENHAM SINTAXE SQL
			mysqli_close($sql);
		}

		$query = trim($query); // LIMPA ESPAÇOS VAZIOS
		$query = strip_tags($query); // REMOVE TAGS HTML E PHP
		$query = addslashes($query); // ADICIONA BARRAS INVERTIDAS
		return $query;
	}


	// CRIA UMA STRING CHAVE-VALOR
	function associative(string $key, int|string $value, bool $checkNull=false): string {
		$decorator = '"';
		if(substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
			$decorator = '';
		}
		elseif($checkNull && str_contains(mb_strtoupper($value), 'NULL')) {
			$decorator = '';
			$value = 'NULL';
		}
		return $key . '=' . $decorator . $value . $decorator;
	}


	// BOAS-VINDAS
	function greetings(): string {
		$hour = date('H');

		if($hour >= 0 && $hour < 6) {
			$return = 'Boa madrugada';
		}
		elseif($hour >= 6 && $hour < 12) {
			$return = 'Bom dia';
		}
		elseif($hour >= 12 && $hour < 18) {
			$return = 'Boa tarde';
		}
		else {
			$return = 'Boa noite';
		}

		return $return;
	}


	// GRAVA UM LOG NO BANCO
	function saveLog(string $action, int $user, string $text=null): void {
		$user > 0 && sqlInsert(table: 'LOGS', fields: 'IP, ACAO, TEXTO, USUARIO, TEMPO', values: '"' . ($_SERVER['REMOTE_ADDR'] ?? '') . '", "' . $action . '", "' . $text . '", ' . $user . ', "' . SYSDATE . '"');
	}


	// ESTABELECE CONEXÃO COM O BANCO DE DADOS MYSQL
	function mysql(int $tries=MYSQL_MAX_TRIES): ?object {
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		try {
			$connection = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_SCHEMA);
			$connection->set_charset('utf8');
		}
		catch(Exception $e) {
			$connection = null;

			if($tries >= 1) {
				return mysql(--$tries);
			}
			else {
				mail('suporte@luizamichi.com.br', 'Sinteemar - Erro de conexão SQL', 'HOST: ' . MYSQL_HOST . PHP_EOL . 'USER: ' . MYSQL_USER . PHP_EOL . 'PASSWORD: ' . MYSQL_PASSWORD . PHP_EOL . 'SCHEMA: ' . MYSQL_SCHEMA . PHP_EOL . 'ERRO: ' . $e->getMessage());
			}
		}
		finally {
			return $connection;
		}
	}


	// INSERE REGISTRO NO BANCO DE DADOS
	function sqlInsert(string $table, string $fields, string $values): bool {
		$query = 'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ');';
		return (bool) sqlQuery($query);
	}


	// RETORNA A QUANTIDADE DE REGISTROS DE UMA TABELA
	function sqlLength(string $table, string $condition=null): int {
		$query = 'SELECT COUNT(*) FROM ' . $table . ($condition ? ' WHERE 1 = 1 AND ' . $condition : '') . ';';
		$rows = 0;

		if($execution = sqlQuery($query)) {
			$rows = $execution->fetch_assoc()['COUNT(*)'] ?? 0;
		}

		return $rows;
	}


	// RETORNA O MAIOR (ÚLTIMO) ID DE UMA TABELA
	function sqlMax(string $table): int {
		$query = 'SELECT MAX(ID) FROM ' . $table . ';';
		$rows = 0;

		if($execution = sqlQuery($query)) {
			$rows = $execution->fetch_assoc()['MAX(ID)'] ?? 0;
		}

		return $rows;
	}


	// LÊ REGISTROS DE UMA TABELA
	function sqlRead(string $table, string $condition=null, bool $unique=false): array {
		$query = 'SELECT @ROW_NUMBER := @ROW_NUMBER + 1 AS ROWNUM, 1 FIX, ' . $table . '.* FROM ' . $table . ', (SELECT @ROW_NUMBER := 0) AS RN WHERE 1 = 1' . ($condition ? ' AND ' . $condition : '') . ';';
		$rows = [];

		if($execution = sqlQuery($query)) {
			if($execution->num_rows === 1) {
				$rows = $unique ? $execution->fetch_assoc() : [$execution->fetch_assoc()];
			}
			else {
				$rows = $unique ? $execution->fetch_all(MYSQLI_ASSOC)[0] ?? [] : $execution->fetch_all(MYSQLI_ASSOC);
			}
		}

		return $rows;
	}


	// REALIZA UM COMANDO NO SQL
	function sqlQuery(string $query): mixed {
		if($sql = mysql()) {
			try {
				$result = $sql->query($query);
			}
			catch(Exception $e) {
				mail('suporte@luizamichi.com.br', 'Sinteemar - Erro de consulta SQL', 'CONSULTA: ' . $query . PHP_EOL . 'ERRO: ' . $e->getMessage());
				$result = null;
			}
			finally {
				mysqli_close($sql);
			}
		}

		return $result ?? null;
	}


	// REMOVE UM REGISTRO DA TABELA
	function sqlRemove(string $table, string $field, string $value): bool {
		$query = 'DELETE FROM ' . $table . ' WHERE ' . $field . '="' . $value . '";';
		return (bool) sqlQuery($query);
	}


	// ATUALIZA UM REGISTRO DA TABELA
	function sqlUpdate(string $table, string $fields, string $condition): bool {
		$query = 'UPDATE ' . $table . ' SET ' . $fields . ' WHERE ' . $condition . ';';
		return (bool) sqlQuery($query);
	}
