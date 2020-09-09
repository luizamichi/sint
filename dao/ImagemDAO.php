<?php
	include_once("../modelos/Database.php");
	/**
	 * Classe DAO Imagem com atributo simples (Banco de dados) no modo procedural
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	class ImagemDAO {
		/**
		 * Variável privada
		 * @var Database $database Banco de dados onde está localizada a tabela IMAGEM
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
		 * Procura uma imagem no banco de dados pelo ID
		 * @param int $id ID da imagem a ser buscada
		 * @return array|false Dicionário (ID, imagem, evento, status)
		 */
		public function procurarId(int $id) {
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
			mysqli_stmt_close($stmt);
			return $imagem;
		}

		/**
		 * Procura uma imagem no banco de dados pelo evento
		 * @param int $evento ID do evento a ser buscado
		 * @return array Imagens encontradas
		 */
		public function procurarEvento(int $evento) {
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE EVENTO=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $evento);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Procura uma imagem no banco de dados pelo nome da imagem
		 * @param string $imagem Imagem ou parte da imagem a ser buscada
		 * @return array Imagens encontradas
		 */
		public function procurarImagem(string $imagem) {
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE IMAGEM LIKE CONCAT('%',?,'%')";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "s", $imagem);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Procura uma imagem no banco de dados pelo status
		 * @param boolean $status Ativo (TRUE) ou inativo (FALSE)
		 * @return array Imagens encontradas
		 */
		public function procurarStatus(bool $status) {
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE STATUS=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $status);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Procura a última imagem cadastrada no banco de dados
		 * @return array|false Dicionário (ID, imagem, evento, status)
		 */
		public function procurarUltimo() {
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID ORDER BY ID DESC LIMIT 1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			if(!mysqli_stmt_fetch($stmt)) {
				return FALSE;
			}
			$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
			mysqli_stmt_close($stmt);
			return $imagem;
		}

		/**
		 * Insere uma nova imagem no banco de dados
		 * @param array $imagem Dicionário estruturado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function inserir(array $imagem) {
			$query = "INSERT INTO IMAGEM (IMAGEM, EVENTO, STATUS) VALUES (?, ?, ?)";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$evento = $imagem["EVENTO"];
			$status = $imagem["STATUS"];
			$imagem = $imagem["IMAGEM"];
			mysqli_stmt_bind_param($stmt, "sii", $imagem, $evento, $status);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Altera uma imagem no banco de dados
		 * @param array $imagem Dicionário estruturado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function alterar(array $imagem) {
			$query = "UPDATE IMAGEM SET IMAGEM=?, EVENTO=?, STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$imagem = $imagem["IMAGEM"];
			$evento = $imagem["EVENTO"];
			$status = $imagem["STATUS"];
			$id = $imagem["ID"];
			mysqli_stmt_bind_param($stmt, "siii", $imagem, $evento, $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Remove uma imagem do banco de dados
		 * @param array $imagem Dicionário estruturado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function remover(array $imagem) {
			$query = "DELETE FROM IMAGEM WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$id = $imagem["ID"];
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Ativa uma imagem no banco de dados
		 * @param array $imagem Dicionário estruturado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function ativar(array $imagem) {
			$query = "UPDATE IMAGEM SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = TRUE;
			$id = $imagem["ID"];
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Desativa uma imagem no banco de dados
		 * @param array $imagem Dicionário estruturado
		 * @return boolean Sucesso ou fracasso na operação
		 */
		public function desativar(array $imagem) {
			$query = "UPDATE IMAGEM SET STATUS=? WHERE ID=?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			$status = FALSE;
			$id = $imagem["ID"];
			mysqli_stmt_bind_param($stmt, "ii", $status, $id);
			mysqli_stmt_execute($stmt);
			$operacao = mysqli_affected_rows($this->database->getConexao()) > 0 ? TRUE : FALSE;
			mysqli_stmt_close($stmt);
			return $operacao;
		}

		/**
		 * Lista todos as imagens do banco de dados ordenadas decrescentemente pelo evento
		 * @return array Imagens encontradas
		 */
		public function listar() {
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID ORDER BY I.EVENTO DESC";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Lista as N imagens do banco de dados ordenadas decrescentemente pelo evento
		 * @param int $numero Número de imagens a ser listado
		 * @return array|false Imagens encontradas
		 */
		public function listarQuantidade(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID ORDER BY EVENTO DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Lista as N imagens ativas do banco de dados ordenadas decrescentemente pelo evento
		 * @param int $numero Número de imagens a ser listado
		 * @return array|false Imagens encontradas
		 */
		public function listarAtivo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE STATUS=1 ORDER BY EVENTO DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Lista as N imagens inativas do banco de dados ordenadas decrescentemente pelo ID
		 * @param int $numero Número de imagens a ser listado
		 * @return array|false Imagens encontradas
		 */
		public function listarInativo(int $numero) {
			if($numero <= 0) {
				return FALSE;
			}
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE STATUS=0 ORDER BY EVENTO DESC LIMIT ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "i", $numero);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Lista as imagens do banco de dados ordenadas decrescentemente pelo evento em um dado intervalo
		 * @param int $inicio Número do primeiro evento
		 * @param int $quantidade Limite do último evento
		 * @return array|false Imagens encontradas
		 */
		public function listarIntervalo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID ORDER BY EVENTO DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Lista as imagens ativas do banco de dados ordenadas decrescentemente pelo evento em um dado intervalo
		 * @param int $inicio Número do primeiro evento
		 * @param int $quantidade Limite do último evento
		 * @return array|false Imagens encontradas
		 */
		public function listarIntervaloAtivo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE E.STATUS=1 ORDER BY EVENTO DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Lista as imagens inativas do banco de dados ordenadas decrescentemente pelo ID em um dado intervalo
		 * @param int $inicio Número do primeiro evento
		 * @param int $quantidade Limite do último evento
		 * @return array|false Imagens encontradas
		 */
		public function listarIntervaloInativo(int $inicio, int $quantidade) {
			if($inicio < 0 || $quantidade <= 0) {
				return FALSE;
			}
			$query = "SELECT I.*, E.DIRETORIO FROM IMAGEM AS I INNER JOIN EVENTO AS E ON I.EVENTO=E.ID WHERE E.STATUS=0 ORDER BY EVENTO DESC LIMIT ?, ?";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_bind_param($stmt, "ii", $inicio, $quantidade);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $id, $imagem, $evento, $status, $diretorio);
			$imagens = array();
			while(mysqli_stmt_fetch($stmt)) {
				$imagem = ["ID" => $id, "IMAGEM" => $imagem, "EVENTO" => $evento, "DIRETORIO" => $diretorio, "STATUS" => $status];
				array_push($imagens, $imagem);
			}
			mysqli_stmt_close($stmt);
			return $imagens;
		}

		/**
		 * Retorna o número de imagens cadastradas no banco de dados
		 * @return int Imagens cadastradas
		 */
		public function tamanho() {
			$query = "SELECT COUNT(*) FROM IMAGEM";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de imagens ativas no banco de dados
		 * @return int Imagens cadastradas ativas
		 */
		public function tamanhoAtivo() {
			$query = "SELECT COUNT(*) FROM IMAGEM WHERE STATUS=1";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}

		/**
		 * Retorna o número de imagens inativas no banco de dados
		 * @return int Imagens cadastradas inativas
		 */
		public function tamanhoInativo() {
			$query = "SELECT COUNT(*) FROM IMAGEM WHERE STATUS=0";
			$stmt = mysqli_prepare($this->database->getConexao(), $query);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $tamanho);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
			return $tamanho;
		}
	}
?>