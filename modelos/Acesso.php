<?php
	/**
	 * Classe Acesso com atributos simples (ID, IP e data)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Acesso {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $ip Endereço IP do visitante
		 * @var DateTime $data Data do acesso na ordem ano-mês-dia hora:minuto:segundo
		 */
		private $id;
		private $ip;
		private $data;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->id = 0;
			$this->ip = "";
			$this->data = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->id = NULL;
			$this->ip = NULL;
			$this->data = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->id = clone $this->id;
			$this->ip = clone $this->ip;
			$this->data = clone $this->data;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "ID: " . $this->id .
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
		 * Atribui um novo valor ao IP
		 * @param string $ip Endereço IP da máquina do visitante
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setIp(string $ip) {
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
		 * Retorna o ID do acesso
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o IP do acesso
		 * @return string Endereço IP do visitante
		 */
		public function getIp() {
			return $this->ip;
		}

		/**
		 * Retorna a data e hora do acesso
		 * @return DateTime Objeto Data-Hora
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Valida o acesso certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->ip && $this->data) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>