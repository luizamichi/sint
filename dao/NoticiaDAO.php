<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Noticia.php");
	/**
	 * Classe DAO Notícia com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class NoticiaDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela NOTICIA
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
		 * Procura uma notícia no banco de dados pelo ID
		 * @param int $id ID da notícia a ser buscada
		 * @return false|Noticia Objeto notícia (ID, título, subtítulo, texto, imagem, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT N.ID, N.TITULO, N.SUBTITULO, N.TEXTO, N.IMAGEM, N.DATA, N.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$noticia = new Noticia();
			$noticia->setId($id);
			$noticia->setTitulo($titulo);
			$subtitulo && $noticia->setSubtitulo($subtitulo);
			$noticia->setTexto($texto);
			$imagem && $noticia->setImagem($imagem);
			$noticia->setUsuario($usuario);
			$noticia->setData($data);
			$noticia->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $noticia;
		}

		/**
		 * Procura uma notícia no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Notícias encontradas
		 */
		public function procurarData(string $data) {
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Procura uma notícia no banco de dados pelo nome da imagem
		 * @param string $imagem Imagem ou parte da imagem a ser buscada
		 * @return array Notícias encontradas
		 */
		public function procurarImagem(string $imagem) {
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.IMAGEM LIKE CONCAT('%',?,'%') ORDER BY N.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $imagem);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Procura uma notícia no banco de dados pelo subtítulo
		 * @param string $subtitulo Subtítulo ou parte do subtítulo a ser buscado
		 * @return array Notícias encontradas
		 */
		public function procurarSubtitulo(string $subtitulo) {
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.SUBTITULO LIKE CONCAT('%',?,'%') ORDER BY N.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $subtitulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Procura uma notícia no banco de dados pelo texto
		 * @param string $texto Texto ou parte do texto a ser buscado
		 * @return array Notícias encontradas
		 */
		public function procurarTexto(string $texto) {
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.TEXTO LIKE CONCAT('%',?,'%') ORDER BY N.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $texto);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Procura uma notícia no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Notícias encontradas
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.TITULO LIKE CONCAT('%',?,'%') ORDER BY N.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Procura a última notícia cadastrada no banco de dados
		 * @return false|Noticia Objeto notícia (ID, título, subtítulo, texto, imagem, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY N.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
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
			$usuario->setId($id_usuario);
			$usuario->setNome($nome);
			$usuario->setEmail($email);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status_usuario);
			$noticia = new Noticia();
			$noticia->setId($id);
			$noticia->setTitulo($titulo);
			$subtitulo && $noticia->setSubtitulo($subtitulo);
			$noticia->setTexto($texto);
			$imagem && $noticia->setImagem($imagem);
			$noticia->setUsuario($usuario);
			$noticia->setData($data);
			$noticia->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $noticia;
		}

		/**
		 * Insere uma nova notícia no banco de dados
		 * @param Noticia $noticia Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Noticia $noticia) {
			$query = "INSERT INTO NOTICIA (TITULO, SUBTITULO, TEXTO, IMAGEM, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $noticia->getTitulo();
			$subtitulo = $noticia->getSubtitulo();
			$texto = $noticia->getTexto();
			$imagem = $noticia->getImagem();
			$usuario = $noticia->getUsuario()->getId();
			$data = $noticia->getData()->format("Y-m-d H:i:s");
			$status = $noticia->isStatus();
			mysqli_stmt_bind_param($stmt, "ssssisi", $titulo, $subtitulo, $texto, $imagem, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera uma notícia no banco de dados
		 * @param Noticia $noticia Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Noticia $noticia) {
			$query = "UPDATE NOTICIA SET TITULO=?, SUBTITULO=?, TEXTO=?, IMAGEM=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $noticia->getTitulo();
			$subtitulo = $noticia->getSubtitulo();
			$texto = $noticia->getTexto();
			$imagem = $noticia->getImagem();
			$usuario = $noticia->getUsuario()->getId();
			$data = $noticia->getData()->format("Y-m-d H:i:s");
			$status = $noticia->isStatus();
			$id = $noticia->getId();
			mysqli_stmt_bind_param($stmt, "ssssisii", $titulo, $subtitulo, $texto, $imagem, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove uma notícia do banco de dados
		 * @param Noticia $noticia Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Noticia $noticia) {
			$query = "DELETE FROM NOTICIA WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $noticia->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa uma notícia no banco de dados
		 * @param Noticia $noticia Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Noticia $noticia) {
			$query = "UPDATE NOTICIA SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $noticia->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa uma notícia no banco de dados
		 * @param Noticia $noticia Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Noticia $noticia) {
			$query = "UPDATE NOTICIA SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $noticia->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todas as notícias do banco de dados ordenadas decrescentemente pela data
		 * @return array Notícias encontradas
		 */
		public function listar() {
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY N.DATA DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Lista as N notícias do banco de dados ordenadas decrescentemente pela data
		 * @param int $numero Número de notícias a ser listado
		 * @return array|false Notícias encontradas
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY N.DATA DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Lista as N notícias ativas do banco de dados ordenadas decrescentemente pela data
		 * @param int $numero Número de notícias a ser listado
		 * @return array|false Notícias encontradas
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.STATUS=1 ORDER BY N.DATA DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Lista as N notícias inativas do banco de dados ordenadas decrescentemente pelo ID
		 * @param int $numero Número de notícias a ser listado
		 * @return array|false Notícias encontradas
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.STATUS=0 ORDER BY N.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Lista as notícias do banco de dados ordenadas decrescentemente pela data em um dado intervalo
		 * @param int $inicio Número da primeira notícia
		 * @param int $quantidade Limite da última notícia
		 * @return array|false Notícias encontradas
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY N.DATA DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Lista as notícias ativas do banco de dados ordenadas decrescentemente pela data em um dado intervalo
		 * @param int $inicio Número da primeira notícia
		 * @param int $quantidade Limite da última notícia
		 * @return array|false Notícias encontradas
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.STATUS=1 ORDER BY N.DATA DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Lista as notícias inativas do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número da primeira notícia
		 * @param int $quantidade Limite da última notícia
		 * @return array|false Notícias encontradas
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT N.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM NOTICIA AS N INNER JOIN USUARIO AS U ON N.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE N.STATUS=0 ORDER BY N.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $subtitulo, $texto, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$noticias = array();
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
				$noticia = new Noticia();
				$noticia->setId($id);
				$noticia->setTitulo($titulo);
				$subtitulo && $noticia->setSubtitulo($subtitulo);
				$noticia->setTexto($texto);
				$imagem && $noticia->setImagem($imagem);
				$noticia->setUsuario($usuario);
				$noticia->setData($data);
				$noticia->setStatus((bool) $status);
				array_push($noticias, $noticia);
			}
			mysqli_stmt_close($stmt);
			return $noticias;
		}

		/**
		 * Retorna o número de notícias cadastradas no banco de dados
		 * @return int Notícias cadastradas
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM NOTICIA";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de notícias ativas no banco de dados
		 * @return int Notícias cadastradas ativas
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM NOTICIA WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de notícias inativas no banco de dados
		 * @return int Notícias cadastradas inativas
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM NOTICIA WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>