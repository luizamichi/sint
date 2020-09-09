<?php
	/**
	 * Controlador de remoção de usuário
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("registro.php");
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isUsuario() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$usuarioRemover = $usuarioDAO->procurarId($_GET["id"]);
		if(!$usuarioRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o usuário. Este não está cadastrado no sistema.";
			header("Location: ../sgc/usuarios.php");
			return FALSE;
		}
		$usuarioDAO->desativar($usuarioRemover);
		$registroModelo->setDescricao($usuarioModelo->getNome() . " REMOVEU O USUÁRIO " . $usuarioRemover->getNome() . " (" . $usuarioRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O usuário foi removido.";
		header("Location: ../sgc/usuarios.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o usuário. Este não está cadastrado no sistema.";
		header("Location: ../sgc/usuarios.php");
		return FALSE;
	}
?>