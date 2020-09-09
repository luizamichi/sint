<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Registro.php");
	/**
	 * Classe DAO Registro com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class RegistroDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela REGISTRO
		 */
		private $database;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->database = NULL;
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->database = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->database = clone $this->database;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "SGBD: " . ($this->database->statusConexao() ? "CONECTADO\n" : "DESCONECTADO\n");
		}

		/**
		 * Atribui um novo valor ao banco de dados
		 * @param Database $database Conexão estabelecida com o SGBD
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setDatabase(Database $database) {
			if($database->statusConexao()) {
				$this->database = $database;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Retorna o status da conexão com o SGBD
		 * @return Database Conexão com o SGBD
		 */
		public function getDatabase() {
			return $this->database;
		}

		/**
		 * Procura um registro no banco de dados pelo ID
		 * @param int $id ID do registro a ser buscado
		 * @return false|Registro Objeto registro (ID, descrição, IP e data)
		 */
		public function procurarId(int $id) {
			$query = "SELECT DESCRICAO, IP, DATA FROM REGISTRO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $descricao, $ip, $data);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$registro = new Registro();
			$registro->setId($id);
			$registro->setDescricao($descricao);
			$registro->setIp($ip);
			$registro->setData($data);
			mysqli_stmt_close($stmt);
			return $registro;
		}

		/**
		 * Procura um registro no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Registros encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT * FROM REGISTRO WHERE DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $descricao, $ip, $data);
			$registros = array();
			while(mysqli_stmt_fetch($stmt)) {
				$registro = new Registro();
				$registro->setId($id);
				$registro->setDescricao($descricao);
				$registro->setIp($ip);
				$registro->setData($data);
				array_push($registros, $registro);
			}
			mysqli_stmt_close($stmt);
			return $registros;
		}

		/**
		 * Procura um registro no banco de dados pela descrição
		 * @param string $descricao Descrição ou parte da descrição a ser buscada
		 * @return array Registros encontrados
		 */
		public function procurarDescricao(string $descricao) {
			$query = "SELECT * FROM REGISTRO WHERE DESCRICAO LIKE CONCAT('%',?,'%') ORDER BY ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $descricao);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $descricao, $ip, $data);
			$registros = array();
			while(mysqli_stmt_fetch($stmt)) {
				$registro = new Registro();
				$registro->setId($id);
				$registro->setDescricao($descricao);
				$registro->setIp($ip);
				$registro->setData($data);
				array_push($registros, $registro);
			}
			mysqli_stmt_close($stmt);
			return $registros;
		}

		/**
		 * Procura um registro no banco de dados pelo IP
		 * @param string $ip IP ou parte do IP a ser buscado
		 * @return array Registros encontrados
		 */
		public function procurarIp(string $ip) {
			$query = "SELECT * FROM REGISTRO WHERE IP LIKE CONCAT('%',?,'%') ORDER BY ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $ip);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $descricao, $ip, $data);
			$registros = array();
			while(mysqli_stmt_fetch($stmt)) {
				$registro = new Registro();
				$registro->setId($id);
				$registro->setDescricao($descricao);
				$registro->setIp($ip);
				$registro->setData($data);
				array_push($registros, $registro);
			}
			mysqli_stmt_close($stmt);
			return $registros;
		}

		/**
		 * Procura o último registro cadastrado no banco de dados
		 * @return false|Registro Objeto registro (ID, descrição, IP e data)
		 */
		public function procurarUltimo() {
			$query = "SELECT * FROM REGISTRO ORDER BY ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $descricao, $ip, $data);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$registro = new Registro();
			$registro->setId($id);
			$registro->setDescricao($descricao);
			$registro->setIp($ip);
			$registro->setData($data);
			mysqli_stmt_close($stmt);
			return $registro;
		}

		/**
		 * Insere um novo registro no banco de dados
		 * @param Registro $registro Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Registro $registro) {
			$query = "INSERT INTO REGISTRO (DESCRICAO, IP, DATA) VALUES (?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$descricao = $registro->getDescricao();
			$ip = $registro->getIp();
			$data = $registro->getData()->format("Y-m-d H:i:s");
			mysqli_stmt_bind_param($stmt, "sss", $descricao, $ip, $data);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um registro no banco de dados
		 * @param Registro $registro Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Registro $registro) {
			$query = "UPDATE REGISTRO SET DESCRICAO=?, IP=?, DATA=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $registro->getId();
			$descricao = $registro->getDescricao();
			$ip = $registro->getIp();
			$data = $registro->getData()->format("Y-m-d H:i:s");
			mysqli_stmt_bind_param($stmt, "sssi", $descricao, $ip, $data, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um registro do banco de dados
		 * @param Registro $registro Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Registro $registro) {
			$query = "DELETE FROM REGISTRO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $registro->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os registros do banco de dados ordenados decrescentemente pelo ID
		 * @return array Registros encontrados
		 */
		public function listar() {
			$query = "SELECT * FROM REGISTRO ORDER BY ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $descricao, $ip, $data);
			$registros = array();
			while(mysqli_stmt_fetch($stmt)) {
				$registro = new Registro();
				$registro->setId($id);
				$registro->setDescricao($descricao);
				$registro->setIp($ip);
				$registro->setData($data);
				array_push($registros, $registro);
			}
			mysqli_stmt_close($stmt);
			return $registros;
		}

		/**
		 * Lista os N registros do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de registros a ser listado
		 * @return array|false Registros encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT * FROM REGISTRO ORDER BY ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $descricao, $ip, $data);
			$registros = array();
			while(mysqli_stmt_fetch($stmt)) {
				$registro = new Registro();
				$registro->setId($id);
				$registro->setDescricao($descricao);
				$registro->setIp($ip);
				$registro->setData($data);
				array_push($registros, $registro);
			}
			mysqli_stmt_close($stmt);
			return $registros;
		}

		/**
		 * Lista os registros do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro registro
		 * @param int $quantidade Limite do último registro
		 * @return array|false Registros encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT * FROM REGISTRO ORDER BY ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $descricao, $ip, $data);
			$registros = array();
			while(mysqli_stmt_fetch($stmt)) {
				$registro = new Registro();
				$registro->setId($id);
				$registro->setDescricao($descricao);
				$registro->setIp($ip);
				$registro->setData($data);
				array_push($registros, $registro);
			}
			mysqli_stmt_close($stmt);
			return $registros;
		}

		/**
		 * Retorna o número de registros cadastrados no banco de dados
		 * @return int Registros cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM REGISTRO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>