<?php
	/**
	 * Controlador de inserção de acesso
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("acesso.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["acessoModelo"])) { // NÃO HÁ UM ACESSO ANTERIOR NA MESMA SESSÃO
		$acessoModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$acessoModelo->setData(date("Y-m-d H:i:s"));
		$acessoDAO->inserir($acessoModelo);
		$_SESSION["acessoModelo"] = $acessoModelo;
		return TRUE;
	}
?>