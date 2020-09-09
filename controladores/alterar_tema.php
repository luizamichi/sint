<?php
	/**
	 * Controlador de alteração de tema
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	if(isset($_SESSION["usuarioModelo"]) && isset($_SESSION["tema"])) {
		if(strcmp($_SESSION["tema"], "Claro") == 0) {
			$_SESSION["tema"] = "Escuro";
		}
		else {
			$_SESSION["tema"] = "Claro";
		}
		header("Location: ../sgc/index.php");
		return TRUE;
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}
?>