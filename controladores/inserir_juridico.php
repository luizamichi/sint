<?php
	/**
	 * Controlador de inserção de jurídico
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("juridico.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isJuridico() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_FILES["inputArquivo"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$juridicoInserir = new Juridico();
		$inputTitulo = $juridicoInserir->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $juridicoInserir->setDescricao($_POST["inputDescricao"]);
		$inputArquivo = $juridicoInserir->hashArquivo($_FILES["inputArquivo"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. Verifique o título.";
			$_SESSION["juridicoInserir"] = serialize($juridicoInserir);
			header("Location: ../sgc/inserir_juridico.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. Verifique a descrição.";
			$_SESSION["juridicoInserir"] = serialize($juridicoInserir);
			header("Location: ../sgc/inserir_juridico.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["juridicoInserir"] = serialize($juridicoInserir);
				header("Location: ../sgc/inserir_juridico.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $juridicoInserir->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. Verifique o arquivo.";
				$_SESSION["juridicoInserir"] = serialize($juridicoInserir);
				header("Location: ../sgc/inserir_juridico.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. Verifique o formato do arquivo.";
			$_SESSION["juridicoInserir"] = serialize($juridicoInserir);
			header("Location: ../sgc/inserir_juridico.php");
			return FALSE;
		}

		$juridicoInserir->setUsuario($usuarioModelo);
		$juridicoInserir->setData(date("Y-m-d H:i:s"));
		$juridicoInserir->setStatus(TRUE);
		$juridicoDAO->inserir($juridicoInserir);
		$juridicoInserir->setId($juridicoDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O JURÍDICO " . strtoupper($juridicoInserir->getTitulo()) . " (" . $juridicoInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Jurídico cadastrado com sucesso.";
		header("Location: ../sgc/inserir_juridico.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o jurídico. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_juridico.php");
		return FALSE;
	}
?>