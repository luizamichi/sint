<?php
	include_once("Usuario.php");
	/**
	 * Classe Jornal com atributos simples (ID, título, edição, arquivo PDF, imagem, usuário, data e status)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Jornal {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $titulo Título do jornal e período
		 * @var int $edicao Número da edição
		 * @var string $arquivo Endereço do arquivo em formato PDF
		 * @var string $imagem Endereço da imagem em formato JPG ou PNG
		 * @var Usuario $usuario Usuário (ID, nome, e-mail, login, senha, permissão e status)
		 * @var DateTime $data Data do registro na ordem ano-mês-dia hora:minuto:segundo
		 * @var boolean $status Inativo (FALSE) ou ativo (TRUE)
		 */
		private $id;
		private $titulo;
		private $edicao;
		private $arquivo;
		private $imagem;
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
			$this->edicao = 1;
			$this->arquivo = "";
			$this->imagem = NULL;
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
			$this->edicao = NULL;
			$this->arquivo = NULL;
			$this->imagem = NULL;
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
			$this->edicao = clone $this->edicao;
			$this->arquivo = clone $this->arquivo;
			$this->imagem = clone $this->imagem;
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
			"\nEDIÇÃO: " . $this->edicao .
			"\nARQUIVO: " . $this->arquivo .
			"\nIMAGEM: " . $this->imagem .
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
		 * Atribui um novo valor ao título do jornal
		 * @param string $titulo Título do jornal com no mínimo 6 e no máximo 128 caracteres
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
		 * Atribui um novo valor à edição do jornal
		 * @param int $edicao Número da edição da publicação
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setEdicao(int $edicao) {
			if($edicao > 0) {
				$this->edicao = $edicao;
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
					$this->arquivo = mb_strtolower("uploads/jornais/" . date("YmdHis") . substr($arquivo, -34));
				}
				else {
					$this->arquivo = mb_strtolower("uploads/jornais/" . date("YmdHis") . $arquivo);
				}
				return TRUE;
			}
			return FALSE;
		}

		/**
		 * Atribui um novo valor à imagem
		 * @param string $imagem Endereço da imagem JPG ou PNG de tamanho <= 1MB com no mínimo 5 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setImagem(string $imagem) {
			if((strcmp(strtolower(substr($imagem, -3)), "jpg") == 0 || strcmp(strtolower(substr($imagem, -3)), "png") == 0) && strlen($imagem) >= 5) {
				$this->imagem = $imagem;
				return TRUE;
			}
			else if(empty($imagem)) {
				$this->imagem = NULL;
			}
			return FALSE;
		}

		/**
		 * Atribui um novo hash à imagem
		 * @param string $imagem Endereço da imagem JPG ou PNG de tamanho <= 1MB com no mínimo 5 e no máximo 64 caracteres
		 * @param bool $limpa Limpa a imagem
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function hashImagem(string $imagem, bool $limpa = TRUE) {
			if((strcmp(strtolower(substr($imagem, -3)), "jpg") == 0 || strcmp(strtolower(substr($imagem, -3)), "png") == 0) && strlen($imagem) >= 5) {
				if(strlen($imagem) > 34) {
					$this->imagem = mb_strtolower("uploads/jornais/" . date("YmdHis") . substr($imagem, -34));
				}
				else {
					$this->imagem = mb_strtolower("uploads/jornais/" . date("YmdHis") . $imagem);
				}
				return TRUE;
			}
			else if(empty($imagem) && $limpa) {
				$this->imagem = NULL;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo usuário
		 * @param Usuario $usuario Usuário responsável pelo jornal
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
		 * Atribui um novo valor ao status do jornal
		 * @param boolean $status Booleano referente ao estado
		 * @return void
		 */
		public function setStatus(bool $status) {
			$this->status = $status;
		}

		/**
		 * Retorna o ID do jornal
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o título do jornal
		 * @return string Título do jornal resumindo o assunto do periódico
		 */
		public function getTitulo() {
			return $this->titulo;
		}

		/**
		 * Retorna a edição do jornal
		 * @return int Número da edição do periódico
		 */
		public function getEdicao() {
			return $this->edicao;
		}

		/**
		 * Retorna o endereço onde está armazenado o arquivo PDF a partir da raiz
		 * @return string Endereço no formato "pasta/arquivo.pdf"
		 */
		public function getArquivo() {
			return $this->arquivo;
		}

		/**
		 * Retorna o endereço onde a imagem está armazenada a partir da raiz
		 * @return string Endereço no formato "pasta/imagem.(jpg, png)"
		 */
		public function getImagem() {
			return $this->imagem;
		}

		/**
		 * Retorna o usuário que criou o jornal
		 * @return Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function getUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna a data e hora da criação ou edição do jornal
		 * @return DateTime Data no formato "YYYY-mm-dd H:m:s"
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Retorna o status de atividade do jornal
		 * @return boolean Inativo ou ativo
		 */
		public function isStatus() {
			return $this->status;
		}

		/**
		 * Valida o jornal certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->titulo && $this->edicao && $this->arquivo && $this->usuario->valida() && $this->data && $this->status) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>