<?php
	/**
	 * Controlador de renovação de sessão
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		$senha = $_SESSION["senha"];
		$tema = $_SESSION["tema"];
		$usuarioRenovar = $usuarioDAO->procurarId($usuarioModelo->getId());
		if($usuarioRenovar && strcmp($usuarioModelo->getSenha(), $usuarioRenovar->getSenha()) == 0 && $usuarioRenovar->isStatus() == TRUE) {
			session_unset();
			$_SESSION["usuarioModelo"] = serialize($usuarioRenovar);
			$_SESSION["senha"] = $senha;
			$_SESSION["tema"] = $tema;
			header("Location: ../sgc/index.php");
			return TRUE;
		}
		else {
			session_unset();
			session_destroy();
			header("Location: ../sgc.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}
?>