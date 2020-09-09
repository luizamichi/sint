<?php
	/**
	 * Controlador de remoção de edital
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("edital.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isEdital() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$editalRemover = $editalDAO->procurarId($_GET["id"]);
		if(!$editalRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o edital. Este não está cadastrado no sistema.";
			header("Location: ../sgc/editais.php");
			return FALSE;
		}
		$editalDAO->desativar($editalRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O EDITAL \"" . strtoupper($editalRemover->getTitulo()) . "\" (" . $editalRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O edital foi removido.";
		header("Location: ../sgc/editais.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o edital. Este não está cadastrado no sistema.";
		header("Location: ../sgc/editais.php");
		return FALSE;
	}
?>