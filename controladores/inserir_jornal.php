<?php
	/**
	 * Controlador de inserção de jornal
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
		if($usuarioModelo->getPermissao()->isJornal() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_POST["inputEdicao"]) && !empty($_FILES["inputArquivo"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$jornalInserir = new Jornal();
		$inputTitulo = $jornalInserir->setTitulo($_POST["inputTitulo"]);
		$inputEdicao = $jornalInserir->setEdicao($_POST["inputEdicao"]);
		$inputArquivo = $jornalInserir->hashArquivo($_FILES["inputArquivo"]["name"]);
		$inputImagem = $jornalInserir->hashImagem($_FILES["inputImagem"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Verifique o título.";
			$_SESSION["jornalInserir"] = serialize($jornalInserir);
			header("Location: ../sgc/inserir_jornal.php");
			return FALSE;
		}
		if(!$inputEdicao) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Verifique a edição.";
			$_SESSION["jornalInserir"] = serialize($jornalInserir);
			header("Location: ../sgc/inserir_jornal.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["jornalInserir"] = serialize($jornalInserir);
				header("Location: ../sgc/inserir_jornal.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $jornalInserir->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Verifique o arquivo.";
				$_SESSION["jornalInserir"] = serialize($jornalInserir);
				header("Location: ../sgc/inserir_jornal.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Verifique o formato do arquivo.";
			$_SESSION["jornalInserir"] = serialize($jornalInserir);
			header("Location: ../sgc/inserir_jornal.php");
			return FALSE;
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["jornalInserir"] = serialize($jornalInserir);
				header("Location: ../sgc/inserir_jornal.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $jornalInserir->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Verifique a imagem.";
				$_SESSION["jornalInserir"] = serialize($jornalInserir);
				header("Location: ../sgc/inserir_jornal.php");
				return FALSE;
			}
		}

		$jornalInserir->setUsuario($usuarioModelo);
		$jornalInserir->setData(date("Y-m-d H:i:s"));
		$jornalInserir->setStatus(TRUE);
		$jornalDAO->inserir($jornalInserir);
		$jornalInserir->setId($jornalDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O JORNAL " . strtoupper($jornalInserir->getTitulo()) . " (" . $jornalInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Jornal cadastrado com sucesso.";
		header("Location: ../sgc/inserir_jornal.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o jornal. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_jornal.php");
		return FALSE;
	}
?>