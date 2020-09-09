<?php
	/**
	 * Controlador de remoção de evento
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("evento.php");
	include_once("imagem.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isEvento() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
		$eventoRemover = $eventoDAO->procurarId($_GET["id"]);
		if(!$eventoRemover) {
			$_SESSION["resposta"] = "Não foi possível remover o evento. Este não está cadastrado no sistema.";
			header("Location: ../sgc/eventos.php");
			return FALSE;
		}
		$eventoDAO->desativar($eventoRemover);
		foreach($eventoRemover->getImagens() as $imagem) {
			$imagemDAO->desativar($imagem);
		}
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " REMOVEU O EVENTO \"" . strtoupper($eventoRemover->getTitulo()) . "\" (" . $eventoRemover->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "O evento foi removido.";
		header("Location: ../sgc/eventos.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível remover o evento. Este não está cadastrado no sistema.";
		header("Location: ../sgc/eventos.php");
		return FALSE;
	}
?>