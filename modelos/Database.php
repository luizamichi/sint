<?php
	/**
	 * Classe Database com atributos simples (Hostname, porta, usuário, senha, base de dados, conjunto de caracteres e conexão)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Database {
		/**
		 * Variáveis privadas
		 * @var string $host Endereço IP ou domínio do servidor hospedeiro
		 * @var int $porta Porta do serviço MySQL ou MariaDB
		 * @var string $usuario Nome do usuário do SGBD
		 * @var string $senha Senha do usuário do SGBD
		 * @var string $database Nome da base de dados
		 * @var string $charset Codificação de caracteres
		 * @var MySQLi $conexao Conexão entre o SGBD e o PHP
		 */
		private $host;
		private $porta;
		private $usuario;
		private $senha;
		private $database;
		private $charset;
		private $conexao;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->host = "";
			$this->porta = 3306;
			$this->usuario = "";
			$this->senha = "";
			$this->database = "";
			$this->charset = "utf8";
			$this->conexao = NULL;
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->host = NULL;
			$this->porta = NULL;
			$this->usuario = NULL;
			$this->senha = NULL;
			$this->database = NULL;
			$this->charset = NULL;
			$this->conexao = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->host = clone $this->host;
			$this->porta = clone $this->porta;
			$this->usuario = clone $this->usuario;
			$this->senha = clone $this->senha;
			$this->database = clone $this->database;
			$this->charset = clone $this->charset;
			$this->conexao = clone $this->conexao;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "HOST: " . $this->host .
			"\nPORTA: " . $this->porta .
			"\nUSUÁRIO: " . $this->usuario .
			"\nSENHA: " . $this->senha .
			"\nBASE DE DADOS: " . $this->database .
			"\nCHARSET: " . $this->charset .
			"\nCONEXÃO: " . ($this->statusConexao() ? "ATIVA" : "INATIVA") . "\n";
		}

		/**
		 * Atribui um novo valor ao host
		 * @param string $host Endereço IP ou domínio do host
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setHost(string $host) {
			if(filter_var($host, FILTER_VALIDATE_IP) || filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) || filter_var($host, FILTER_VALIDATE_URL)) {
				$this->host = $host;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor à porta de conexão
		 * @param int $porta Número inteiro positivo
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setPorta(int $porta) {
			if($porta >= 0) {
				$this->porta = $porta;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao usuário do SGBD
		 * @param string $usuario Nome do usuário para login no SGBD
		 * @return void
		 */
		public function setUsuario(string $usuario) {
			$this->usuario = $usuario;
		}

		/**
		 * Atribui um novo valor à senha do usuário no SGBD
		 * @param string $senha Senha do usuário para login no SGBD
		 * @return void
		 */
		public function setSenha(string $senha) {
			$this->senha = $senha;
		}

		/**
		 * Atribui um novo valor à base de dados
		 * @param string $database Nome da base de dados
		 * @return void
		 */
		public function setDatabase(string $database) {
			$this->database = $database;
		}

		/**
		 * Atribui um novo valor ao charset
		 * @param string $charset Codificação de caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setCharset(string $charset) {
			if(in_array($charset, mb_list_encodings())) {
				$this->charset = $charset;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Inicia uma conexão com o banco de dados
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function startConexao() {
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			try {
				$this->conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->database, $this->porta);
				$this->conexao->set_charset($this->charset);
				return TRUE;
			}
			catch(\mysqli_sql_exception $e) {
				return FALSE;
			}
		}

		/**
		 * Fecha a conexão com a base de dados
		 * @return boolean Permissão ou negação da atividade
		 */
		public function closeConexao() {
			if($this->conexao) {
				$this->conexao->close();
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Retorna o nome do host
		 * @return string Endereço do SGBD
		 */
		public function getHost() {
			return $this->host;
		}

		/**
		 * Retorna a porta do banco de dados
		 * @return int Número da porta de conexão do SGBD
		 */
		public function getPorta() {
			return $this->porta;
		}

		/**
		 * Retorna o nome do usuário
		 * @return string Nome do usuário do SGBD
		 */
		public function getUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna a senha do usuário do SGBD
		 * @return string Senha do SGBD vinculada ao usuário
		 */
		public function getSenha() {
			return $this->senha;
		}

		/**
		 * Retorna o nome da base de dados no SGBD
		 * @return string Nome da base de dados
		 */
		public function getDatabase() {
			return $this->database;
		}

		/**
		 * Retorna o nome da codificação de caracteres
		 * @return string Formato de codificação de caracteres
		 */
		public function getCharset() {
			return $this->charset;
		}

		/**
		 * Retorna a conexão com a base de dados
		 * @return MySQLi Conexão com o MySQL
		 */
		public function getConexao() {
			return $this->conexao;
		}

		/**
		 * Retorna o status da conexão com o SGBD
		 * @return boolean Permissão ou negação da atividade
		 */
		public function statusConexao() {
			if($this->conexao) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Valida a base de dados certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->host && $this->porta && $this->usuario && $this->senha && $this->database && $this->conexao) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>