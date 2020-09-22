<?php
	include_once("Usuario.php");
	/**
	 * Classe Evento com atributos simples (ID, título, descrição, diretório, imagens, usuário, data e status)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Evento {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $titulo Título do evento
		 * @var string $descricao Descrição do evento ocorrido
		 * @var string $diretorio Endereço do diretório com as imagens
		 * @var array $imagens Nomes das imagens em formato JPG ou PNG
		 * @var Usuario $usuario Usuário (ID, nome, e-mail, login, senha, permissão e status)
		 * @var DateTime $data Data do registro na ordem ano-mês-dia hora:minuto:segundo
		 * @var boolean $status Inativo (FALSE) ou ativo (TRUE)
		 */
		private $id;
		private $titulo;
		private $descricao;
		private $diretorio;
		private $imagens;
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
			$this->diretorio = "";
			$this->imagens = array();
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
			$this->diretorio = NULL;
			$this->imagens = NULL;
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
			$this->diretorio = clone $this->diretorio;
			$this->imagens = clone $this->imagens;
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
			"\nDIRETÓRIO: " . $this->diretorio .
			"\nIMAGENS: " . implode(", ", array_column($this->imagens, "IMAGEM")) .
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
				if(!empty($this->imagens)) {
					$imagens = array();
					foreach($this->imagens as $imagem) {
						$imagem["EVENTO"] = $this->id;
						array_push($imagens, $imagem);
					}
					$this->imagens = $imagens;
				}
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao título do evento
		 * @param string $titulo Título chamativo com no mínimo 6 e no máximo 128 caracteres
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
		 * Atribui um novo valor à descrição do evento
		 * @param string $descricao Descrição detalhando o evento com no mínimo 6 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setDescricao(string $descricao) {
			$descricao = trim($descricao);
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
		 * Atribui um novo valor ao diretório
		 * @param string $diretorio Endereço do diretório de armazenamento das imagens com no mínimo 5 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setDiretorio(string $diretorio) {
			if(strlen($diretorio) >= 5) {
				$this->diretorio = $diretorio;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo hash ao diretório
		 * @param string $diretorio Endereço do diretório de armazenamento das imagens com no mínimo 5 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function hashDiretorio(string $diretorio) {
			if(strlen($diretorio) >= 5) {
				if(strlen($diretorio) > 34) {
					$this->diretorio = mb_strtolower("uploads/eventos/" . date("YmdHis") . substr($diretorio, -34));
				}
				else {
					$this->diretorio = mb_strtolower("uploads/eventos/" . date("YmdHis") . $diretorio);
				}
				return TRUE;
			}
			else if(empty($diretorio)) {
				$this->diretorio = mb_strtolower("uploads/eventos/" . date("YmdHis"));
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor às imagens
		 * @param array $imagens Lista de imagens de tamanho <= 1MB com no mínimo 5 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setImagens(array $imagens) {
			if(!empty($imagens)) {
				foreach($imagens as $imagem) {
					if($imagem && (strcmp(strtolower(substr($imagem["IMAGEM"], -3)), "jpg") == 0 || strcmp(strtolower(substr($imagem["IMAGEM"], -3)), "png") == 0) && strlen($imagem["IMAGEM"]) >= 5 && strlen($imagem["IMAGEM"]) <= 64) {
						$imagem["STATUS"] ? array_push($this->imagens, $imagem) : FALSE;
					}
				}
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo usuário
		 * @param Usuario $usuario Usuário responsável pelo evento
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
		 * Atribui um novo valor ao status do evento
		 * @param boolean $status Booleano referente ao estado
		 * @return void
		 */
		public function setStatus(bool $status) {
			$this->status = $status;
		}

		/**
		 * Retorna o ID do evento
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o título do evento
		 * @return string Título do evento resumido
		 */
		public function getTitulo() {
			return $this->titulo;
		}

		/**
		 * Retorna a descrição do evento
		 * @return string Descrição do evento detalhando o evento ocorrido
		 */
		public function getDescricao() {
			return $this->descricao;
		}

		/**
		 * Retorna o endereço do diretório onde as imagens estão armazenadas
		 * @return string Endereço no formato "caminho/pasta"
		 */
		public function getDiretorio() {
			return $this->diretorio;
		}

		/**
		 * Retorna a primeira imagem da lista
		 * @return string Endereço da imagem no formato "imagem.(jpg, png)"
		 */
		public function getImagem() {
			return $this->diretorio . "/" . $this->imagens[0]["IMAGEM"];
		}

		/**
		 * Retorna a lista com todas as imagens armazenadas no diretório do evento
		 * @return array Nomes das imagens no formato "imagem.(jpg, png)"
		 */
		public function getImagens() {
			return $this->imagens;
		}

		/**
		 * Retorna o usuário que criou o evento
		 * @return Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function getUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna a data e hora da criação ou edição do evento
		 * @return DateTime Data no formato "YYYY-mm-dd H:m:s"
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Retorna o status de atividade do evento
		 * @return boolean Inativo ou ativo
		 */
		public function isStatus() {
			return $this->status;
		}

		/**
		 * Valida o evento certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->titulo && $this->diretorio && $this->imagens && $this->usuario->valida() && $this->data && $this->status) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>