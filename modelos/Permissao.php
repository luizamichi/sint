<?php
	/**
	 * Classe Permissão com atributos simples (ID, CRUD(banner), CRUD(boletim), CRUD(convenção), CRUD(convênio), LEITURA(diretório), CRUD(edital), CRUD(evento), CRUD(finança), CRUD(jornal), CRUD(jurídico), CRUD(notícia), CRUD(podcast), LEITURA(registro), LEITURA(tabela) e CRUD(usuário))
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class Permissao {
		/**
		 * Variáveis privadas
		 * @var int $id Chave primária
		 * @var boolean $banner Booleano se pode realizar CRUD na tabela BANNER
		 * @var boolean $boletim Booleano se pode realizar CRUD na tabela BOLETIM
		 * @var boolean $convencao Booleano se pode realizar CRUD na tabela CONVENCAO
		 * @var boolean $convenio Booleano se pode realizar CRUD na tabela CONVENIO
		 * @var boolean $diretorio Booleano se pode realizar LEITURA no diretório raiz
		 * @var boolean $edital Booleano se pode realizar CRUD na tabela EDITAL
		 * @var boolean $evento Booleano se pode realizar CRUD na tabela EVENTO
		 * @var boolean $financa Booleano se pode realizar CRUD na tabela FINANCA
		 * @var boolean $jornal Booleano se pode realizar CRUD na tabela JORNAL
		 * @var boolean $juridico Booleano se pode realizar CRUD na tabela JURIDICO
		 * @var boolean $noticia Booleano se pode realizar CRUD na tabela NOTICIA
		 * @var boolean $podcast Booleano se pode realizar CRUD na tabela PODCAST
		 * @var boolean $registro Booleano se pode realizar LEITURA na tabela REGISTRO
		 * @var boolean $tabela Booleano se pode realizar LEITURA nas tabelas
		 * @var boolean $usuario Booleano se pode realizar CRUD na tabela USUARIO
		 */
		private $id;
		private $banner;
		private $boletim;
		private $convencao;
		private $convenio;
		private $diretorio;
		private $edital;
		private $evento;
		private $financa;
		private $jornal;
		private $juridico;
		private $noticia;
		private $podcast;
		private $registro;
		private $tabela;
		private $usuario;

		/**
		 * Instancia a classe
		 * @return void
		 */
		public function __construct() {
			$this->id = 0;
			$this->banner = FALSE;
			$this->boletim = FALSE;
			$this->convencao = FALSE;
			$this->convenio = FALSE;
			$this->diretorio = FALSE;
			$this->edital = FALSE;
			$this->evento = FALSE;
			$this->financa = FALSE;
			$this->jornal = FALSE;
			$this->juridico = FALSE;
			$this->noticia = FALSE;
			$this->podcast = FALSE;
			$this->registro = FALSE;
			$this->tabela = FALSE;
			$this->usuario = FALSE;
		}

		/**
		 * Destrói a classe
		 * @return void
		 */
		public function __destruct() {
			$this->id = NULL;
			$this->banner = NULL;
			$this->boletim = NULL;
			$this->convencao = NULL;
			$this->convenio = NULL;
			$this->diretorio = NULL;
			$this->edital = NULL;
			$this->evento = NULL;
			$this->financa = NULL;
			$this->jornal = NULL;
			$this->juridico = NULL;
			$this->noticia = NULL;
			$this->podcast = NULL;
			$this->registro = NULL;
			$this->tabela = NULL;
			$this->usuario = NULL;
		}

		/**
		 * Clona a classe
		 * @return void
		 */
		public function __clone() {
			$this->id = clone $this->id;
			$this->banner = clone $this->banner;
			$this->boletim = clone $this->boletim;
			$this->convencao = clone $this->convencao;
			$this->convenio = clone $this->convenio;
			$this->diretorio = clone $this->diretorio;
			$this->edital = clone $this->edital;
			$this->evento = clone $this->evento;
			$this->financa = clone $this->financa;
			$this->jornal = clone $this->jornal;
			$this->juridico = clone $this->juridico;
			$this->noticia = clone $this->noticia;
			$this->podcast = clone $this->podcast;
			$this->registro = clone $this->registro;
			$this->tabela = clone $this->tabela;
			$this->usuario = clone $this->usuario;
		}

		/**
		 * Retorna a classe em formato de string
		 * @return string Atributos da classe separados por quebra de linha
		 */
		public function __toString() {
			return "ID: " . $this->id .
			"\nBANNER: " . $this->banner .
			"\nBOLETIM: " . $this->boletim .
			"\nCONVENÇÃO: " . $this->convencao .
			"\nCONVÊNIO: " . $this->convenio .
			"\nDIRETÓRIO: " . $this->diretorio .
			"\nEDITAL: " . $this->edital .
			"\nEVENTO: " . $this->evento .
			"\nFINANÇAS: " . $this->financa .
			"\nJORNAL: " . $this->jornal .
			"\nJURÍDICO: " . $this->juridico .
			"\nNOTÍCIA: " . $this->noticia .
			"\nPODCAST: " . $this->podcast .
			"\nREGISTRO: " . $this->registro .
			"\nTABELA: " . $this->tabela .
			"\nUSUÁRIO: " . $this->usuario . "\n";
		}

		/**
		 * Atribui um novo valor ao ID
		 * @param int $id Número inteiro positivo
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function setId(int $id) {
			if($id > 0) {
				$this->id = $id;
				return TRUE;
			}
			else {
				return FALSE;
			}
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela BANNER
		 * @param boolean $banner Booleano referente à permissão
		 * @return void
		 */
		public function setBanner(bool $banner) {
			$this->banner = $banner;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela BOLETIM
		 * @param boolean $boletim Booleano referente à permissão
		 * @return void
		 */
		public function setBoletim(bool $boletim) {
			$this->boletim = $boletim;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela CONVENCAO
		 * @param boolean $convencao Booleano referente à permissão
		 * @return void
		 */
		public function setConvencao(bool $convencao) {
			$this->convencao = $convencao;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela CONVENIO
		 * @param boolean $convenio Booleano referente à permissão
		 * @return void
		 */
		public function setConvenio(bool $convenio) {
			$this->convenio = $convenio;
		}

		/**
		 * Atribui um novo valor à permissão de LEITURA no DIRETORIO
		 * @param boolean $diretorio Booleano referente à permissão
		 * @return void
		 */
		public function setDiretorio(bool $diretorio) {
			$this->diretorio = $diretorio;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela EDITAL
		 * @param boolean $edital Booleano referente à permissão
		 * @return void
		 */
		public function setEdital(bool $edital) {
			$this->edital = $edital;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela EVENTO
		 * @param boolean $evento Booleano referente à permissão
		 * @return void
		 */
		public function setEvento(bool $evento) {
			$this->evento = $evento;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela FINANCA
		 * @param boolean $financa Booleano referente à permissão
		 * @return void
		 */
		public function setFinanca(bool $financa) {
			$this->financa = $financa;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela JORNAL
		 * @param boolean $jornal Booleano referente à permissão
		 * @return void
		 */
		public function setJornal(bool $jornal) {
			$this->jornal = $jornal;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela JURIDICO
		 * @param boolean $juridico Booleano referente à permissão
		 * @return void
		 */
		public function setJuridico(bool $juridico) {
			$this->juridico = $juridico;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela NOTICIA
		 * @param boolean $noticia Booleano referente à permissão
		 * @return void
		 */
		public function setNoticia(bool $noticia) {
			$this->noticia = $noticia;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela PODCAST
		 * @param boolean $podcast Booleano referente à permissão
		 * @return void
		 */
		public function setPodcast(bool $podcast) {
			$this->podcast = $podcast;
		}

		/**
		 * Atribui um novo valor à permissão de LEITURA na tabela REGISTRO
		 * @param boolean $registro Booleano referente à permissão
		 * @return void
		 */
		public function setRegistro(bool $registro) {
			$this->registro = $registro;
		}

		/**
		 * Atribui um novo valor à permissão de LEITURA nas tabelas
		 * @param boolean $tabela Booleano referente à permissão
		 * @return void
		 */
		public function setTabela(bool $tabela) {
			$this->tabela = $tabela;
		}

		/**
		 * Atribui um novo valor à permissão de CRUD na tabela USUARIO
		 * @param boolean $usuario Booleano referente à permissão
		 * @return void
		 */
		public function setUsuario(bool $usuario) {
			$this->usuario = $usuario;
		}

		/**
		 * Retorna o ID da permissão
		 * @return int Inteiro positivo único
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Retorna o status de CRUD na tabela BANNER
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isBanner() {
			return $this->banner;
		}

		/**
		 * Retorna o status de CRUD na tabela BOLETIM
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isBoletim() {
			return $this->boletim;
		}

		/**
		 * Retorna o status de CRUD na tabela CONVENCAO
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isConvencao() {
			return $this->convencao;
		}

		/**
		 * Retorna o status de CRUD na tabela CONVENIO
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isConvenio() {
			return $this->convenio;
		}

		/**
		 * Retorna o status de LEITURA no DIRETORIO
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isDiretorio() {
			return $this->diretorio;
		}

		/**
		 * Retorna o status de CRUD na tabela EDITAL
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isEdital() {
			return $this->edital;
		}

		/**
		 * Retorna o status de CRUD na tabela EVENTO
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isEvento() {
			return $this->evento;
		}

		/**
		 * Retorna o status de CRUD na tabela FINANCA
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isFinanca() {
			return $this->financa;
		}

		/**
		 * Retorna o status de CRUD na tabela JORNAL
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isJornal() {
			return $this->jornal;
		}

		/**
		 * Retorna o status de CRUD na tabela JURIDICO
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isJuridico() {
			return $this->juridico;
		}

		/**
		 * Retorna o status de CRUD na tabela NOTICIA
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isNoticia() {
			return $this->noticia;
		}

		/**
		 * Retorna o status de CRUD na tabela PODCAST
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isPodcast() {
			return $this->podcast;
		}

		/**
		 * Retorna o status de LEITURA na tabela REGISTRO
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isRegistro() {
			return $this->registro;
		}

		/**
		 * Retorna o status de LEITURA nas tabelas
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isTabela() {
			return $this->tabela;
		}

		/**
		 * Retorna o status de CRUD na tabela USUARIO
		 * @return boolean Permissão ou negação da atividade
		 */
		public function isUsuario() {
			return $this->usuario;
		}

		/**
		 * Retorna as permissões ativas em formato de texto
		 * @return string Permissões ativas
		 */
		public function getPermissoes() {
			$permissoes = "";
			$cont = 0;
			if($this->banner) {
				$permissoes .= "BANNER, ";
				$cont++;
			}
			if($this->boletim) {
				$permissoes .= "BOLETIM, ";
				$cont++;
			}
			if($this->convencao) {
				$permissoes .= "CONVENÇÃO, ";
				$cont++;
			}
			if($this->convenio) {
				$permissoes .= "CONVÊNIO, ";
				$cont++;
			}
			if($this->edital) {
				$permissoes .= "EDITAL, ";
				$cont++;
			}
			if($this->evento) {
				$permissoes .= "EVENTO, ";
				$cont++;
			}
			if($this->financa) {
				$permissoes .= "FINANÇA, ";
				$cont++;
			}
			if($this->jornal) {
				$permissoes .= "JORNAL, ";
				$cont++;
			}
			if($this->juridico) {
				$permissoes .= "JURÍDICO, ";
				$cont++;
			}
			if($this->noticia) {
				$permissoes .= "NOTÍCIA, ";
				$cont++;
			}
			if($this->podcast) {
				$permissoes .= "PODCAST, ";
				$cont++;
			}
			if($this->registro) {
				$permissoes .= "REGISTRO, ";
				$cont++;
			}
			if($this->usuario) {
				$permissoes .= "USUÁRIO, ";
				$cont++;
			}
			return $cont == 1 ? "PERMISSÃO: " . substr($permissoes, 0, strlen($permissoes) - 2) . "." : "PERMISSÕES: " . substr($permissoes, 0, strlen($permissoes) - 2) . ".";
		}

		/**
		 * Valida a permissão certificando todos os atributos
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function valida() {
			if($this->id && ($this->banner || $this->boletim || $this->convencao || $this->convenio || $this->diretorio || $this->edital || $this->evento || $this->financa || $this->jornal || $this->juridico || $this->noticia || $this->podcast || $this->registro || $this->tabela || $this->usuario)) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	}
?>