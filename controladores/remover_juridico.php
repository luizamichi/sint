<?php
	/**
	 * Controlador de remoção de jurídico
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("juridico.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isJuridico() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$juridicoRemover = $juridicoDAO->procurarId($_GET["id"]);
		if(!$juridicoRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o jurídico. Este não está cadastrado no sistema.";
			header("Location: ../sgc/juridicos.php");
			return FALSE;
		}
		$juridicoDAO->desativar($juridicoRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O JURÍDICO \"" . strtoupper($juridicoRemover->getTitulo()) . "\" (" . $juridicoRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O jurídico foi removido.";
		header("Location: ../sgc/juridicos.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o jurídico. Este não está cadastrado no sistema.";
		header("Location: ../sgc/juridicos.php");
		return FALSE;
	}
?>