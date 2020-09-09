<?php
	/**
	 * Controlador de logout (encerramento de sessão)
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
		$registroModelo->setDescricao($usuarioModelo->getNome() . " SAIU.");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		if($usuarioModelo->getPermissao()->isDiretorio() == FALSE || $usuarioModelo->getPermissao()->isTabela() == FALSE) {
			$registroDAO->inserir($registroModelo);
		}
		session_unset();
		session_destroy();
		echo "<script>
			alert('Logout efetuado com sucesso!');
			location.href = '../sgc.php';
			</script>";
		return TRUE;
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}
?>