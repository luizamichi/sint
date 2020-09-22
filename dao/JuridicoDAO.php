<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Juridico.php");
	/**
	 * Classe DAO Jurídico com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class JuridicoDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela JURIDICO
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
		 * Procura um jurídico no banco de dados pelo ID
		 * @param int $id ID do jurídico a ser buscado
		 * @return false|Juridico Objeto jurídico (ID, título, descrição, arquivo PDF, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT J.ID, J.TITULO, J.DESCRICAO, J.ARQUIVO, J.DATA, J.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id_permissao);
			$permissao->setBanner((bool) $banner);
			$permissao->setBoletim((bool) $boletim);
			$permissao->setConvencao((bool) $convencao);
			$permissao->setConvenio((bool) $convenio);
			$permissao->setDiretorio((bool) $diretorio);
			$permissao->setEdital((bool) $edital);
			$permissao->setEvento((bool) $evento);
			$permissao->setFinanca((bool) $financa);
			$permissao->setJornal((bool) $jornal);
			$permissao->setJuridico((bool) $juridico);
			$permissao->setNoticia((bool) $noticia);
			$permissao->setPodcast((bool) $podcast);
			$permissao->setRegistro((bool) $registro);
			$permissao->setTabela((bool) $tabela);
			$permissao->setUsuario((bool) $usuario);
			$usuario = new Usuario();
			$usuario->setId($id_usuario);
			$usuario->setNome($nome);
			$usuario->setEmail($email);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status_usuario);
			$juridico = new Juridico();
			$juridico->setId($id);
			$juridico->setTitulo($titulo);
			if($descricao) {
				$juridico->setDescricao($descricao);
			}
			$juridico->setArquivo($arquivo);
			$juridico->setUsuario($usuario);
			$juridico->setData($data);
			$juridico->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $juridico;
		}

		/**
		 * Procura um jurídico no banco de dados pelo nome do arquivo
		 * @param string $arquivo Arquivo ou parte do arquivo a ser buscado
		 * @return array Jurídicos encontrados
		 */
		public function procurarArquivo(string $arquivo) {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.ARQUIVO LIKE CONCAT('%',?,'%') ORDER BY J.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $arquivo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Procura um jurídico no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Jurídicos encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Procura um jurídico no banco de dados pela descrição
		 * @param string $descricao Descrição do periódico
		 * @return array Jurídicos encontrados
		 */
		public function procurarDescricao(string $descricao) {
			$query = "SELECT J.ID, J.TITULO, J.DESCRICAO, J.ARQUIVO, J.DATA, J.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.DESCRICAO LIKE CONCAT('%',?,'%') ORDER BY J.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $descricao);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Procura um jurídico no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Jurídicos encontrados
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.TITULO LIKE CONCAT('%',?,'%') ORDER BY J.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Procura o último jurídico cadastrado no banco de dados
		 * @return false|Juridico Objeto jurídico (ID, título, descrição, arquivo PDF, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id_permissao);
			$permissao->setBanner((bool) $banner);
			$permissao->setBoletim((bool) $boletim);
			$permissao->setConvencao((bool) $convencao);
			$permissao->setConvenio((bool) $convenio);
			$permissao->setDiretorio((bool) $diretorio);
			$permissao->setEdital((bool) $edital);
			$permissao->setEvento((bool) $evento);
			$permissao->setFinanca((bool) $financa);
			$permissao->setJornal((bool) $jornal);
			$permissao->setJuridico((bool) $juridico);
			$permissao->setNoticia((bool) $noticia);
			$permissao->setPodcast((bool) $podcast);
			$permissao->setRegistro((bool) $registro);
			$permissao->setTabela((bool) $tabela);
			$permissao->setUsuario((bool) $usuario);
			$usuario = new Usuario();
			$usuario->setId($id_usuario);
			$usuario->setNome($nome);
			$usuario->setEmail($email);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status_usuario);
			$juridico = new Juridico();
			$juridico->setId($id);
			$juridico->setTitulo($titulo);
			if($descricao) {
				$juridico->setDescricao($descricao);
			}
			$juridico->setArquivo($arquivo);
			$juridico->setUsuario($usuario);
			$juridico->setData($data);
			$juridico->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $juridico;
		}

		/**
		 * Insere um novo jurídico no banco de dados
		 * @param Juridico $juridico Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Juridico $juridico) {
			$query = "INSERT INTO JURIDICO (TITULO, DESCRICAO, ARQUIVO, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $juridico->getTitulo();
			$descricao = $juridico->getDescricao();
			$arquivo = $juridico->getArquivo();
			$usuario = $juridico->getUsuario()->getId();
			$data = $juridico->getData()->format("Y-m-d H:i:s");
			$status = $juridico->isStatus();
			mysqli_stmt_bind_param($stmt, "sssisi", $titulo, $descricao, $arquivo, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um jurídico no banco de dados
		 * @param Juridico $juridico Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Juridico $juridico) {
			$query = "UPDATE JURIDICO SET TITULO=?, DESCRICAO=?, ARQUIVO=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $juridico->getTitulo();
			$descricao = $juridico->getDescricao();
			$arquivo = $juridico->getArquivo();
			$usuario = $juridico->getUsuario()->getId();
			$data = $juridico->getData()->format("Y-m-d H:i:s");
			$status = $juridico->isStatus();
			$id = $juridico->getId();
			mysqli_stmt_bind_param($stmt, "sssisii", $titulo, $descricao, $arquivo, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um jurídico do banco de dados
		 * @param Juridico $juridico Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Juridico $juridico) {
			$query = "DELETE FROM JURIDICO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $juridico->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa um jurídico no banco de dados
		 * @param Juridico $juridico Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Juridico $juridico) {
			$query = "UPDATE JURIDICO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $juridico->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa um jurídico no banco de dados
		 * @param Juridico $juridico Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Juridico $juridico) {
			$query = "UPDATE JURIDICO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $juridico->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os jurídicos do banco de dados ordenados decrescentemente pelo ID
		 * @return array Jurídicos encontrados
		 */
		public function listar() {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Lista os N jurídicos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de jurídicos a ser listado
		 * @return array|false Jurídicos encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Lista os N jurídicos ativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de jurídicos a ser listado
		 * @return array|false Jurídicos encontrados
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=1 ORDER BY J.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Lista os N jurídicos inativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de jurídicos a ser listado
		 * @return array|false Jurídicos encontrados
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=0 ORDER BY J.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Lista os jurídicos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro jurídico
		 * @param int $quantidade Limite do último jurídico
		 * @return array|false Jurídicos encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Lista os jurídicos ativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro jurídico
		 * @param int $quantidade Limite do último jurídico
		 * @return array|false Jurídicos encontrados
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=1 ORDER BY J.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Lista os jurídicos inativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro jurídico
		 * @param int $quantidade Limite do último jurídico
		 * @return array|false Jurídicos encontrados
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JURIDICO AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=0 ORDER BY J.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$juridicos = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id_permissao);
				$permissao->setBanner((bool) $banner);
				$permissao->setBoletim((bool) $boletim);
				$permissao->setConvencao((bool) $convencao);
				$permissao->setConvenio((bool) $convenio);
				$permissao->setDiretorio((bool) $diretorio);
				$permissao->setEdital((bool) $edital);
				$permissao->setEvento((bool) $evento);
				$permissao->setFinanca((bool) $financa);
				$permissao->setJornal((bool) $jornal);
				$permissao->setJuridico((bool) $juridico);
				$permissao->setNoticia((bool) $noticia);
				$permissao->setPodcast((bool) $podcast);
				$permissao->setRegistro((bool) $registro);
				$permissao->setTabela((bool) $tabela);
				$permissao->setUsuario((bool) $usuario);
				$usuario = new Usuario();
				$usuario->setId($id_usuario);
				$usuario->setNome($nome);
				$usuario->setEmail($email);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$juridico = new Juridico();
				$juridico->setId($id);
				$juridico->setTitulo($titulo);
				$descricao && $juridico->setDescricao($descricao);
				$juridico->setArquivo($arquivo);
				$juridico->setUsuario($usuario);
				$juridico->setData($data);
				$juridico->setStatus((bool) $status);
				array_push($juridicos, $juridico);
			}
			mysqli_stmt_close($stmt);
			return $juridicos;
		}

		/**
		 * Retorna o número de jurídicos cadastrados no banco de dados
		 * @return int Jurídicos cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM JURIDICO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de jurídicos ativos no banco de dados
		 * @return int Jurídicos cadastrados ativos
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM JURIDICO WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de jurídicos inativos no banco de dados
		 * @return int Jurídicos cadastrados inativos
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM JURIDICO WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>