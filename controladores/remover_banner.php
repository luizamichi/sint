<?php
	/**
	 * Controlador de remoção de banner
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("banner.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isBanner() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$bannerRemover = $bannerDAO->procurarId($_GET["id"]);
		if(!$bannerRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o banner. Este não está cadastrado no sistema.";
			header("Location: ../sgc/banners.php");
			return FALSE;
		}
		$bannerDAO->desativar($bannerRemover);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O BANNER \"" . strtoupper(substr($bannerRemover->getImagem(), 16)) . "\" (" . $bannerRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O banner foi removido.";
		header("Location: ../sgc/banners.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o banner. Este não está cadastrado no sistema.";
		header("Location: ../sgc/banners.php");
		return FALSE;
	}
?>