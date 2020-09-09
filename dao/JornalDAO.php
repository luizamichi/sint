<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Jornal.php");
	/**
	 * Classe DAO Jornal com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class JornalDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela JORNAL
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
		 * Procura um jornal no banco de dados pelo ID
		 * @param int $id ID do jornal a ser buscado
		 * @return false|Jornal Objeto jornal (ID, título, edição, arquivo PDF, imagem, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT J.ID, J.TITULO, J.EDICAO, J.ARQUIVO, J.IMAGEM, J.DATA, J.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$jornal = new Jornal();
			$jornal->setId($id);
			$jornal->setTitulo($titulo);
			$jornal->setEdicao($edicao);
			$jornal->setArquivo($arquivo);
			$imagem && $jornal->setImagem($imagem);
			$jornal->setUsuario($usuario);
			$jornal->setData($data);
			$jornal->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $jornal;
		}

		/**
		 * Procura um jornal no banco de dados pelo nome do arquivo
		 * @param string $arquivo Arquivo ou parte do arquivo a ser buscado
		 * @return array Jornais encontrados
		 */
		public function procurarArquivo(string $arquivo) {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.ARQUIVO LIKE CONCAT('%',?,'%') ORDER BY J.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $arquivo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Procura um jornal no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Jornais encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Procura um jornal no banco de dados pela edição
		 * @param int $edicao Número da edição do periódico
		 * @return false|Jornal Jornais encontrados
		 */
		public function procurarEdicao(int $edicao) {
			$query = "SELECT J.ID, J.TITULO, J.EDICAO, J.ARQUIVO, J.IMAGEM, J.DATA, J.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.EDICAO=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $edicao);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$jornal = new Jornal();
			$jornal->setId($id);
			$jornal->setTitulo($titulo);
			$jornal->setEdicao($edicao);
			$jornal->setArquivo($arquivo);
			$imagem && $jornal->setImagem($imagem);
			$jornal->setUsuario($usuario);
			$jornal->setData($data);
			$jornal->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $jornal;
		}

		/**
		 * Procura um jornal no banco de dados pelo nome da imagem
		 * @param string $imagem Imagem ou parte da imagem a ser buscada
		 * @return array Jornais encontrados
		 */
		public function procurarImagem(string $imagem) {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.IMAGEM LIKE CONCAT('%',?,'%') ORDER BY J.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $imagem);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Procura um jornal no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Jornais encontrados
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.TITULO LIKE CONCAT('%',?,'%') ORDER BY J.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Procura o último jornal cadastrado no banco de dados
		 * @return false|Jornal Objeto jornal (ID, título, edição, arquivo PDF, imagem, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$jornal = new Jornal();
			$jornal->setId($id);
			$jornal->setTitulo($titulo);
			$jornal->setEdicao($edicao);
			$jornal->setArquivo($arquivo);
			$imagem && $jornal->setImagem($imagem);
			$jornal->setUsuario($usuario);
			$jornal->setData($data);
			$jornal->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $jornal;
		}

		/**
		 * Insere um novo jornal no banco de dados
		 * @param Jornal $jornal Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Jornal $jornal) {
			$query = "INSERT INTO JORNAL (TITULO, EDICAO, ARQUIVO, IMAGEM, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $jornal->getTitulo();
			$edicao = $jornal->getEdicao();
			$arquivo = $jornal->getArquivo();
			$imagem = $jornal->getImagem();
			$usuario = $jornal->getUsuario()->getId();
			$data = $jornal->getData()->format("Y-m-d H:i:s");
			$status = $jornal->isStatus();
			mysqli_stmt_bind_param($stmt, "sissisi", $titulo, $edicao, $arquivo, $imagem, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um jornal no banco de dados
		 * @param Jornal $jornal Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Jornal $jornal) {
			$query = "UPDATE JORNAL SET TITULO=?, EDICAO=?, ARQUIVO=?, IMAGEM=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $jornal->getTitulo();
			$edicao = $jornal->getEdicao();
			$arquivo = $jornal->getArquivo();
			$imagem = $jornal->getImagem();
			$usuario = $jornal->getUsuario()->getId();
			$data = $jornal->getData()->format("Y-m-d H:i:s");
			$status = $jornal->isStatus();
			$id = $jornal->getId();
			mysqli_stmt_bind_param($stmt, "sissisii", $titulo, $edicao, $arquivo, $imagem, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um jornal do banco de dados
		 * @param Jornal $jornal Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Jornal $jornal) {
			$query = "DELETE FROM JORNAL WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $jornal->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa um jornal no banco de dados
		 * @param Jornal $jornal Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Jornal $jornal) {
			$query = "UPDATE JORNAL SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $jornal->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa um jornal no banco de dados
		 * @param Jornal $jornal Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Jornal $jornal) {
			$query = "UPDATE JORNAL SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $jornal->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os jornais do banco de dados ordenados decrescentemente pela edição
		 * @return array Jornais encontrados
		 */
		public function listar() {
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.EDICAO DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Lista os N jornais do banco de dados ordenados decrescentemente pela edição
		 * @param int $numero Número de jornais a ser listado
		 * @return array|false Jornais encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.EDICAO DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Lista os N jornais ativos do banco de dados ordenados decrescentemente pela edição
		 * @param int $numero Número de jornais a ser listado
		 * @return array|false Jornais encontrados
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=1 ORDER BY J.EDICAO DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Lista os N jornais inativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de jornais a ser listado
		 * @return array|false Jornais encontrados
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=0 ORDER BY J.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Lista os jornais do banco de dados ordenados decrescentemente pela edição em um dado intervalo
		 * @param int $inicio Número do primeiro jornal
		 * @param int $quantidade Limite do último jornal
		 * @return array|false Jornais encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY J.EDICAO DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Lista os jornais ativos do banco de dados ordenados decrescentemente pela edição em um dado intervalo
		 * @param int $inicio Número do primeiro jornal
		 * @param int $quantidade Limite do último jornal
		 * @return array|false Jornais encontrados
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=1 ORDER BY J.EDICAO DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Lista os jornais inativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro jornal
		 * @param int $quantidade Limite do último jornal
		 * @return array|false Jornais encontrados
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT J.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM JORNAL AS J INNER JOIN USUARIO AS U ON J.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE J.STATUS=0 ORDER BY J.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $edicao, $arquivo, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$jornais = array();
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
				$jornal = new Jornal();
				$jornal->setId($id);
				$jornal->setTitulo($titulo);
				$jornal->setEdicao($edicao);
				$jornal->setArquivo($arquivo);
				$imagem && $jornal->setImagem($imagem);
				$jornal->setUsuario($usuario);
				$jornal->setData($data);
				$jornal->setStatus((bool) $status);
				array_push($jornais, $jornal);
			}
			mysqli_stmt_close($stmt);
			return $jornais;
		}

		/**
		 * Retorna o número de jornais cadastrados no banco de dados
		 * @return int Jornais cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM JORNAL";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de jornais ativos no banco de dados
		 * @return int Jornais cadastrados ativos
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM JORNAL WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de jornais inativos no banco de dados
		 * @return int Jornais cadastrados inativos
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM JORNAL WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>