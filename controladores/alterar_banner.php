<?php
	/**
	 * Controlador de alteração de banner
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
		if($usuarioModelo->getPermissao()->isBanner() == FALSE || !isset($_SESSION["bannerAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$bannerAlterar = unserialize($_SESSION["bannerAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_FILES["inputImagem"]["name"])) { // O DADO ESTÁ CORRETO
		$inputImagem = $bannerAlterar->hashImagem($_FILES["inputImagem"]["name"]);
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o banner. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o banner. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o banner. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["bannerAlterar"] = serialize($bannerAlterar);
				header("Location: ../sgc/alterar_banner.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $bannerAlterar->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível alterar o banner. Verifique a imagem.";
				$_SESSION["bannerAlterar"] = serialize($bannerAlterar);
				header("Location: ../sgc/alterar_banner.php");
				return FALSE;
			}

			$bannerAlterar->setUsuario($usuarioModelo);
			$bannerAlterar->setData(date("Y-m-d H:i:s"));
			$bannerAlterar->setStatus(TRUE);
			$bannerDAO->alterar($bannerAlterar);
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O BANNER " . strtoupper(substr($bannerAlterar->getImagem(), 16)) . " (" . $bannerAlterar->getId() . ") . ");
			$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
			$registroModelo->setData(date("Y-m-d H:i:s"));
			$registroDAO->inserir($registroModelo);
			$_SESSION["resposta"] = "Banner alterado com sucesso.";
			header("Location: ../sgc/alterar_banner.php?id=" . $bannerAlterar->getId());
			return TRUE;
		}

		$_SESSION["resposta"] = "Não foi possível alterar o banner. Ocorreu um erro não catalogado.";
		header("Location: ../sgc/alterar_banner.php");
		return FALSE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o banner. É necessário enviar uma imagem.";
		header("Location: ../sgc/alterar_banner.php");
		return FALSE;
	}
?>