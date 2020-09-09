<?php
	include_once("Usuario.php");
	/**
	 * Classe Convênio com atributos simples (ID, título, descrição, cidade, telefone, celular, site, e-mail, imagem, arquivo PDF, usuário, data e status)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Convenio {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $titulo Nome da empresa ou do convênio
		 * @var string $descricao Descrição detalhando o convênio
		 * @var string $cidade Cidade de atendimento do convênio
		 * @var string $telefone Número do telefone fixo
		 * @var string $celular Número do telefone celular
		 * @var string $site Endereço web
		 * @var string $email Endereço de e-mail
		 * @var string $imagem Endereço da imagem em formato JPG ou PNG
		 * @var string $arquivo Endereço do arquivo em formato PDF
		 * @var Usuario $usuario Usuário (ID, nome, e-mail, login, senha, permissão e status)
		 * @var DateTime $data Data do registro na ordem ano-mês-dia hora:minuto:segundo
		 * @var boolean $status Inativo (FALSE) ou ativo (TRUE)
		 */
		private $id;
		private $titulo;
		private $descricao;
		private $cidade;
		private $telefone;
		private $celular;
		private $site;
		private $email;
		private $imagem;
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
			$this->descricao = "";
			$this->cidade = "";
			$this->telefone = NULL;
			$this->celular = NULL;
			$this->site = NULL;
			$this->email = NULL;
			$this->imagem = "";
			$this->arquivo = NULL;
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
			$this->cidade = NULL;
			$this->telefone = NULL;
			$this->celular = NULL;
			$this->site = NULL;
			$this->email = NULL;
			$this->imagem = NULL;
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
			$this->cidade = clone $this->cidade;
			$this->telefone = clone $this->telefone;
			$this->celular = clone $this->celular;
			$this->site = clone $this->site;
			$this->email = clone $this->email;
			$this->imagem = clone $this->imagem;
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
			"\nCIDADE: " . $this->cidade .
			"\nTELEFONE: " . preg_replace("/^([0-9]{2})([0-9]{4})([0-9]{4})$/", "($1) $2-$3", (string) $this->telefone) .
			"\nCELULAR: " . preg_replace("/^([0-9]{2})([0-9]{5})([0-9]{4})$/", "($1) $2-$3", (string) $this->celular) .
			"\nSITE: " . $this->site .
			"\nE-MAIL: " . $this->email .
			"\nIMAGEM: " . $this->imagem .
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
		 * Atribui um novo valor ao título do convênio
		 * @param string $titulo Nome da empresa ou do convênio com no mínimo 6 e no máximo 128 caracteres
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
		 * Atribui um novo valor à descrição do convênio
		 * @param string $descricao Descrição detalhada do convênio com no mínimo 6 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setDescricao(string $descricao) {
			$descricao = trim($descricao);
			if(strlen($descricao) >= 6) {
				$this->descricao = $descricao;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor a cidade do convênio
		 * @param string $cidade Cidade de atendimento da empresa com no mínimo 6 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setCidade(string $cidade) {
			$cidade = trim($cidade);
			if(strlen($cidade) >= 6 && strlen($cidade) <= 64) {
				$this->cidade = $cidade;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao telefone
		 * @param string $telefone Número de telefone no formato (XX) XXXX-XXXX ou XXXXXXXXXX
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setTelefone(string $telefone) {
			$telefone = preg_replace("/[^0-9]/is", "", (string) $telefone);
			if(preg_match("/^[0-9]{10}$/", $telefone)) {
				$this->telefone = $telefone;
				return TRUE;
			}
			else if(empty($telefone)) {
				$this->telefone = NULL;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao telefone celular
		 * @param string $celular Número de telefone celular no formato (XX) XXXXX-XXXX ou XXXXXXXXXXX
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setCelular(string $celular) {
			$celular = preg_replace("/[^0-9]/is", "", (string) $celular);
			if(preg_match("/^[0-9]{11}$/", $celular)) {
				$this->celular = $celular;
				return TRUE;
			}
			else if(empty($celular)) {
				$this->celular = NULL;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao site do convênio ou da empresa
		 * @param string $site Website do convênio com no mínimo 12 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setSite(string $site) {
			$site = trim($site);
			if(strlen($site) >= 12 && strlen($site) <= 64 && filter_var($site, FILTER_VALIDATE_URL)) {
				$this->site = $site;
				return TRUE;
			}
			else if(empty($site)) {
				$this->site = NULL;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao e-mail
		 * @param string $email E-mail no formato nome@dominio.com.br com no mínimo 6 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setEmail(string $email) {
			$email = trim($email);
			if(filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) >= 6 && strlen($email) <= 64) {
				$this->email = strtolower($email);
				return TRUE;
			}
			else if(empty($email)) {
				$this->email = NULL;
				return TRUE;
			}
			else {
				return FALSE;
			}
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
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo hash à imagem
		 * @param string $imagem Endereço da imagem JPG ou PNG de tamanho <= 1MB com no mínimo 5 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function hashImagem(string $imagem) {
			if((strcmp(strtolower(substr($imagem, -3)), "jpg") == 0 || strcmp(strtolower(substr($imagem, -3)), "png") == 0) && strlen($imagem) >= 5) {
				if(strlen($imagem) > 34) {
					$this->imagem = mb_strtolower("uploads/convenios/" . date("YmdHis") . substr($imagem, -32));
				}
				else {
					$this->imagem = mb_strtolower("uploads/convenios/" . date("YmdHis") . $imagem);
				}
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
			else if(empty($arquivo)) {
				$this->arquivo = NULL;
			}
			return FALSE;
		}

		/**
		 * Atribui um novo hash ao arquivo
		 * @param string $arquivo Endereço do arquivo PDF de tamanho <= 3MB com no mínimo 5 e no máximo 64 caracteres
		 * @param bool $limpa Limpa o arquivo
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function hashArquivo(string $arquivo, bool $limpa = TRUE) {
			if(strcmp(strtolower(substr($arquivo, -3)), "pdf") == 0 && strlen($arquivo) >= 5) {
				if(strlen($arquivo) > 31) {
					$this->arquivo = mb_strtolower("uploads/convenios/" . date("YmdHis") . substr($arquivo, -32));
				}
				else {
					$this->arquivo = mb_strtolower("uploads/convenios/" . date("YmdHis") . $arquivo);
				}
				return TRUE;
			}
			else if(empty($arquivo) && $limpa) {
				$this->arquivo = NULL;
			}
			return FALSE;
		}

		/**
		 * Atribui um novo usuário
		 * @param Usuario $usuario Usuário responsável pelo convênio
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
		 * Atribui um novo valor ao status do convênio
		 * @param boolean $status Booleano referente ao estado
		 * @return void
		 */
		public function setStatus(bool $status) {
			$this->status = $status;
		}

		/**
		 * Retorna o ID do convênio
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o título do convênio
		 * @return string Título do convênio ou nome da empresa
		 */
		public function getTitulo() {
			return $this->titulo;
		}

		/**
		 * Retorna a descrição do convênio
		 * @return string Descrição detalhada do convênio ou da empresa
		 */
		public function getDescricao() {
			return $this->descricao;
		}

		/**
		 * Retorna a cidade da empresa
		 * @return string Cidade onde a empresa do convênio está localizada
		 */
		public function getCidade() {
			return $this->cidade;
		}

		/**
		 * Retorna o telefone da empresa
		 * @return string Telefone fixo
		 */
		public function getTelefone() {
			return $this->telefone;
		}

		/**
		 * Retorna o celular da empresa
		 * @return string Telefone celular
		 */
		public function getCelular() {
			return $this->celular;
		}

		/**
		 * Retorna o website da empresa
		 * @return string Endereço web
		 */
		public function getSite() {
			return $this->site;
		}

		/**
		 * Retorna o e-mail da empresa
		 * @return string E-mail da caixa postal
		 */
		public function getEmail() {
			return $this->email;
		}

		/**
		 * Retorna o endereço onde a imagem está armazenada a partir da raiz
		 * @return string Endereço no formato "pasta/imagem.(jpg, png)"
		 */
		public function getImagem() {
			return $this->imagem;
		}

		/**
		 * Retorna o endereço onde está armazenado o arquivo PDF a partir da raiz
		 * @return string Endereço no formato "pasta/arquivo.pdf"
		 */
		public function getArquivo() {
			return $this->arquivo;
		}

		/**
		 * Retorna o usuário que criou o convênio
		 * @return Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function getUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna a data e hora da criação ou edição do convênio
		 * @return DateTime Data no formato "YYYY-mm-dd H:m:s"
		 */
		public function getData() {
			return $this->data;
		}

		/**
		 * Retorna o status de atividade do convênio
		 * @return boolean Inativo ou ativo
		 */
		public function isStatus() {
			return $this->status;
		}

		/**
		 * Valida o convênio certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->titulo && $this->descricao && $this->cidade && $this->imagem && $this->usuario->valida() && $this->data && $this->status) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>