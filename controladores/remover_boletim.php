<?php
	/**
	 * Controlador de remoção de boletim
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("boletim.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isBoletim() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$boletimRemover = $boletimDAO->procurarId($_GET["id"]);
		if(!$boletimRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o boletim. Este não está cadastrado no sistema.";
			header("Location: ../sgc/boletins.php");
			return FALSE;
		}
		$boletimDAO->desativar($boletimRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O BOLETIM \"" . strtoupper($boletimRemover->getTitulo()) . "\" (" . $boletimRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O boletim foi removido.";
		header("Location: ../sgc/boletins.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o boletim. Este não está cadastrado no sistema.";
		header("Location: ../sgc/boletins.php");
		return FALSE;
	}
?>