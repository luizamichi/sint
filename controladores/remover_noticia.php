<?php
	/**
	 * Controlador de remoção de notícia
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("noticia.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isNoticia() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$noticiaRemover = $noticiaDAO->procurarId($_GET["id"]);
		if(!$noticiaRemover) {
			$_SESSION["resposta"] = "Não foi possível remover a notícia. Esta não está cadastrada no sistema.";
			header("Location: ../sgc/noticias.php");
			return FALSE;
		}
		$noticiaDAO->desativar($noticiaRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU A NOTÍCIA \"" . strtoupper($noticiaRemover->getTitulo()) . "\" (" . $noticiaRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "A notícia foi removida.";
		header("Location: ../sgc/noticias.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover a notícia. Esta não está cadastrada no sistema.";
		header("Location: ../sgc/noticias.php");
		return FALSE;
	}
?>