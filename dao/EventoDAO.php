<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Evento.php");
	/**
	 * Classe DAO Evento com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class EventoDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela EVENTO
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
		 * Procura um evento no banco de dados pelo ID
		 * @param int $id ID do evento a ser buscado
		 * @return Evento|false Objeto evento (ID, título, descrição, diretório, imagens, usuário, data e status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT E.ID, E.TITULO, E.DESCRICAO, E.DIRETORIO, I.*, E.DATA, E.STATUS, U.ID, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $imagem_id, $imagem, $evento_imagem, $status_imagem, $data, $status, $id_usuario, $nome, $email, $login, $senha, $status_usuario, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
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
			$evento = new Evento();
			$evento->setId($id);
			$evento->setTitulo($titulo);
			$descricao && $evento->setDescricao($descricao);
			$evento->setDiretorio($diretorio);
			$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
			$evento->setUsuario($usuario);
			$evento->setData($data);
			$evento->setStatus((bool) $status);
			while(mysqli_stmt_fetch($stmt)) {
				array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
			}
			$evento->setImagens($imagens);
			mysqli_stmt_close($stmt);
			return $evento;
		}

		/**
		 * Procura um evento no banco de dados pela data
		 * @param string $data Data ou parte da data a ser buscada
		 * @return array Eventos encontrados
		 */
		public function procurarData(string $data) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.DATA LIKE CONCAT(?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $data);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Procura um evento no banco de dados pela descrição
		 * @param string $descricao Descrição ou parte da descrição a ser buscada
		 * @return array Eventos encontrados
		 */
		public function procurarDescricao(string $descricao) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.DESCRICAO LIKE CONCAT('%',?,'%') ORDER BY E.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $descricao);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Procura um evento no banco de dados pelo nome do diretório
		 * @param string $diretorio Diretório ou parte do diretório a ser buscado
		 * @return array Eventos encontrados
		 */
		public function procurarDiretorio(string $diretorio) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.DIRETORIO LIKE CONCAT('%',?,'%') ORDER BY E.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $diretorio);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Procura um evento no banco de dados pelo título
		 * @param string $titulo Título ou parte do título a ser buscado
		 * @return array Eventos encontrados
		 */
		public function procurarTitulo(string $titulo) {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.TITULO LIKE CONCAT('%',?,'%') ORDER BY E.ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $titulo);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Procura o último evento cadastrado no banco de dados
		 * @return Evento|false Objeto evento (ID, título, descrição, diretório, imagens, usuário, data e status)
		 */
		public function procurarUltimo() {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID LEFT JOIN IMAGEM AS I ON E.ID=I.EVENTO INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY E.ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			if(!mysqli_stmt_fetch($stmt)) {
				$stmt = mysqli_prepare($this->database->getConexao(), $query);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
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
			$evento = new Evento();
			$evento->setId($id);
			$evento->setTitulo($titulo);
			$descricao && $evento->setDescricao($descricao);
			$evento->setDiretorio($diretorio);
			$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
			$evento->setUsuario($usuario);
			$evento->setData($data);
			$evento->setStatus((bool) $status);
			while(mysqli_stmt_fetch($stmt)) {
				array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
			}
			$evento->setImagens($imagens);
			mysqli_stmt_close($stmt);
			return $evento;
		}

		/**
		 * Insere um novo evento no banco de dados
		 * @param Evento $evento Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Evento $evento) {
			$query = "INSERT INTO EVENTO (TITULO, DESCRICAO, DIRETORIO, USUARIO, DATA, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $evento->getTitulo();
			$descricao = $evento->getDescricao();
			$diretorio = $evento->getDiretorio();
			$usuario = $evento->getUsuario()->getId();
			$data = $evento->getData()->format("Y-m-d H:i:s");
			$status = $evento->isStatus();
			mysqli_stmt_bind_param($stmt, "sssisi", $titulo, $descricao, $diretorio, $usuario, $data, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera um evento no banco de dados
		 * @param Evento $evento Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Evento $evento) {
			$query = "UPDATE EVENTO SET TITULO=?, DESCRICAO=?, DIRETORIO=?, USUARIO=?, DATA=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$titulo = $evento->getTitulo();
			$descricao = $evento->getDescricao();
			$diretorio = $evento->getDiretorio();
			$usuario = $evento->getUsuario()->getId();
			$data = $evento->getData()->format("Y-m-d H:i:s");
			$status = $evento->isStatus();
			$id = $evento->getId();
			mysqli_stmt_bind_param($stmt, "sssisii", $titulo, $descricao, $diretorio, $usuario, $data, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove um evento do banco de dados
		 * @param Evento $evento Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Evento $evento) {
			$query = "DELETE FROM EVENTO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $evento->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa um evento no banco de dados
		 * @param Evento $evento Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(Evento $evento) {
			$query = "UPDATE EVENTO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $evento->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa um evento no banco de dados
		 * @param Evento $evento Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(Evento $evento) {
			$query = "UPDATE EVENTO SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $evento->getId();
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos os eventos do banco de dados ordenados decrescentemente pela data
		 * @return array Eventos encontrados
		 */
		public function listar() {
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO ORDER BY E.DATA DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Lista os N eventos do banco de dados ordenados decrescentemente pela data
		 * @param int $numero Número de eventos a ser listado
		 * @return array|false Eventos encontrados
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY E.DATA DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Lista os N eventos ativos do banco de dados ordenados decrescentemente pela data
		 * @param int $numero Número de eventos a ser listado
		 * @return array|false Eventos encontrados
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.STATUS=1 ORDER BY E.DATA DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Lista os N eventos inativos do banco de dados ordenados decrescentemente pela data
		 * @param int $numero Número de eventos a ser listado
		 * @return array|false Eventos encontrados
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.STATUS=0 ORDER BY E.DATA DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Lista os eventos do banco de dados ordenados decrescentemente pela data em um dado intervalo
		 * @param int $inicio Número do primeiro evento
		 * @param int $quantidade Limite do último evento
		 * @return array|false Eventos encontrados
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID ORDER BY E.DATA DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Lista os eventos ativos do banco de dados ordenados decrescentemente pela data em um dado intervalo
		 * @param int $inicio Número do primeiro evento
		 * @param int $quantidade Limite do último evento
		 * @return array|false Eventos encontrados
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.STATUS=1 ORDER BY E.DATA";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			$ids = array();
			$query = "SELECT E.ID FROM EVENTO AS E WHERE E.STATUS=1 ORDER BY E.DATA DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id);
			while(mysqli_stmt_fetch($stmt)) {
				array_push($ids, $id);
			}
			foreach($eventos as $indice => $evento) {
				if(!in_array($evento->getId(), $ids)) {
					unset($eventos[$indice]);
				}
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Lista os eventos inativos do banco de dados ordenados decrescentemente pela data em um dado intervalo
		 * @param int $inicio Número do primeiro evento
		 * @param int $quantidade Limite do último evento
		 * @return array|false Eventos encontrados
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT E.*, U.NOME, U.EMAIL, U.LOGIN, U.SENHA, U.STATUS, I.*, P.* FROM EVENTO AS E INNER JOIN USUARIO AS U ON E.USUARIO=U.ID INNER JOIN PERMISSAO AS P ON U.PERMISSAO=P.ID INNER JOIN IMAGEM AS I ON E.ID=I.EVENTO WHERE E.STATUS=0 ORDER BY E.DATA DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $titulo, $descricao, $diretorio, $id_usuario, $data, $status, $nome, $email, $login, $senha, $status_usuario, $imagem_id, $imagem, $evento_imagem, $status_imagem, $id_permissao, $banner_permissao, $boletim_permissao, $convencao_permissao, $convenio_permissao, $diretorio_permissao, $evento_permissao, $edital_permissao, $financa_permissao, $jornal_permissao, $juridico_permissao, $noticia_permissao, $podcast_permissao, $registro_permissao, $tabela_permissao, $usuario_permissao);
			$eventos = array();
			$imagens = array();
			$ids = array();
			while(mysqli_stmt_fetch($stmt)) {
				if(!in_array($id, $ids)) {
					if(!empty($eventos)) {
						end($eventos)->setImagens($imagens);
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
					$evento = new Evento();
					$evento->setId($id);
					$evento->setTitulo($titulo);
					$descricao && $evento->setDescricao($descricao);
					$evento->setDiretorio($diretorio);
					$imagens = array(["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
					$evento->setUsuario($usuario);
					$evento->setData($data);
					$evento->setStatus((bool) $status);
					$evento->setImagens($imagens);
					array_push($ids, $id);
					array_push($eventos, $evento);
				}
				else {
					array_push($imagens, ["ID" => $imagem_id, "IMAGEM" => $imagem, "EVENTO" => $evento_imagem, "STATUS" => (bool) $status_imagem]);
				}
			}
			if(!empty($eventos)) {
				end($eventos)->setImagens($imagens);
			}
			mysqli_stmt_close($stmt);
			return $eventos;
		}

		/**
		 * Retorna o número de eventos cadastrados no banco de dados
		 * @return int Eventos cadastrados
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM EVENTO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de eventos ativos no banco de dados
		 * @return int Eventos cadastrados ativos
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM EVENTO WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de eventos inativos no banco de dados
		 * @return int Eventos cadastrados inativos
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM EVENTO WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>