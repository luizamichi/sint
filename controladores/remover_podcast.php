<?php
	/**
	 * Controlador de remoção de podcast
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("podcast.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isPodcast() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$podcastRemover = $podcastDAO->procurarId($_GET["id"]);
		if(!$podcastRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o podcast. Este não está cadastrado no sistema.";
			header("Location: ../sgc/podcasts.php");
			return FALSE;
		}
		$podcastDAO->desativar($podcastRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O PODCAST \"" . strtoupper($podcastRemover->getTitulo()) . "\" (" . $podcastRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O podcast foi removido.";
		header("Location: ../sgc/podcasts.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o podcast. Este não está cadastrado no sistema.";
		header("Location: ../sgc/podcasts.php");
		return FALSE;
	}
?>