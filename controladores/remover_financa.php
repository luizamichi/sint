<?php
	/**
	 * Controlador de remoção de finança
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("financa.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isFinanca() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$financaRemover = $financaDAO->procurarId($_GET["id"]);
		if(!$financaRemover) {
			$_SESSION["resposta"] = "Não foi possível remover a finança. Esta não está cadastrada no sistema.";
			header("Location: ../sgc/financas.php");
			return FALSE;
		}
		$financaDAO->desativar($financaRemover);
		if(strcmp(strtoupper(explode(" ", $financaRemover->getPeriodo())[1]), "TRIMESTRE") == 0) {
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU A FINANÇA DO " . strtoupper($financaRemover->getPeriodo()) . " (" . $financaRemover->getId() . ") . ");
		}
		else {
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU A FINANÇA DE " . strtoupper($financaRemover->getPeriodo()) . " (" . $financaRemover->getId() . ") . ");
		}
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "A finança foi removida.";
		header("Location: ../sgc/financas.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover a finança. Esta não está cadastrada no sistema.";
		header("Location: ../sgc/financas.php");
		return FALSE;
	}
?>