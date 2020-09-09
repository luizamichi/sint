<?php
	/**
	 * Controlador de remoção de convênio
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("convenio.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isConvenio() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$convenioRemover = $convenioDAO->procurarId($_GET["id"]);
		if(!$convenioRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o convênio. Este não está cadastrado no sistema.";
			header("Location: ../sgc/convenios.php");
			return FALSE;
		}
		$convenioDAO->desativar($convenioRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O CONVÊNIO \"" . strtoupper($convenioRemover->getTitulo()) . "\" (" . $convenioRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O convênio foi removido.";
		header("Location: ../sgc/convenios.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o convênio. Este não está cadastrado no sistema.";
		header("Location: ../sgc/convenios.php");
		return FALSE;
	}
?>