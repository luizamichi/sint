<?php
	/**
	 * Controlador de alteração de podcast
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
		if($usuarioModelo->getPermissao()->isPodcast() == FALSE || !isset($_SESSION["podcastAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$podcastAlterar = unserialize($_SESSION["podcastAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $podcastAlterar->setTitulo($_POST["inputTitulo"]);
		$inputAudio = $podcastAlterar->hashAudio($_FILES["inputAudio"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar o podcast. Verifique o título.";
			$_SESSION["podcastAlterar"] = serialize($podcastAlterar);
			header("Location: ../sgc/alterar_podcast.php");
			return FALSE;
		}
		if($inputAudio) {
			if($_FILES["inputAudio"]["error"] == 1 || $_FILES["inputAudio"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o podcast. O áudio excede o tamanho máximo de 100MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputAudio"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o podcast. O envio do áudio foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputAudio"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o podcast. Nenhum áudio foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["podcastAlterar"] = serialize($podcastAlterar);
				header("Location: ../sgc/alterar_podcast.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputAudio"]["tmp_name"], "../" . $podcastAlterar->getAudio())) {
				$_SESSION["resposta"] = "Não foi possível alterar o podcast. Verifique o áudio.";
				$_SESSION["podcastAlterar"] = serialize($podcastAlterar);
				header("Location: ../sgc/alterar_podcast.php");
				return FALSE;
			}
		}

		$podcastAlterar->setUsuario($usuarioModelo);
		$podcastAlterar->setData(date("Y-m-d H:i:s"));
		$podcastDAO->alterar($podcastAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O PODCAST " . strtoupper($podcastAlterar->getTitulo()) . " (" . $podcastAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Podcast alterado com sucesso.";
		header("Location: ../sgc/alterar_podcast.php?id=" . $podcastAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o podcast. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_podcast.php");
		return FALSE;
	}
?>