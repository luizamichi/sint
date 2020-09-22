<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Podcast.php");
	/**
	 * Classe DAO Podcast com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class PodcastDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela PODCAST
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
		 * Procura um podcast no banco de dados pelo ID
		 * @param int $id ID do podcast a ser buscado
		 * @return false|Podcast Objeto podcast (ID, título, áudio, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$podcast = new Podcast;
			$podcast->setId($id);
			$podcast->setTitulo($titulo);
			$podcast->setAudio($audio);
			$podcast->setUsuario($usuario);
			$podcast->setData($data);
			$podcast->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $podcast;
		}

		/**
		 * Procura um podcast no banco de dados pelo nome do áudio
		 * @param string $audio Áudio ou parte do áudio a ser buscado
		 * @return array Podcasts encontrados
		 */
		public function procurarAudio(string $audio) {
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.AUDIO LIKE CONCAT('%',?,'%') ORDER BY P.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $audio);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Procura um podcast no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Podcasts encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Procura um podcast no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Podcasts encontrados
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.TITULO LIKE CONCAT('%',?,'%') ORDER BY P.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Procura o último podcast cadastrado no banco de dados
		 * @return false|Podcast Objeto podcast (ID, título, áudio, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID ORDER BY P.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$podcast = new Podcast;
			$podcast->setId($id);
			$podcast->setTitulo($titulo);
			$podcast->setAudio($audio);
			$podcast->setUsuario($usuario);
			$podcast->setData($data);
			$podcast->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $podcast;
		}

		/**
		 * Insere um novo podcast no banco de dados
		 * @param Podcast $podcast Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Podcast $podcast) {
			$query = "INSERT INTO PODCAST (TITULO, AUDIO, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $podcast->getTitulo();
			$audio = $podcast->getAudio();
			$usuario = $podcast->getUsuario()->getId();
			$data = $podcast->getData()->format("Y-m-d H:i:s");
			$status = $podcast->isStatus();
			mysqli_stmt_bind_param($stmt, "ssisi", $titulo, $audio, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um podcast no banco de dados
		 * @param Podcast $podcast Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Podcast $podcast) {
			$query = "UPDATE PODCAST SET TITULO=?, AUDIO=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $podcast->getTitulo();
			$audio = $podcast->getAudio();
			$usuario = $podcast->getUsuario()->getId();
			$data = $podcast->getData()->format("Y-m-d H:i:s");
			$status = $podcast->isStatus();
			$id = $podcast->getId();
			mysqli_stmt_bind_param($stmt, "ssisii", $titulo, $audio, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um podcast do banco de dados
		 * @param Podcast $podcast Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Podcast $podcast) {
			$query = "DELETE FROM PODCAST WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $podcast->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa um podcast no banco de dados
		 * @param Podcast $podcast Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Podcast $podcast) {
			$query = "UPDATE PODCAST SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $podcast->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa um podcast no banco de dados
		 * @param Podcast $podcast Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Podcast $podcast) {
			$query = "UPDATE PODCAST SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $podcast->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os podcasts do banco de dados ordenados decrescentemente pelo ID
		 * @return array Podcasts encontrados
		 */
		public function listar() {
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID ORDER BY P.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Lista os N podcasts do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de podcasts a ser listado
		 * @return array|false Podcasts encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID ORDER BY P.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Lista os N podcasts ativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de podcasts a ser listado
		 * @return array|false Podcasts encontrados
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.STATUS=1 ORDER BY P.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Lista os N podcasts inativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de podcasts a ser listado
		 * @return array|false Podcasts encontrados
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.STATUS=0 ORDER BY P.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Lista os podcasts do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro podcast
		 * @param int $quantidade Limite do último podcast
		 * @return array|false Podcasts encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID ORDER BY P.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Lista os podcasts ativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro podcast
		 * @param int $quantidade Limite do último podcast
		 * @return array|false Podcasts encontrados
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.STATUS=1 ORDER BY P.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Lista os podcasts inativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro podcast
		 * @param int $quantidade Limite do último podcast
		 * @return array|false Podcasts encontrados
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT P.ID, P.TITULO, P.AUDIO, P.DATA, P.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, M.* FROM PODCAST AS P INNER JOIN USUARIO AS U ON P.USUARIO=U.ID INNER JOIN PERMISSAO AS M ON U.PERMISSAO=M.ID WHERE P.STATUS=0 ORDER BY P.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $audio, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$podcasts = array();
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
				$podcast = new Podcast;
				$podcast->setId($id);
				$podcast->setTitulo($titulo);
				$podcast->setAudio($audio);
				$podcast->setUsuario($usuario);
				$podcast->setData($data);
				$podcast->setStatus((bool) $status);
				array_push($podcasts, $podcast);
			}
			mysqli_stmt_close($stmt);
			return $podcasts;
		}

		/**
		 * Retorna o número de podcasts cadastrados no banco de dados
		 * @return int Podcasts cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM PODCAST";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de podcasts ativos no banco de dados
		 * @return int Podcasts cadastrados ativos
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM PODCAST WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de podcasts inativos no banco de dados
		 * @return int Podcasts cadastrados inativos
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM PODCAST WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>