<?php
	include_once("Permissao.php");
	/**
	 * Classe Usuário com atributos simples (ID, nome, e-mail, login, senha, permissão e status)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Usuario {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var string $nome Nome completo
		 * @var string $email Endereço de e-mail
		 * @var string $login Apelido único
		 * @var string $senha Senha alfanumérica
		 * @var Permissao $permissao Permissão (ID, CRUD(banner), CRUD(boletim), CRUD(convenção), CRUD(convênio), LEITURA(diretório), CRUD(edital), CRUD(evento), CRUD(finança), CRUD(jornal), CRUD(jurídico), CRUD(notícia), CRUD(podcast), LEITURA(registro), LEITURA(tabela) e CRUD(usuário))
		 * @var boolean $status Inativo (FALSE) ou ativo (TRUE)
		 */
		private $id;
		private $nome;
		private $email;
		private $login;
		private $senha;
		private $permissao;
		private $status;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->id = 0;
			$this->nome = "";
			$this->email = "";
			$this->login = "";
			$this->senha = "";
			$this->permissao = new Permissao();
			$this->status = FALSE;
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->id = NULL;
			$this->nome = NULL;
			$this->email = NULL;
			$this->login = NULL;
			$this->senha = NULL;
			$this->permissao = NULL;
			$this->status = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->id = clone $this->id;
			$this->nome = clone $this->nome;
			$this->email = clone $this->email;
			$this->login = clone $this->login;
			$this->senha = clone $this->senha;
			$this->permissao = clone $this->permissao;
			$this->status = clone $this->status;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "ID: " . $this->id .
			"\nNOME: " . $this->nome .
			"\nE-MAIL: " . $this->email .
			"\nLOGIN: " . $this->login .
			"\nSENHA: " . $this->senha .
			"\nPERMISSÃO: " . $this->permissao->getId() .
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
		 * Atribui um novo valor ao nome
		 * @param string $nome Nome completo do usuário com no mínimo 6 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setNome(string $nome) {
			$nome = trim($nome);
			if(strlen($nome) >= 6 && strlen($nome) <= 64) {
				$this->nome = mb_strtoupper($nome);
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
				$this->email = mb_strtolower($email);
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao login
		 * @param string $login Texto alfanumérico único vinculado ao usuário com no mínimo 6 e no máximo 64 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setLogin(string $login) {
			$login = trim($login);
			if(strlen($login) >= 6 && strlen($login) <= 64) {
				$this->login = $login;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor à senha
		 * @param string $senha Texto alfanumérico secreto vinculado ao usuário com no mínimo 6 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setSenha(string $senha) {
			if(strlen($senha) >= 6) {
				$this->senha = $senha;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor criptografado com MD5 à senha
		 * @param string $senha Texto alfanumérico secreto vinculado ao usuário com no mínimo 6 caracteres
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function hashSenha(string $senha) {
			if(strlen($senha) >= 6) {
				$this->senha = md5($senha);
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor em branco à senha
		 * @return void
		 */
		public function limpaSenha() {
			$this->senha = "";
		}

		/**
		 * Atribui uma permissão (objeto) ao usuário
		 * @param Permissao $permissao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setPermissao(Permissao $permissao) {
			if($permissao->valida()) {
				$this->permissao = $permissao;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor ao status do usuário
		 * @param boolean $status Booleano referente ao estado
		 * @return void
		 */
		public function setStatus(bool $status) {
			$this->status = $status;
		}

		/**
		 * Retorna o ID do usuário
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o nome do usuário
		 * @return string Nome completo
		 */
		public function getNome() {
			return $this->nome;
		}

		/**
		 * Retorna o e-mail do usuário
		 * @return string E-mail da caixa postal
		 */
		public function getEmail() {
			return $this->email;
		}

		/**
		 * Retorna o login do usuário
		 * @return string Chave única
		 */
		public function getLogin() {
			return $this->login;
		}

		/**
		 * Retorna a senha do usuário
		 * @return string Chave secreta
		 */
		public function getSenha() {
			return $this->senha;
		}

		/**
		 * Retorna as permissões do usuário
		 * @return Permissao Objeto permissão (ID, CRUD(banner), CRUD(boletim), CRUD(convenção), CRUD(convênio), LEITURA(diretório), CRUD(edital), CRUD(evento), CRUD(finança), CRUD(jornal), CRUD(jurídico), CRUD(notícia), CRUD(podcast), LEITURA(registro), LEITURA(tabela) e CRUD(usuário))
		 */
		public function getPermissao() {
			return $this->permissao;
		}

		/**
		 * Retorna o status de atividade do usuário
		 * @return boolean Inativo ou ativo
		 */
		public function isStatus() {
			return $this->status;
		}

		/**
		 * Valida o usuário certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && $this->nome && $this->email && $this->login && $this->senha && $this->permissao->valida() && $this->status) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>