<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Edital.php");
	/**
	 * Classe DAO Edital com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class EditalDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela EDITAL
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
		 * Procura um edital no banco de dados pelo ID
		 * @param int $id ID do edital a ser buscado
		 * @return Edital|false Objeto edital (ID, título, descrição, imagem, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT E.ID, E.TITULO, E.DESCRICAO, E.IMAGEM, E.DATA, E.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$edital = new Edital();
			$edital->setId($id);
			$edital->setTitulo($titulo);
			$descricao && $edital->setDescricao($descricao);
			$edital->setImagem($imagem);
			$edital->setUsuario($usuario);
			$edital->setData($data);
			$edital->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $edital;
		}

		/**
		 * Procura um edital no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Editais encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Procura um edital no banco de dados pela descrição
		 * @param string $descricao Descrição ou parte da descrição a ser buscada
		 * @return array Editais encontrados
		 */
		public function procurarDescricao(string $descricao) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.DESCRICAO LIKE CONCAT('%',?,'%') ORDER BY E.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $descricao);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Procura um edital no banco de dados pelo nome da imagem
		 * @param string $imagem Imagem ou parte da imagem a ser buscada
		 * @return array Editais encontrados
		 */
		public function procurarImagem(string $imagem) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.IMAGEM LIKE CONCAT('%',?,'%') ORDER BY E.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $imagem);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Procura um edital no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Editais encontrados
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.TITULO LIKE CONCAT('%',?,'%') ORDER BY E.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Procura o último edital cadastrado no banco de dados
		 * @return Edital|false Objeto edital (ID, título, descrição, imagem, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY E.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$edital = new Edital();
			$edital->setId($id);
			$edital->setTitulo($titulo);
			$descricao && $edital->setDescricao($descricao);
			$edital->setImagem($imagem);
			$edital->setUsuario($usuario);
			$edital->setData($data);
			$edital->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $edital;
		}

		/**
		 * Insere um novo edital no banco de dados
		 * @param Edital $edital Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Edital $edital) {
			$query = "INSERT INTO EDITAL (TITULO, DESCRICAO, IMAGEM, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $edital->getTitulo();
			$descricao = $edital->getDescricao();
			$imagem = $edital->getImagem();
			$usuario = $edital->getUsuario()->getId();
			$data = $edital->getData()->format("Y-m-d H:i:s");
			$status = $edital->isStatus();
			mysqli_stmt_bind_param($stmt, "sssisi", $titulo, $descricao, $imagem, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um edital no banco de dados
		 * @param Edital $edital Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Edital $edital) {
			$query = "UPDATE EDITAL SET TITULO=?, DESCRICAO=?, IMAGEM=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $edital->getTitulo();
			$descricao = $edital->getDescricao();
			$imagem = $edital->getImagem();
			$usuario = $edital->getUsuario()->getId();
			$data = $edital->getData()->format("Y-m-d H:i:s");
			$status = $edital->isStatus();
			$id = $edital->getId();
			mysqli_stmt_bind_param($stmt, "sssisii", $titulo, $descricao, $imagem, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um edital do banco de dados
		 * @param Edital $edital Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Edital $edital) {
			$query = "DELETE FROM EDITAL WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $edital->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa um edital no banco de dados
		 * @param Edital $edital Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Edital $edital) {
			$query = "UPDATE EDITAL SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $edital->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa um edital no banco de dados
		 * @param Edital $edital Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Edital $edital) {
			$query = "UPDATE EDITAL SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $edital->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os editais do banco de dados ordenados decrescentemente pela data
		 * @return array Editais encontrados
		 */
		public function listar() {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY E.DATA DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Lista os N editais do banco de dados ordenados decrescentemente pela data
		 * @param int $numero Número de editais a ser listado
		 * @return array|false Editais encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY E.DATA DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Lista os N editais ativos do banco de dados ordenados decrescentemente pela data
		 * @param int $numero Número de editais a ser listado
		 * @return array|false Editais encontrados
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.STATUS=1 ORDER BY E.DATA DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Lista os N editais inativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de editais a ser listado
		 * @return array|false Editais encontrados
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.STATUS=0 ORDER BY E.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Lista os editais do banco de dados ordenados decrescentemente pela data em um dado intervalo
		 * @param int $inicio Número do primeiro edital
		 * @param int $quantidade Limite do último edital
		 * @return array|false Editais encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY E.DATA DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Lista os editais ativos do banco de dados ordenados decrescentemente pela data em um dado intervalo
		 * @param int $inicio Número do primeiro edital
		 * @param int $quantidade Limite do último edital
		 * @return array|false Editais encontrados
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.STATUS=1 ORDER BY E.DATA DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Lista os editais inativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro edital
		 * @param int $quantidade Limite do último edital
		 * @return array|false Editais encontrados
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EDITAL AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE E.STATUS=0 ORDER BY E.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $imagem, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$editais = array();
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
				$edital = new Edital();
				$edital->setId($id);
				$edital->setTitulo($titulo);
				$descricao && $edital->setDescricao($descricao);
				$edital->setImagem($imagem);
				$edital->setUsuario($usuario);
				$edital->setData($data);
				$edital->setStatus((bool) $status);
				array_push($editais, $edital);
			}
			mysqli_stmt_close($stmt);
			return $editais;
		}

		/**
		 * Retorna o número de editais cadastrados no banco de dados
		 * @return int Editais cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM EDITAL";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de editais ativos no banco de dados
		 * @return int Editais cadastrados ativos
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM EDITAL WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de editais inativos no banco de dados
		 * @return int Editais cadastrados inativos
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM EDITAL WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>