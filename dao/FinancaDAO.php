<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Financa.php");
	/**
	 * Classe DAO Finança com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class FinancaDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela FINANCA
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
		 * Procura uma finança no banco de dados pelo ID
		 * @param int $id ID da finança a ser buscada
		 * @return false|Financa Objeto finança (ID, período, arquivo PDF, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT F.ID, F.PERIODO, F.ARQUIVO, F.DATA, F.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON F.USUARIO=P.ID WHERE F.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$financa = new Financa();
			$financa->setId($id);
			$financa->setPeriodo($periodo);
			$financa->setArquivo($arquivo);
			$financa->setUsuario($usuario);
			$financa->setData($data);
			$financa->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $financa;
		}

		/**
		 * Procura uma finança no banco de dados pelo nome do arquivo
		 * @param string $arquivo Arquivo ou parte do arquivo a ser buscado
		 * @return array Finanças encontradas
		 */
		public function procurarArquivo(string $arquivo) {
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE F.ARQUIVO LIKE CONCAT('%',?,'%') ORDER BY F.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $arquivo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Procura uma finança no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Finanças encontradas
		 */
		public function procurarData(string $data) {
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE F.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Procura uma finança no banco de dados pelo período
		 * @param string $periodo Período ou parte do período a ser buscado
		 * @return array Finanças encontradas
		 */
		public function procurarPeriodo(string $periodo) {
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE F.PERIODO LIKE CONCAT('%',?,'%') ORDER BY F.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $periodo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Procura a última finança cadastrada no banco de dados
		 * @return false|Financa Objeto finança (ID, período, arquivo PDF, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY F.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$financa = new Financa();
			$financa->setId($id);
			$financa->setPeriodo($periodo);
			$financa->setArquivo($arquivo);
			$financa->setUsuario($usuario);
			$financa->setData($data);
			$financa->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $financa;
		}

		/**
		 * Insere uma nova finança no banco de dados
		 * @param Financa $financa Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Financa $financa) {
			$query = "INSERT INTO FINANCA (PERIODO, ARQUIVO, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$periodo = $financa->getPeriodo();
			$arquivo = $financa->getArquivo();
			$usuario = $financa->getUsuario()->getId();
			$data = $financa->getData()->format("Y-m-d H:i:s");
			$status = $financa->isStatus();
			mysqli_stmt_bind_param($stmt, "ssisi", $periodo, $arquivo, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera uma finança no banco de dados
		 * @param Financa $financa Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Financa $financa) {
			$query = "UPDATE FINANCA SET PERIODO=?, ARQUIVO=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$periodo = $financa->getPeriodo();
			$arquivo = $financa->getArquivo();
			$usuario = $financa->getUsuario()->getId();
			$data = $financa->getData()->format("Y-m-d H:i:s");
			$status = $financa->isStatus();
			$id = $financa->getId();
			mysqli_stmt_bind_param($stmt, "ssisii", $periodo, $arquivo, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove uma finança do banco de dados
		 * @param Financa $financa Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Financa $financa) {
			$query = "DELETE FROM FINANCA WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $financa->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa uma finança no banco de dados
		 * @param Financa $financa Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Financa $financa) {
			$query = "UPDATE FINANCA SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $financa->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa uma finança no banco de dados
		 * @param Financa $financa Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Financa $financa) {
			$query = "UPDATE FINANCA SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $financa->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todas as finanças do banco de dados ordenadas decrescentemente pelo ID
		 * @return array Finanças encontradas
		 */
		public function listar() {
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY F.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Lista as N finanças do banco de dados ordenadas decrescentemente pelo ID
		 * @param int $numero Número de finanças a ser listado
		 * @return array|false Finanças encontradas
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY F.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Lista as N finanças ativas do banco de dados ordenadas decrescentemente pelo ano (período)
		 * @param int $numero Número de finanças a ser listado
		 * @return array|false Finanças encontradas
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE F.STATUS=1 ORDER BY RIGHT(F.PERIODO, 4) DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Lista as N finanças inativas do banco de dados ordenadas decrescentemente pelo ID
		 * @param int $numero Número de finanças a ser listado
		 * @return array|false Finanças encontradas
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE F.STATUS=0 ORDER BY F.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Lista as finanças do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número da primeira finança
		 * @param int $quantidade Limite da última finança
		 * @return array|false Finanças encontradas
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY F.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Lista as finanças ativas do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número da primeira finança
		 * @param int $quantidade Limite da última finança
		 * @return array|false Finanças encontradas
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE F.STATUS=1 ORDER BY F.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Lista as finanças inativas do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número da primeira finança
		 * @param int $quantidade Limite da última finança
		 * @return array|false Finanças encontradas
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT F.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM FINANCA AS F INNER JOIN USUARIO AS U ON F.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE F.STATUS=0 ORDER BY F.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $periodo, $arquivo, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$financas = array();
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
				$financa = new Financa();
				$financa->setId($id);
				$financa->setPeriodo($periodo);
				$financa->setArquivo($arquivo);
				$financa->setUsuario($usuario);
				$financa->setData($data);
				$financa->setStatus((bool) $status);
				array_push($financas, $financa);
			}
			mysqli_stmt_close($stmt);
			return $financas;
		}

		/**
		 * Retorna o número de finanças cadastradas no banco de dados
		 * @return int Finanças cadastradas
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM FINANCA";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de finanças ativas no banco de dados
		 * @return int Finanças cadastradas ativas
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM FINANCA WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de finanças inativas no banco de dados
		 * @return int Finanças cadastradas inativas
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM FINANCA WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>