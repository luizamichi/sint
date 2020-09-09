<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Usuario.php");
	/**
	 * Classe DAO Usuário com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class UsuarioDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela USUARIO
		 */
		private $database;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->database = NULL;
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->database = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->database = clone $this->database;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "SGBD: " . ($this->database->statusConexao() ? "CONECTADO\n" : "DESCONECTADO\n");
		}

		/**
		 * Atribui um novo valor ao banco de dados
		 * @param Database $database Conexão estabelecida com o SGBD
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setDatabase(Database $database) {
			if($database->statusConexao()) {
				$this->database = $database;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Retorna o status da conexão com o SGBD
		 * @return Database Conexão com o SGBD
		 */
		public function getDatabase() {
			return $this->database;
		}

		/**
		 * Procura um usuário no banco de dados pelo ID
		 * @param int $id ID do usuário a ser buscado
		 * @return false|Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID AND U.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id_permissao);
			$permissao->setBanner((bool) $banner_permissao);
			$permissao->setBoletim((bool) $boletim_permissao);
			$permissao->setConvencao((bool) $convencao_permissao);
			$permissao->setConvenio((bool) $convenio_permissao);
			$permissao->setDiretorio((bool) $diretorio_permissao);
			$permissao->setEdital((bool) $edital_permissao);
			$permissao->setEvento((bool) $evento_permissao);
			$permissao->setFinanca((bool) $financa_permissao);
			$permissao->setJornal((bool) $jornal_permissao);
			$permissao->setJuridico((bool) $juridico_permissao);
			$permissao->setNoticia((bool) $noticia_permissao);
			$permissao->setPodcast((bool) $podcast_permissao);
			$permissao->setRegistro((bool) $registro_permissao);
			$permissao->setTabela((bool) $tabela_permissao);
			$permissao->setUsuario((bool) $usuario_permissao);
			$usuario = new Usuario();
			$usuario->setId($id);
			$usuario->setNome($nome);
			$usuario->setEmail($email);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $usuario;
		}

		/**
		 * Procura um usuário no banco de dados pelo e-mail
		 * @param string $email E-mail ou parte do e-mail a ser buscado
		 * @return array Usuários encontrados
		 */
		public function procurarEmail(string $email) {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID AND U.EMAIL LIKE CONCAT('%',?,'%') ORDER BY U.NOME";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Procura um usuário no banco de dados pelo login
		 * @param string $login Login ou parte do login a ser buscado
		 * @return array Usuários encontrados
		 */
		public function procurarLogin(string $login) {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID AND U.LOGIN LIKE CONCAT('%',?,'%') ORDER BY U.NOME";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $login);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Procura um usuário no banco de dados pelo nome
		 * @param string $nome Nome ou parte do nome a ser buscado
		 * @return array Usuários encontrados
		 */
		public function procurarNome(string $nome) {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID AND U.NOME LIKE CONCAT('%',?,'%') ORDER BY U.NOME";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $nome);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Procura um usuário no banco de dados pela permissão
		 * @param Permissao $permissao Permissão do usuário a ser buscado
		 * @return false|Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function procurarPermissao(Permissao $permissao) {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID AND U.PERMISSAO=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $permissao->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id_permissao);
			$permissao->setBanner((bool) $banner_permissao);
			$permissao->setBoletim((bool) $boletim_permissao);
			$permissao->setConvencao((bool) $convencao_permissao);
			$permissao->setConvenio((bool) $convenio_permissao);
			$permissao->setDiretorio((bool) $diretorio_permissao);
			$permissao->setEdital((bool) $edital_permissao);
			$permissao->setEvento((bool) $evento_permissao);
			$permissao->setFinanca((bool) $financa_permissao);
			$permissao->setJornal((bool) $jornal_permissao);
			$permissao->setJuridico((bool) $juridico_permissao);
			$permissao->setNoticia((bool) $noticia_permissao);
			$permissao->setPodcast((bool) $podcast_permissao);
			$permissao->setRegistro((bool) $registro_permissao);
			$permissao->setTabela((bool) $tabela_permissao);
			$permissao->setUsuario((bool) $usuario_permissao);
			$usuario = new Usuario();
			$usuario->setId($id);
			$usuario->setNome($nome);
			$usuario->setEmail($email);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $usuario;
		}

		/**
		 * Procura o último usuário cadastrado no banco de dados
		 * @return false|Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY U.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id_permissao);
			$permissao->setBanner((bool) $banner_permissao);
			$permissao->setBoletim((bool) $boletim_permissao);
			$permissao->setConvencao((bool) $convencao_permissao);
			$permissao->setConvenio((bool) $convenio_permissao);
			$permissao->setDiretorio((bool) $diretorio_permissao);
			$permissao->setEdital((bool) $edital_permissao);
			$permissao->setEvento((bool) $evento_permissao);
			$permissao->setFinanca((bool) $financa_permissao);
			$permissao->setJornal((bool) $jornal_permissao);
			$permissao->setJuridico((bool) $juridico_permissao);
			$permissao->setNoticia((bool) $noticia_permissao);
			$permissao->setPodcast((bool) $podcast_permissao);
			$permissao->setRegistro((bool) $registro_permissao);
			$permissao->setTabela((bool) $tabela_permissao);
			$permissao->setUsuario((bool) $usuario_permissao);
			$usuario = new Usuario();
			$usuario->setId($id);
			$usuario->setNome($nome);
			$usuario->setEmail($email);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $usuario;
		}

		/**
		 * Procura um usuário no banco de dados pelo login
		 * @param string $login Login único atribuído ao usuário
		 * @return false|Usuario Objeto usuário (ID, nome, e-mail, login, senha, permissão e status)
		 */
		public function procurarUsuario(string $login) {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID AND U.LOGIN=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $login);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id_permissao);
			$permissao->setBanner((bool) $banner_permissao);
			$permissao->setBoletim((bool) $boletim_permissao);
			$permissao->setConvencao((bool) $convencao_permissao);
			$permissao->setConvenio((bool) $convenio_permissao);
			$permissao->setDiretorio((bool) $diretorio_permissao);
			$permissao->setEdital((bool) $edital_permissao);
			$permissao->setEvento((bool) $evento_permissao);
			$permissao->setFinanca((bool) $financa_permissao);
			$permissao->setJornal((bool) $jornal_permissao);
			$permissao->setJuridico((bool) $juridico_permissao);
			$permissao->setNoticia((bool) $noticia_permissao);
			$permissao->setPodcast((bool) $podcast_permissao);
			$permissao->setRegistro((bool) $registro_permissao);
			$permissao->setTabela((bool) $tabela_permissao);
			$permissao->setUsuario((bool) $usuario_permissao);
			$usuario = new Usuario();
			$usuario->setId($id);
			$usuario->setNome($nome);
			$usuario->setEmail($email);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $usuario;
		}

		/**
		 * Insere um novo usuário no banco de dados
		 * @param Usuario $usuario Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Usuario $usuario) {
			$query = "INSERT INTO USUARIO (NOME, EMAIL, LOGIN, SENHA, PERMISSAO, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$nome = strtoupper($usuario->getNome());
			$email = $usuario->getEmail();
			$login = $usuario->getLogin();
			$senha = $usuario->getSenha();
			$id_permissao = $usuario->getPermissao()->getId();
			$status = $usuario->isStatus();
			mysqli_stmt_bind_param($stmt, "ssssii", $nome, $email, $login, $senha, $id_permissao, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um usuário no banco de dados
		 * @param Usuario $usuario Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Usuario $usuario) {
			$query = "UPDATE USUARIO SET NOME=?, EMAIL=?, LOGIN=?, SENHA=?, PERMISSAO=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$nome = $usuario->getNome();
			$email = $usuario->getEmail();
			$login = $usuario->getLogin();
			$senha = $usuario->getSenha();
			$id_permissao = $usuario->getPermissao()->getId();
			$status = $usuario->isStatus();
			$id = $usuario->getId();
			mysqli_stmt_bind_param($stmt, "ssssiii", $nome, $email, $login, $senha, $id_permissao, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um usuário do banco de dados
		 * @param Usuario $usuario Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Usuario $usuario) {
			$query = "DELETE FROM USUARIO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $usuario->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa um usuário no banco de dados
		 * @param Usuario $usuario Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Usuario $usuario) {
			$query = "UPDATE USUARIO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $usuario->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa um usuário no banco de dados
		 * @param Usuario $usuario Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Usuario $usuario) {
			$query = "UPDATE USUARIO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $usuario->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os usuários do banco de dados ordenados decrescentemente pelo ID
		 * @return array Usuários encontrados
		 */
		public function listar() {
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID GROUP BY U.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Lista os N usuários do banco de dados ordenados crescentemente pelo nome
		 * @param int $numero Número de usuários a ser listado
		 * @return array|false Usuários encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID GROUP BY U.NOME ASC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Lista os N usuários ativos do banco de dados ordenados crescentemente pelo nome
		 * @param int $numero Número de usuários a ser listado
		 * @return array|false Usuários encontrados
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE U.STATUS=1 GROUP BY U.NOME ASC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Lista os N usuários inativos do banco de dados ordenados crescentemente pelo nome
		 * @param int $numero Número de usuários a ser listado
		 * @return array|false Usuários encontrados
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE U.STATUS=0 GROUP BY U.NOME ASC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Lista os usuários do banco de dados ordenados crescentemente pelo nome em um dado intervalo
		 * @param int $inicio Número do primeiro usuário
		 * @param int $quantidade Limite do último usuário
		 * @return array|false Usuários encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY U.NOME ASC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Lista os usuários ativos do banco de dados ordenados crescentemente pelo nome em um dado intervalo
		 * @param int $inicio Número do primeiro usuário
		 * @param int $quantidade Limite do último usuário
		 * @return array|false Usuários encontrados
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE U.STATUS=1 ORDER BY U.NOME ASC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Lista os usuários inativos do banco de dados ordenados crescentemente pelo nome em um dado intervalo
		 * @param int $inicio Número do primeiro usuário
		 * @param int $quantidade Limite do último usuário
		 * @return array|false Usuários encontrados
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM USUARIO AS U INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE U.STATUS=0 AND U.ID<=? ORDER BY U.NOME ASC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $nome, $email, $login, $senha, $status, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $edital_permissao, $evento_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$usuarios = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner_permissao);
				$permissao->setBoletim((bool) $boletim_permissao);
				$permissao->setConvencao((bool) $convencao_permissao);
				$permissao->setConvenio((bool) $convenio_permissao);
				$permissao->setDiretorio((bool) $diretorio_permissao);
				$permissao->setEdital((bool) $edital_permissao);
				$permissao->setEvento((bool) $evento_permissao);
				$permissao->setFinanca((bool) $financa_permissao);
				$permissao->setJornal((bool) $jornal_permissao);
				$permissao->setJuridico((bool) $juridico_permissao);
				$permissao->setNoticia((bool) $noticia_permissao);
				$permissao->setPodcast((bool) $podcast_permissao);
				$permissao->setRegistro((bool) $registro_permissao);
				$permissao->setTabela((bool) $tabela_permissao);
				$permissao->setUsuario((bool) $usuario_permissao);
				$usuario = new Usuario();
				$usuario->setId($id);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status);
				array_push($usuarios, $usuario);
			}
			mysqli_stmt_close($stmt);
			return $usuarios;
		}

		/**
		 * Retorna o número de usuários cadastrados no banco de dados
		 * @return int Usuários cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM USUARIO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de usuários ativos no banco de dados
		 * @return int Usuários cadastrados ativos
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM USUARIO WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de usuários inativos no banco de dados
		 * @return int Usuários cadastrados inativos
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM USUARIO WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>