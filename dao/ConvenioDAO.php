<?php
	include_once("../modelos/Convenio.php");
	include_once("../modelos/Database.php");
	/**
	 * Classe DAO Convênio com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class ConvenioDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela CONVENIO
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
		 * Procura um convênio no banco de dados pelo ID
		 * @param int $id ID do convênio a ser buscado
		 * @return Convenio|false Objeto convênio (ID, título, descrição, cidade, telefone, celular, site, e-mail, imagem, arquivo, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT C.ID, C.TITULO, C.DESCRICAO, C.CIDADE, C.TELEFONE, C.CELULAR, C.SITE, C.EMAIL, C.IMAGEM, C.ARQUIVO, C.DATA, C.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $data, $status, $id_usuario, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$usuario->setEmail($email_usuario);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status_usuario);
			$convenio = new Convenio();
			$convenio->setId($id);
			$convenio->setTitulo($titulo);
			$convenio->setDescricao($descricao);
			$cidade && $convenio->setCidade($cidade);
			$telefone && $convenio->setTelefone($telefone);
			$celular && $convenio->setCelular($celular);
			$site && $convenio->setSite($site);
			$email && $convenio->setEmail($email);
			$convenio->setImagem($imagem);
			$arquivo && $convenio->setArquivo($arquivo);
			$convenio->setUsuario($usuario);
			$convenio->setData($data);
			$convenio->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $convenio;
		}

		/**
		 * Procura um convênio no banco de dados pelo nome do arquivo
		 * @param string $arquivo Arquivo ou parte do arquivo a ser buscado
		 * @return array Convênios encontrados
		 */
		public function procurarArquivo(string $arquivo) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.ARQUIVO LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $arquivo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pelo número do telefone celular
		 * @param string $celular Celular ou parte do celular a ser buscado
		 * @return array Convênios encontrados
		 */
		public function procurarCelular(string $celular) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.CELULAR LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $celular);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pela cidade
		 * @param string $cidade Nome ou parte do nome da cidade a ser buscada
		 * @return array Convênios encontrados
		 */
		public function procurarCidade(string $cidade) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.CIDADE LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $cidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Convênios encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pela descrição
		 * @param string $descricao Descrição ou parte da descrição a ser buscada
		 * @return array Convênios encontrados
		 */
		public function procurarDescricao(string $descricao) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.DESCRICAO LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $descricao);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pelo e-mail
		 * @param string $email E-mail ou parte do e-mail a ser buscado
		 * @return array Convênios encontrados
		 */
		public function procurarEmail(string $email) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.EMAIL LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $email);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pelo nome da imagem
		 * @param string $imagem Imagem ou parte da imagem a ser buscada
		 * @return array Convênios encontrados
		 */
		public function procurarImagem(string $imagem) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.IMAGEM LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $imagem);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pelo domínio web
		 * @param string $site Website ou parte do website a ser buscado
		 * @return array Convênios encontrados
		 */
		public function procurarSite(string $site) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.SITE LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $site);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pelo número de telefone
		 * @param string $telefone Telefone ou parte do telefone a ser buscado
		 * @return array Convênios encontrados
		 */
		public function procurarTelefone(string $telefone) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.TELEFONE LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $telefone);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura um convênio no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Convênios encontrados
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.TITULO LIKE CONCAT('%',?,'%') ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Procura o último convênio cadastrado no banco de dados
		 * @return Convenio|false Objeto convênio (ID, título, descrição, cidade, telefone, celular, site, e-mail, imagem, arquivo, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
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
			$usuario->setEmail($email_usuario);
			$usuario->setLogin($login);
			$usuario->setSenha($senha);
			$usuario->setPermissao($permissao);
			$usuario->setStatus((bool) $status_usuario);
			$convenio = new Convenio();
			$convenio->setId($id);
			$convenio->setTitulo($titulo);
			$convenio->setDescricao($descricao);
			$cidade && $convenio->setCidade($cidade);
			$telefone && $convenio->setTelefone($telefone);
			$celular && $convenio->setCelular($celular);
			$site && $convenio->setSite($site);
			$email && $convenio->setEmail($email);
			$convenio->setImagem($imagem);
			$arquivo && $convenio->setArquivo($arquivo);
			$convenio->setUsuario($usuario);
			$convenio->setData($data);
			$convenio->setStatus((bool) $status);
			mysqli_stmt_close($stmt);
			return $convenio;
		}

		/**
		 * Insere um novo convênio no banco de dados
		 * @param Convenio $convenio Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Convenio $convenio) {
			$query = "INSERT INTO CONVENIO (TITULO, DESCRICAO, CIDADE, TELEFONE, CELULAR, SITE, EMAIL, IMAGEM, ARQUIVO, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $convenio->getTitulo();
			$descricao = $convenio->getDescricao();
			$cidade = $convenio->getCidade();
			$telefone = $convenio->getTelefone();
			$celular = $convenio->getCelular();
			$site = $convenio->getSite();
			$email = $convenio->getEmail();
			$arquivo = $convenio->getArquivo();
			$imagem = $convenio->getImagem();
			$usuario = $convenio->getUsuario()->getId();
			$data = $convenio->getData()->format("Y-m-d H:i:s");
			$status = $convenio->isStatus();
			mysqli_stmt_bind_param($stmt, "sssssssssisi", $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um convênio no banco de dados
		 * @param Convenio $convenio Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Convenio $convenio) {
			$query = "UPDATE CONVENIO SET TITULO=?, DESCRICAO=?, CIDADE=?, TELEFONE=?, CELULAR=?, SITE=?, EMAIL=?, IMAGEM=?, ARQUIVO=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $convenio->getTitulo();
			$descricao = $convenio->getDescricao();
			$cidade = $convenio->getCidade();
			$telefone = $convenio->getTelefone();
			$celular = $convenio->getCelular();
			$site = $convenio->getSite();
			$email = $convenio->getEmail();
			$arquivo = $convenio->getArquivo();
			$imagem = $convenio->getImagem();
			$usuario = $convenio->getUsuario()->getId();
			$data = $convenio->getData()->format("Y-m-d H:i:s");
			$status = $convenio->isStatus();
			$id = $convenio->getId();
			mysqli_stmt_bind_param($stmt, "sssssssssisii", $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um convênio do banco de dados
		 * @param Convenio $convenio Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Convenio $convenio) {
			$query = "DELETE FROM CONVENIO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $convenio->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa um convênio no banco de dados
		 * @param Convenio $convenio Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Convenio $convenio) {
			$query = "UPDATE CONVENIO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $convenio->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa um convênio no banco de dados
		 * @param Convenio $convenio Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Convenio $convenio) {
			$query = "UPDATE CONVENIO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $convenio->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os convênios do banco de dados ordenados decrescentemente pelo ID
		 * @return array Convênios encontrados
		 */
		public function listar() {
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Lista os N convênios do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de convênios a ser listado
		 * @return array|false Convênios encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Lista os N convênios ativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de convênios a ser listado
		 * @return array|false Convênios encontrados
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=1 ORDER BY C.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Lista os N convênios inativos do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de convênios a ser listado
		 * @return array|false Convênios encontrados
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=0 ORDER BY C.ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Lista os convênios do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro convênio
		 * @param int $quantidade Limite do último convênio
		 * @return array|false Convênios encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY C.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Lista os convênios ativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro convênio
		 * @param int $quantidade Limite do último convênio
		 * @return array|false Convênios encontrados
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=1 ORDER BY C.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Lista os convênios inativos do banco de dados ordenados decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro convênio
		 * @param int $quantidade Limite do último convênio
		 * @return array|false Convênios encontrados
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT C.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM CONVENIO AS C INNER JOIN USUARIO AS U ON C.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID WHERE C.STATUS=0 ORDER BY C.ID DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $cidade, $telefone, $celular, $site, $email, $imagem, $arquivo, $id_usuario, $data, $status, $nome, $email_usuario, $login, $senha, $status_usuario, $id_permissao, $banner, $boletim, $convencao, $convenio, $diretorio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$convenios = array();
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
				$usuario->setEmail($email_usuario);
				$usuario->setLogin($login);
				$usuario->setSenha($senha);
				$usuario->setPermissao($permissao);
				$usuario->setStatus((bool) $status_usuario);
				$convenio = new Convenio();
				$convenio->setId($id);
				$convenio->setTitulo($titulo);
				$convenio->setDescricao($descricao);
				$cidade && $convenio->setCidade($cidade);
				$telefone && $convenio->setTelefone($telefone);
				$celular && $convenio->setCelular($celular);
				$site && $convenio->setSite($site);
				$email && $convenio->setEmail($email);
				$convenio->setImagem($imagem);
				$arquivo && $convenio->setArquivo($arquivo);
				$convenio->setUsuario($usuario);
				$convenio->setData($data);
				$convenio->setStatus((bool) $status);
				array_push($convenios, $convenio);
			}
			mysqli_stmt_close($stmt);
			return $convenios;
		}

		/**
		 * Retorna o número de convênios cadastrados no banco de dados
		 * @return int Convênios cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM CONVENIO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de convênios ativos no banco de dados
		 * @return int Convênios cadastrados ativos
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM CONVENIO WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de convênios inativos no banco de dados
		 * @return int Convênios cadastrados inativos
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM CONVENIO WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>