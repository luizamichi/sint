<?php
	include_once("../modelos/Acesso.php");
	include_once("../modelos/Database.php");
	/**
	 * Classe DAO Acesso com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class AcessoDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela ACESSO
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
		 * Procura um acesso no banco de dados pelo ID
		 * @param int $id ID do acesso a ser buscado
		 * @return Acesso|false Objeto acesso (ID, IP e data)
		 */
		public function procurarId(int $id) {
			$query = "SELECT IP, DATA FROM ACESSO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $ip, $data);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$acesso = new Acesso();
			$acesso->setId($id);
			$acesso->setIp($ip);
			$acesso->setData($data);
			mysqli_stmt_close($stmt);
			return $acesso;
		}

		/**
		 * Procura um acesso no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Acessos encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT * FROM ACESSO WHERE DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $ip, $data);
			$acessos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$acesso = new Acesso();
				$acesso->setId($id);
				$acesso->setIp($ip);
				$acesso->setData($data);
				array_push($acessos, $acesso);
			}
			mysqli_stmt_close($stmt);
			return $acessos;
		}

		/**
		 * Procura um acesso no banco de dados pelo IP
		 * @param string $ip IP ou parte do IP a ser buscado
		 * @return array Acessos encontrados
		 */
		public function procurarIp(string $ip) {
			$query = "SELECT * FROM ACESSO WHERE IP LIKE CONCAT('%',?,'%') ORDER BY ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $ip);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $ip, $data);
			$acessos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$acesso = new Acesso();
				$acesso->setId($id);
				$acesso->setIp($ip);
				$acesso->setData($data);
				array_push($acessos, $acesso);
			}
			mysqli_stmt_close($stmt);
			return $acessos;
		}

		/**
		 * Procura o último acesso cadastrado no banco de dados
		 * @return Acesso|false Objeto acesso (ID, IP e data)
		 */
		public function procurarUltimo() {
			$query = "SELECT * FROM ACESSO ORDER BY ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $ip, $data);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$acesso = new Acesso();
			$acesso->setId($id);
			$acesso->setIp($ip);
			$acesso->setData($data);
			mysqli_stmt_close($stmt);
			return $acesso;
		}

		/**
		 * Insere um novo acesso no banco de dados
		 * @param Acesso $acesso Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Acesso $acesso) {
			$query = "INSERT INTO ACESSO (IP, DATA) VALUES (?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$ip = $acesso->getIp();
			$data = $acesso->getData()->format("Y-m-d H:i:s");
			mysqli_stmt_bind_param($stmt, "ss", $ip, $data);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um acesso no banco de dados
		 * @param Acesso $acesso Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Acesso $acesso) {
			$query = "UPDATE ACESSO SET IP=?, DATA=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$ip = $acesso->getIp();
			$data = $acesso->getData()->format("Y-m-d H:i:s");
			$id = $acesso->getId();
			mysqli_stmt_bind_param($stmt, "ssi", $ip, $data, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um acesso do banco de dados
		 * @param Acesso $acesso Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Acesso $acesso) {
			$query = "DELETE FROM ACESSO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $acesso->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os acessos do banco de dados ordenados decrescentemente pelo ID
		 * @return array Acessos encontrados
		 */
		public function listar() {
			$query = "SELECT * FROM ACESSO ORDER BY ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $ip, $data);
			$acessos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$acesso = new Acesso();
				$acesso->setId($id);
				$acesso->setIp($ip);
				$acesso->setData($data);
				array_push($acessos, $acesso);
			}
			mysqli_stmt_close($stmt);
			return $acessos;
		}

		/**
		 * Lista os N acessos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de acessos a ser listado
		 * @return array|false Acessos encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT * FROM ACESSO ORDER BY ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $ip, $data);
			$acessos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$acesso = new Acesso();
				$acesso->setId($id);
				$acesso->setIp($ip);
				$acesso->setData($data);
				array_push($acessos, $acesso);
			}
			mysqli_stmt_close($stmt);
			return $acessos;
		}

		/**
		 * Lista os acessos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro acesso
		 * @param int $quantidade Limite do último acesso
		 * @return array|false Acessos encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio <= 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT * FROM ACESSO ORDER BY ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $ip, $data);
			$acessos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$acesso = new Acesso();
				$acesso->setId($id);
				$acesso->setIp($ip);
				$acesso->setData($data);
				array_push($acessos, $acesso);
			}
			mysqli_stmt_close($stmt);
			return $acessos;
		}

		/**
		 * Retorna o número de acessos cadastrados no banco de dados
		 * @return int Acessos cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM ACESSO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de acessos diários, mensais ou anuais cadastrados no banco de dados
		 * @param string $data Data no formato "YYYY-mm-dd", "YYYY-mm" ou "YYYY"
		 * @return int Acessos cadastrados
		 */
		public function tamanhoData(string $data) {
			$query = "SELECT COUNT(*) FROM ACESSO WHERE DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de acessos realizados de um IP específico
		 * @param string $ip IP a ser buscado
		 * @return int Acessos cadastrados
		 */
		public function tamanhoIp(string $ip) {
			$query = "SELECT COUNT(*) FROM ACESSO WHERE IP=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $ip);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>