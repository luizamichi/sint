<?php
	/**
	 * Controlador de alteração de jornal
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("jornal.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isJornal() == FALSE || !isset($_SESSION["jornalAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$jornalAlterar = unserialize($_SESSION["jornalAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_POST["inputEdicao"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $jornalAlterar->setTitulo($_POST["inputTitulo"]);
		$inputEdicao = $jornalAlterar->setEdicao($_POST["inputEdicao"]);
		$inputArquivo = $jornalAlterar->hashArquivo($_FILES["inputArquivo"]["name"]);
		$inputImagem = $jornalAlterar->hashImagem($_FILES["inputImagem"]["name"], !empty($_POST["inputLimpaImagem"]));

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar o jornal. Verifique o título.";
			$_SESSION["jornalAlterar"] = serialize($jornalAlterar);
			header("Location: ../sgc/alterar_jornal.php");
			return FALSE;
		}
		if(!$inputEdicao) {
			$_SESSION["resposta"] = "Não foi possível alterar o jornal. Verifique a edição.";
			$_SESSION["jornalAlterar"] = serialize($jornalAlterar);
			header("Location: ../sgc/alterar_jornal.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["jornalAlterar"] = serialize($jornalAlterar);
				header("Location: ../sgc/alterar_jornal.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $jornalAlterar->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. Verifique o arquivo.";
				$_SESSION["jornalAlterar"] = serialize($jornalAlterar);
				header("Location: ../sgc/alterar_jornal.php");
				return FALSE;
			}
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["jornalAlterar"] = serialize($jornalAlterar);
				header("Location: ../sgc/alterar_jornal.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $jornalAlterar->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível alterar o jornal. Verifique a imagem.";
				$_SESSION["jornalAlterar"] = serialize($jornalAlterar);
				header("Location: ../sgc/alterar_jornal.php");
				return FALSE;
			}
		}

		$jornalAlterar->setUsuario($usuarioModelo);
		$jornalAlterar->setData(date("Y-m-d H:i:s"));
		$jornalDAO->alterar($jornalAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O JORNAL " . strtoupper($jornalAlterar->getTitulo()) . " (" . $jornalAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Jornal alterado com sucesso.";
		header("Location: ../sgc/alterar_jornal.php?id=" . $jornalAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o jornal. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_jornal.php");
		return FALSE;
	}
?>