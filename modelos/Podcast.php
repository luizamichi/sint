<?php
	include_once("Usuario.php");
	/**
	 * Classe Podcast com atributos simples (ID, título, arquivo MP3, usuário, data e status)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Podcast {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $titulo Descrição do podcast
		 * @var string $audio Endereço do arquivo em formato MP3
		 * @var Usuario $usuario Usuário (ID, nome, e-mail, login, senha, permissão e status)
		 * @var DateTime $data Data do registro na ordem ano-mês-dia hora:minuto:segundo
		 * @var boolean $status Inativo (FALSE) ou ativo (TRUE)
		 */
		private $id;
		private $titulo;
		private $audio;
		private $usuario;
		private $data;
		private $status;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->id = 0;
			$this->titulo = "";
			$this->audio = "";
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
			$this->titulo = NULL;
			$this->audio = NULL;
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
			$this->titulo = clone $this->titulo;
			$this->audio = clone $this->audio;
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
			"\nTÍTULO: " . $this->titulo .
			"\nARQUIVO: " . $this->audio .
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
		 * Atribui um novo valor ao título do podcast
		 * @param string $titulo Descrição sobre o podcast com no mínimo 6 e no máximo 128 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setTitulo(string $titulo) {
			$titulo = trim($titulo);
			if(strlen($titulo) >= 6 && strlen($titulo) <= 128) {
				$this->titulo = $titulo;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao áudio
		 * @param string $audio Endereço do arquivo MP3 de tamanho <= 100MB com no mínimo 5 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setAudio(string $audio) {
			if(strcmp(strtolower(substr($audio, -3)), "mp3") == 0 && strlen($audio) >= 5) {
				$this->audio = $audio;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo hash ao áudio
		 * @param string $audio Endereço do arquivo MP3 de tamanho <= 100MB com no mínimo 5 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function hashAudio(string $audio) {
			if(strcmp(strtolower(substr($audio, -3)), "mp3") == 0 && strlen($audio) >= 5) {
				if(strlen($audio) > 31) {
					$this->audio = mb_strtolower("uploads/podcasts/" . date("YmdHis") . substr($audio, -31));
				}
				else {
					$this->audio = mb_strtolower("uploads/podcasts/" . date("YmdHis") . $audio);
				}
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo usuário
		 * @param Usuario $usuario Usuário responsável pelo podcast
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
		 * Atribui um novo valor ao status do podcast
		 * @param boolean $status Booleano referente ao estado
		 * @return void
		 */
		public function setStatus(bool $status) {
			$this->status = $status;
		}

		/**
		 * Retorna o ID do podcast
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o título do podcast
		 * @return string Descrição do podcast
		 */
		public function getTitulo() {
			return $this->titulo;
		}

		/**
		 * Retorna o endereço onde está armazenado o arquivo MP3 a partir da raiz
		 * @return string Endereço no formato "pasta/arquivo.mp3"
		 */
		public function getAudio() {
			return $this->audio;
		}

		/**
		 * Retorna o usuário que criou o podcast
		 * @return Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function getUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna a data e hora da criação ou edição do podcast
		 * @return DateTime Objeto Data-Hora
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Retorna o status de atividade do podcast
		 * @return boolean Inativo ou ativo
		 */
		public function isStatus() {
			return $this->status;
		}

		/**
		 * Valida o podcast certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->titulo && $this->audio && $this->usuario->valida() && $this->data && $this->status) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>