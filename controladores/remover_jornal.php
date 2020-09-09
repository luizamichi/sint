<?php
	/**
	 * Controlador de remoção de jornal
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("jornal.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isJornal() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$jornalRemover = $jornalDAO->procurarId($_GET["id"]);
		if(!$jornalRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o jornal. Este não está cadastrado no sistema.";
			header("Location: ../sgc/jornais.php");
			return FALSE;
		}
		$jornalDAO->desativar($jornalRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O JORNAL \"" . strtoupper($jornalRemover->getTitulo()) . "\" (" . $jornalRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O jornal foi removido.";
		header("Location: ../sgc/jornais.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o jornal. Este não está cadastrado no sistema.";
		header("Location: ../sgc/jornais.php");
		return FALSE;
	}
?>