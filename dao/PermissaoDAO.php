<?php
	include_once("../modelos/Database.php");
	include_once("../modelos/Permissao.php");
	/**
	 * Classe DAO Permissão com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class PermissaoDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela PERMISSAO
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
		 * Procura uma permissão no banco de dados pelo ID
		 * @param int $id ID da permissão a ser buscada
		 * @return false|Permissao Objeto permissão (ID, CRUD(banner), CRUD(boletim), CRUD(convenção), CRUD(convênio), LEITURA(diretório), CRUD(edital), CRUD(evento), CRUD(finança), CRUD(jornal), CRUD(jurídico), CRUD(notícia), CRUD(podcast), LEITURA(registro), LEITURA(tabela) e CRUD(usuário))
		 */
		public function procurarId(int $id) {
			$query = "SELECT BANNER, BOLETIM, CONVENCAO, CONVENIO, DIRETORIO, EDITAL, EVENTO, FINANCA, JORNAL, JURIDICO, NOTICIA, REGISTRO, TABELA, USUARIO FROM PERMISSAO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id);
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
			mysqli_stmt_close($stmt);
			return $permissao;
		}

		/**
		 * Procura uma permissão pelo CRUD(banner)
		 * @return array Permissões encontradas
		 */
		public function procurarBanner() {
			$query = "SELECT * FROM PERMISSAO WHERE BANNER=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(boletim)
		 * @return array Permissões encontradas
		 */
		public function procurarBoletim() {
			$query = "SELECT * FROM PERMISSAO WHERE BOLETIM=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(convenção)
		 * @return array Permissões encontradas
		 */
		public function procurarConvencao() {
			$query = "SELECT * FROM PERMISSAO WHERE CONVENCAO=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(convênio)
		 * @return array Permissões encontradas
		 */
		public function procurarConvenio() {
			$query = "SELECT * FROM PERMISSAO WHERE CONVENIO=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(diretório)
		 * @return array Permissões encontradas
		 */
		public function procurarDiretorio() {
			$query = "SELECT * FROM PERMISSAO WHERE DIRETORIO=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(edital)
		 * @return array Permissões encontradas
		 */
		public function procurarEdital() {
			$query = "SELECT * FROM PERMISSAO WHERE EDITAL=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(evento)
		 * @return array Permissões encontradas
		 */
		public function procurarEvento() {
			$query = "SELECT * FROM PERMISSAO WHERE EVENTO=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(finança)
		 * @return array Permissões encontradas
		 */
		public function procurarFinanca() {
			$query = "SELECT * FROM PERMISSAO WHERE FINANCA=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(jornal)
		 * @return array Permissões encontradas
		 */
		public function procurarJornal() {
			$query = "SELECT * FROM PERMISSAO WHERE JORNAL=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(jurídico)
		 * @return array Permissões encontradas
		 */
		public function procurarJuridico() {
			$query = "SELECT * FROM PERMISSAO WHERE JURIDICO=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(notícia)
		 * @return array Permissões encontradas
		 */
		public function procurarNoticia() {
			$query = "SELECT * FROM PERMISSAO WHERE NOTICIA=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(podcast)
		 * @return array Permissões encontradas
		 */
		public function procurarPodcast() {
			$query = "SELECT * FROM PERMISSAO WHERE PODCAST=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(registro)
		 * @return array Permissões encontradas
		 */
		public function procurarRegistro() {
			$query = "SELECT * FROM PERMISSAO WHERE REGISTRO=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(tabela)
		 * @return array Permissões encontradas
		 */
		public function procurarTabela() {
			$query = "SELECT * FROM PERMISSAO WHERE TABELA=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura uma permissão pelo CRUD(usuário)
		 * @return array Permissões encontradas
		 */
		public function procurarUsuario() {
			$query = "SELECT * FROM PERMISSAO WHERE USUARIO=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Procura a última permissão cadastrada no banco de dados
		 * @return false|Permissao Objeto permissão (ID, CRUD(banner), CRUD(boletim), CRUD(convenção), CRUD(convênio), LEITURA(diretório), CRUD(edital), CRUD(evento), CRUD(finança), CRUD(jornal), CRUD(jurídico), CRUD(notícia), CRUD(podcast), LEITURA(registro), LEITURA(tabela) e CRUD(usuário))
		 */
		public function procurarUltimo() {
			$query = "SELECT * FROM PERMISSAO ORDER BY ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$permissao = new Permissao();
			$permissao->setId($id);
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
			mysqli_stmt_close($stmt);
			return $permissao;
		}

		/**
		 * Insere uma nova permissão no banco de dados
		 * @param Permissao $permissao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(Permissao $permissao) {
			$query = "INSERT INTO PERMISSAO(BANNER, BOLETIM, CONVENCAO, CONVENIO, EDITAL, EVENTO, FINANCA, JORNAL, JURIDICO, NOTICIA, REGISTRO, USUARIO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$banner = $permissao->isBanner();
			$boletim = $permissao->isBoletim();
			$convencao = $permissao->isConvencao();
			$convenio = $permissao->isConvenio();
			$edital = $permissao->isEdital();
			$evento = $permissao->isEvento();
			$financa = $permissao->isFinanca();
			$jornal = $permissao->isJornal();
			$juridico = $permissao->isJuridico();
			$noticia = $permissao->isNoticia();
			$registro = $permissao->isRegistro();
			$usuario = $permissao->isUsuario();
			mysqli_stmt_bind_param($stmt, "iiiiiiiiiiii", $banner, $boletim, $convencao, $convenio, $evento, $edital, $financa, $jornal, $juridico, $noticia, $registro, $usuario);
			mysqli_stmt_execute($stmt);
			$status = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $status;
		}

		/**
		 * Altera uma permissão no banco de dados
		 * @param Permissao $permissao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(Permissao $permissao) {
			$query = "UPDATE PERMISSAO SET BANNER=?, BOLETIM=?, CONVENCAO=?, CONVENIO=?, EDITAL=?, EVENTO=?, FINANCA=?, JORNAL=?, JURIDICO=?, NOTICIA=?, REGISTRO=?, USUARIO=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$banner = $permissao->isBanner();
			$boletim = $permissao->isBoletim();
			$convencao = $permissao->isConvencao();
			$convenio = $permissao->isConvenio();
			$edital = $permissao->isEdital();
			$evento = $permissao->isEvento();
			$financa = $permissao->isFinanca();
			$jornal = $permissao->isJornal();
			$juridico = $permissao->isJuridico();
			$noticia = $permissao->isNoticia();
			$registro = $permissao->isRegistro();
			$usuario = $permissao->isUsuario();
			$id = $permissao->getId();
			mysqli_stmt_bind_param($stmt, "iiiiiiiiiiiii", $banner, $boletim, $convencao, $convenio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $registro, $usuario, $id);
			mysqli_stmt_execute($stmt);
			$status = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $status;
		}

		/**
		 * Remove uma permissão no banco de dados
		 * @param Permissao $permissao Objeto instanciado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(Permissao $permissao) {
			$query = "DELETE FROM PERMISSAO WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $permissao->getId();
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$status = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $status;
		}

		/**
		 * Lista todas as permissões do banco de dados ordenados decrescentemente pelo ID
		 * @return array Permissões encontradas
		 */
		public function listar() {
			$query = "SELECT * FROM PERMISSAO ORDER BY ID DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Lista as N permissões do banco de dados ordenados decrescentemente pelo ID
		 * @param int $numero Número de permissões a ser listado
		 * @return array|false Permissões encontradas
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT * FROM PERMISSAO ORDER BY ID DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $banner, $boletim, $convencao, $convenio, $diretorio, $edital, $evento, $financa, $jornal, $juridico, $noticia, $podcast, $registro, $tabela, $usuario);
			$permissoes = array();
			while(mysqli_stmt_fetch($stmt)) {
				$permissao = new Permissao();
				$permissao->setId($id);
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
				array_push($permissoes, $permissao);
			}
			mysqli_stmt_close($stmt);
			return $permissoes;
		}

		/**
		 * Retorna o número de permissões no banco de dados
		 * @return int Permissões cadastradas
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM PERMISSAO";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>