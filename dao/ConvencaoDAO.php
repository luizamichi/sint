<?php
	include_once("../modelos/Convencao.php");
	include_once("../modelos/Database.php");
	/**
	 * Classe DAO Convenção com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class ConvencaoDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela CONVENCAO
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
		 * Procura uma convenção no banco de dados pelo ID
		 * @param int $id ID da convenção a ser buscada
		 * @return Convencao|false Objeto convenção (ID, título, arquivo PDF, tipo, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$convencao = new Convencao;
			$convencao->setId($id);
			$convencao->setTitulo($titulo);
			$convencao->setArquivo($arquivo);
			$convencao->setTipo((bool) $tipo);
			$convencao->setUsuario($usuario);
			$convencao->setData($data);
			$convencao->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $convencao;
		}

		/**
		 * Procura uma convenção no banco de dados pelo nome do arquivo
		 * @param string $arquivo Arquivo ou parte do arquivo a ser buscado
		 * @return array Convenções encontradas
		 */
		public function procurarArquivo(string $arquivo) {
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.ARQUIVO LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $arquivo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Procura uma convenção no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Convenções encontradas
		 */
		public function procurarData(string $data) {
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Procura uma convenção no banco de dados pelo tipo (vigente ou anterior)
		 * @param boolean $tipo Vigente (1) ou anterior (0)
		 * @return array Convenções encontradas
		 */
		public function procurarTipo(bool $tipo) {
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.TIPO=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $tipo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Procura uma convenção no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Convenções encontradas
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.TITULO LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Procura a última convenção cadastrada no banco de dados
		 * @return Convencao|false Objeto convenção (ID, título, arquivo PDF, tipo, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$convencao = new Convencao;
			$convencao->setId($id);
			$convencao->setTitulo($titulo);
			$convencao->setArquivo($arquivo);
			$convencao->setTipo((bool) $tipo);
			$convencao->setUsuario($usuario);
			$convencao->setData($data);
			$convencao->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $convencao;
		}

		/**
		 * Insere uma nova convenção no banco de dados
		 * @param Convencao $convencao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Convencao $convencao) {
			$query = "INSERT INTO CONVENCAO (TITULO, ARQUIVO, TIPO, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $convencao->getTitulo();
			$arquivo = $convencao->getArquivo();
			$tipo = $convencao->isTipo();
			$usuario = $convencao->getUsuario()->getId();
			$data = $convencao->getData()->format("Y-m-d H:i:s");
			$status = $convencao->isStatus();
			mysqli_stmt_bind_param($stmt, "ssiisi", $titulo, $arquivo, $tipo, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera uma convenção no banco de dados
		 * @param Convencao $convencao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Convencao $convencao) {
			$query = "UPDATE CONVENCAO SET TITULO=?, ARQUIVO=?, TIPO=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $convencao->getTitulo();
			$arquivo = $convencao->getArquivo();
			$tipo = $convencao->isTipo();
			$usuario = $convencao->getUsuario()->getId();
			$data = $convencao->getData()->format("Y-m-d H:i:s");
			$status = $convencao->isStatus();
			$id = $convencao->getId();
			mysqli_stmt_bind_param($stmt, "ssiisii", $titulo, $arquivo, $tipo, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove uma convenção do banco de dados
		 * @param Convencao $convencao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Convencao $convencao) {
			$query = "DELETE FROM CONVENCAO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $convencao->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa uma convenção no banco de dados
		 * @param Convencao $convencao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Convencao $convencao) {
			$query = "UPDATE CONVENCAO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $convencao->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa uma convenção no banco de dados
		 * @param Convencao $convencao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Convencao $convencao) {
			$query = "UPDATE CONVENCAO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $convencao->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todas as convenções do banco de dados ordenadas decrescentemente pelo ID
		 * @return array Convenções encontradas
		 */
		public function listar() {
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Lista as N convenções do banco de dados ordenadas decrescentemente pelo ID
		 * @param int $numero Número de convenções a ser listado
		 * @return array|false Convenções encontradas
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Lista as N convenções ativas do banco de dados ordenadas decrescentemente pelo ID
		 * @param int $numero Número de convenções a ser listado
		 * @return array|false Convenções encontradas
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=1 ORDER BY C.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Lista as N convenções inativas do banco de dados ordenadas decrescentemente pelo ID
		 * @param int $numero Número de convenções a ser listado
		 * @return array|false Convenções encontradas
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=0 ORDER BY C.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Lista as convenções do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número da primeira convenção
		 * @param int $quantidade Limite da última convenção
		 * @return array|false Convenções encontradas
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Lista as convenções ativas do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número da primeira convenção
		 * @param int $quantidade Limite da última convenção
		 * @return array|false Convenções encontradas
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=1 ORDER BY C.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Lista as convenções inativas do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número da primeira convenção
		 * @param int $quantidade Limite da última convenção
		 * @return array|false Convenções encontradas
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT C.ID, C.TITULO, C.ARQUIVO, C.TIPO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENCAO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=0 ORDER BY C.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $arquivo, $tipo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convencoes = array();
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
				$convencao = new Convencao;
				$convencao->setId($id);
				$convencao->setTitulo($titulo);
				$convencao->setArquivo($arquivo);
				$convencao->setTipo((bool) $tipo);
				$convencao->setUsuario($usuario);
				$convencao->setData($data);
				$convencao->setStatus((bool) $status);
				array_push($convencoes, $convencao);
			}
			mysqli_stmt_close($stmt);
			return $convencoes;
		}

		/**
		 * Retorna o número de convenções cadastradas no banco de dados
		 * @return int Convenções cadastradas
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM CONVENCAO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de convenções ativas no banco de dados
		 * @return int Convenções cadastradas ativas
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM CONVENCAO WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de convenções inativas no banco de dados
		 * @return int Convenções cadastradas inativas
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM CONVENCAO WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>