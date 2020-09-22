<?php
	/**
	 * Classe Registro (LOG) com atributos simples (ID, descrição, IP e data)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Registro {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $descricao Descrição da atividade realizada
		 * @var string $ip Endereço IP do usuário
		 * @var DateTime $data Data do registro na ordem ano-mês-dia hora:minuto:segundo
		 */
		private $id;
		private $descricao;
		private $ip;
		private $data;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->id = 0;
			$this->descricao = "";
			$this->ip = "";
			$this->data = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->id = NULL;
			$this->descricao = NULL;
			$this->ip = NULL;
			$this->data = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->id = clone $this->id;
			$this->descricao = clone $this->descricao;
			$this->ip = clone $this->ip;
			$this->data = clone $this->data;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "ID: " . $this->id .
			"\nDESCRIÇÃO: " . $this->descricao .
			"\nIP: " . $this->ip .
			"\nDATA: " . $this->data->format("d/m/Y - H:i:s") . "\n";
		}

		/**
		 * Atribui um novo valor ao ID
		 * @param int $id Número inteiro positivo
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setId(int $id) {
			if($id > 0) {
				$this->id = $id;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor à descrição do registro
		 * @param string $descricao Descrição da atividade com no mínimo 6 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setDescricao(string $descricao) {
			$descricao = trim($descricao);
			if(strlen($descricao) >= 6) {
				$this->descricao = mb_strtoupper($descricao);
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao IP
		 * @param string $ip Endereço IP da máquina do usuário
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setIp(string $ip) {
			$ip = trim($ip);
			if(filter_var($ip, FILTER_VALIDATE_IP) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
				$this->ip = $ip;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor à data
		 * @param string $data Data no formato "YYYY-mm-dd H:m:s"
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setData(string $data) {
			$datetime = DateTime::createFromFormat("Y-m-d H:i:s", $data);
			if($data && $datetime->format("Y-m-d H:i:s") === $data) {
				$this->data = $datetime;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Retorna o ID do registro
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna a descrição do registro
		 * @return string Descrição da atividade realizada
		 */
		public function getDescricao() {
			return $this->descricao;
		}

		/**
		 * Retorna o IP do registro
		 * @return string Endereço IP do usuário
		 */
		public function getIp() {
			return $this->ip;
		}

		/**
		 * Retorna a data e hora da criação ou edição do registro
		 * @return DateTime Objeto Data-Hora
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Valida o registro certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->descricao && $this->ip && $this->data) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>