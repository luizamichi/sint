<?php
	/**
	 * Controlador de remoção de convenção
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("convencao.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isConvencao() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$convencaoRemover = $convencaoDAO->procurarId($_GET["id"]);
		if(!$convencaoRemover) {
			$_SESSION["resposta"] = "Não foi possível remover a convenção. Esta não está cadastrada no sistema.";
			header("Location: ../sgc/convencoes.php");
			return FALSE;
		}
		$convencaoDAO->desativar($convencaoRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU A CONVENÇÃO \"" . strtoupper($convencaoRemover->getTitulo()) . "\" (" . $convencaoRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "A convenção foi removida.";
		header("Location: ../sgc/convencoes.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover a convenção. Esta não está cadastrada no sistema.";
		header("Location: ../sgc/convencoes.php");
		return FALSE;
	}
?>