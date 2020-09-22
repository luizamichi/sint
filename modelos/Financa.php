<?php
	include_once("Usuario.php");
	/**
	 * Classe Finança com atributos simples (ID, período, arquivo PDF, usuário, data e status)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Financa {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $periodo Período (semanal, mensal, bimestral, trimestral, semestral ou anual)
		 * @var string $flag Valor utilizado para ordenação por período
		 * @var string $arquivo Endereço do arquivo em formato PDF
		 * @var Usuario $usuario Usuário (ID, nome, e-mail, login, senha, permissão e status)
		 * @var DateTime $data Data do registro na ordem ano-mês-dia hora:minuto:segundo
		 * @var boolean $status Inativo (FALSE) ou ativo (TRUE)
		 */
		private $id;
		private $periodo;
		private $flag;
		private $arquivo;
		private $usuario;
		private $data;
		private $status;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->id = 0;
			$this->periodo = "";
			$this->flag = date("Ym");
			$this->arquivo = "";
			$this->usuario = new Usuario();
			$this->data = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
			$this->status = FALSE;
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->id = NULL;
			$this->periodo = NULL;
			$this->flag = NULL;
			$this->arquivo = NULL;
			$this->usuario = NULL;
			$this->data = NULL;
			$this->status = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->id = clone $this->id;
			$this->periodo = clone $this->periodo;
			$this->flag = clone $this->flag;
			$this->arquivo = clone $this->arquivo;
			$this->usuario = clone $this->usuario;
			$this->data = clone $this->data;
			$this->status = clone $this->status;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "ID: " . $this->id .
			"\nPERÍODO: " . $this->periodo .
			"\nFLAG: " . $this->flag .
			"\nARQUIVO: " . $this->arquivo .
			"\nUSUÁRIO: " . $this->usuario->getNome() .
			"\nDATA: " . $this->data->format("d/m/Y - H:i:s") .
			"\nSTATUS: " . ($this->status ? "ATIVO" : "INATIVO") . "\n";
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
		 * Atribui um novo valor ao período da finança
		 * @param string $periodo Semanal, mensal, (bi, tri ou se)mestral ou anual com no mínimo 6 e no máximo 32 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setPeriodo(string $periodo) {
			$periodo = trim($periodo);
			if(strlen($periodo) >= 6 && strlen($periodo) <= 32) {
				$flag = "";
				switch(mb_substr($periodo, 0, 3)) {
					case "Jan":
						$flag = "01";
						break;
					case "Fev":
						$flag = "02";
						break;
					case "Mar":
						$flag = "03";
						break;
					case "Abr":
						$flag = "04";
						break;
					case "Mai":
						$flag = "05";
						break;
					case "Jun":
						$flag = "06";
						break;
					case "Jul":
						$flag = "07";
						break;
					case "Ago":
						$flag = "08";
						break;
					case "Set":
						$flag = "09";
						break;
					case "Out":
						$flag = "10";
						break;
					case "Nov":
						$flag = "11";
						break;
					case "Dez":
						$flag = "12";
						break;
					case "1º ":
						$flag = "03.1";
						break;
					case "2º ":
						$flag = "06.1";
						break;
					case "3º ":
						$flag = "09.1";
						break;
					case "4º ":
						$flag = "12.1";
						break;
					default:
						$periodo = "";
				}
				$this->flag = substr($periodo, -4) . $flag;
				$this->periodo = $periodo;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao arquivo
		 * @param string $arquivo Endereço do arquivo PDF de tamanho <= 3MB com no mínimo 5 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setArquivo(string $arquivo) {
			if(strcmp(strtolower(substr($arquivo, -3)), "pdf") == 0 && strlen($arquivo) >= 5) {
				$this->arquivo = $arquivo;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo hash ao arquivo
		 * @param string $arquivo Endereço do arquivo PDF de tamanho <= 3MB com no mínimo 5 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function hashArquivo(string $arquivo) {
			if(strcmp(strtolower(substr($arquivo, -3)), "pdf") == 0 && strlen($arquivo) >= 5) {
				if(strlen($arquivo) > 33) {
					$this->arquivo = mb_strtolower("uploads/financas/" . date("YmdHis") . substr($arquivo, -33));
				}
				else {
					$this->arquivo = mb_strtolower("uploads/financas/" . date("YmdHis") . $arquivo);
				}
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo usuário
		 * @param Usuario $usuario Usuário responsável pela finança
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setUsuario(Usuario $usuario) {
			if($usuario->valida()) {
				$this->usuario = $usuario;
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
		 * Atribui um novo valor ao status da finança
		 * @param boolean $status Booleano referente ao estado
		 * @return void
		 */
		public function setStatus(bool $status) {
			$this->status = $status;
		}

		/**
		 * Retorna o ID da finança
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o período da finança
		 * @return string Descrição do período
		 */
		public function getPeriodo() {
			return $this->periodo;
		}

		/**
		 * Retorna a flag da finança
		 * @return string Valor referente a posição ordenada
		 */
		public function getFlag() {
			return $this->flag;
		}

		/**
		 * Retorna o endereço onde está armazenado o arquivo PDF a partir da raiz
		 * @return string Endereço no formato "pasta/arquivo.pdf"
		 */
		public function getArquivo() {
			return $this->arquivo;
		}

		/**
		 * Retorna o usuário que criou a finança
		 * @return Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function getUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna a data e hora da criação ou edição da finança
		 * @return DateTime Data no formato "YYYY-mm-dd H:m:s"
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Retorna o status de atividade da finança
		 * @return boolean Inativo ou ativo
		 */
		public function isStatus() {
			return $this->status;
		}

		/**
		 * Valida a finança certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->periodo && $this->flag && $this->arquivo && $this->usuario->valida() && $this->data && $this->status) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>