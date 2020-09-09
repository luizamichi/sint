<?php
	/**
	 * Controlador de inserção de banner
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

	if(!empty($_FILES["inputImagem"]["name"])) { // O DADO ESTÁ CORRETO
		$bannerInserir = new Banner();
		$inputImagem = $bannerInserir->hashImagem($_FILES["inputImagem"]["name"]);
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o banner. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o banner. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o banner. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["bannerInserir"] = serialize($bannerInserir);
				header("Location: ../sgc/inserir_banner.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $bannerInserir->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o banner. Verifique a imagem.";
				$_SESSION["bannerInserir"] = serialize($bannerInserir);
				header("Location: ../sgc/inserir_banner.php");
				return FALSE;
			}

			$bannerInserir->setUsuario($usuarioModelo);
			$bannerInserir->setData(date("Y-m-d H:i:s"));
			$bannerInserir->setStatus(TRUE);
			$bannerDAO->inserir($bannerInserir);
			$bannerInserir->setId($bannerDAO->procurarUltimo()->getId());
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O BANNER " . strtoupper(substr($bannerInserir->getImagem(), 16)) . " (" . $bannerInserir->getId() . ") . ");
			$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
			$registroModelo->setData(date("Y-m-d H:i:s"));
			$registroDAO->inserir($registroModelo);
			$_SESSION["resposta"] = "Banner cadastrado com sucesso.";
			header("Location: ../sgc/inserir_banner.php");
			return TRUE;
		}

		$_SESSION["resposta"] = "Não foi possível cadastrar o banner. Ocorreu um erro não catalogado.";
		header("Location: ../sgc/inserir_banner.php");
		return FALSE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o banner. É necessário enviar uma imagem.";
		header("Location: ../sgc/inserir_banner.php");
		return FALSE;
	}
?>