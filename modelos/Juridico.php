<?php
	include_once("Usuario.php");
	/**
	 * Classe Jurídico com atributos simples (ID, título, descrição, arquivo PDF, usuário, data e status)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Juridico {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $titulo Título do documento jurídico
		 * @var string $descricao Descrição do essencial do documento
		 * @var string $arquivo Endereço do arquivo em formato PDF
		 * @var Usuario $usuario Usuário (ID, nome, e-mail, login, senha, permissão e status)
		 * @var DateTime $data Data do registro na ordem ano-mês-dia hora:minuto:segundo
		 * @var boolean $status Inativo (FALSE) ou ativo (TRUE)
		 */
		private $id;
		private $titulo;
		private $descricao;
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
			$this->titulo = "";
			$this->descricao = NULL;
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
			$this->titulo = NULL;
			$this->descricao = NULL;
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
			$this->titulo = clone $this->titulo;
			$this->descricao = clone $this->descricao;
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
			"\nTÍTULO: " . $this->titulo .
			"\nDESCRIÇÃO: " . $this->descricao .
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
		 * Atribui um novo valor ao título do documento jurídico
		 * @param string $titulo Título do documento detalhado com no mínimo 6 e no máximo 128 caracteres
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
		 * Atribui um novo valor à descrição do documento jurídico
		 * @param string $descricao Descrição resumindo o documento com no mínimo 6 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setDescricao(string $descricao) {
			if(strlen($descricao) >= 6) {
				$this->descricao = $descricao;
				return TRUE;
			}
			else if(empty($descricao)) {
				$this->descricao = NULL;
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
				if(strlen($arquivo) > 31) {
					$this->arquivo = mb_strtolower("uploads/juridicos/" . date("YmdHis") . substr($arquivo, -32));
				}
				else {
					$this->arquivo = mb_strtolower("uploads/juridicos/" . date("YmdHis") . $arquivo);
				}
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo usuário
		 * @param Usuario $usuario Usuário responsável pelo documento jurídico
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
		 * Atribui um novo valor ao status do documento jurídico
		 * @param boolean $status Booleano referente ao estado
		 * @return void
		 */
		public function setStatus(bool $status) {
			$this->status = $status;
		}

		/**
		 * Retorna o ID do documento jurídico
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o título do documento jurídico
		 * @return string Título do documento jurídico detalhando a categoria e número referencial
		 */
		public function getTitulo() {
			return $this->titulo;
		}

		/**
		 * Retorna a descrição do documento jurídico
		 * @return string Descrição do documento jurídico resumindo sua finalidade
		 */
		public function getDescricao() {
			return $this->descricao;
		}

		/**
		 * Retorna o endereço onde está armazenado o arquivo PDF a partir da raiz
		 * @return string Endereço no formato "pasta/arquivo.pdf"
		 */
		public function getArquivo() {
			return $this->arquivo;
		}

		/**
		 * Retorna o usuário que criou o documento jurídico
		 * @return Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function getUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna a data e hora da criação ou edição do documento jurídico
		 * @return DateTime Data no formato "YYYY-mm-dd H:m:s"
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Retorna o status de atividade do documento jurídico
		 * @return boolean Inativo ou ativo
		 */
		public function isStatus() {
			return $this->status;
		}

		/**
		 * Valida o documento jurídico certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->titulo && $this->arquivo && $this->usuario->valida() && $this->data && $this->status) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>