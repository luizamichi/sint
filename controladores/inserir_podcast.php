<?php
	/**
	 * Controlador de inserção de podcast
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("podcast.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isPodcast() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_FILES["inputAudio"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$podcastInserir = new Podcast();
		$inputTitulo = $podcastInserir->setTitulo($_POST["inputTitulo"]);
		$inputAudio = $podcastInserir->hashAudio($_FILES["inputAudio"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o podcast. Verifique o título.";
			$_SESSION["podcastInserir"] = serialize($podcastInserir);
			header("Location: ../sgc/inserir_podcast.php");
			return FALSE;
		}
		if($inputAudio) {
			if($_FILES["inputAudio"]["error"] == 1 || $_FILES["inputAudio"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o podcast. O áudio excede o tamanho máximo de 100MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputAudio"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o podcast. O envio do áudio foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputAudio"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o podcast. Nenhum áudio foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["podcastInserir"] = serialize($podcastInserir);
				header("Location: ../sgc/inserir_podcast.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputAudio"]["tmp_name"], "../" . $podcastInserir->getAudio())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o podcast. Verifique o áudio.";
				$_SESSION["podcastInserir"] = serialize($podcastInserir);
				header("Location: ../sgc/inserir_podcast.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar o podcast. Verifique o formato do áudio.";
			$_SESSION["podcastInserir"] = serialize($podcastInserir);
			header("Location: ../sgc/inserir_podcast.php");
			return FALSE;
		}

		$podcastInserir->setUsuario($usuarioModelo);
		$podcastInserir->setData(date("Y-m-d H:i:s"));
		$podcastInserir->setStatus(TRUE);
		$podcastDAO->inserir($podcastInserir);
		$podcastInserir->setId($podcastDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O PODCAST " . strtoupper($podcastInserir->getTitulo()) . " (" . $podcastInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Podcast cadastrado com sucesso.";
		header("Location: ../sgc/inserir_podcast.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o podcast. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_podcast.php");
		return FALSE;
	}
?>