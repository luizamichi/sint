<?php
	/**
	 * Controlador de inserção de finança
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("financa.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isFinanca() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputPeriodo"]) && !empty($_POST["inputAno"]) && !empty($_FILES["inputArquivo"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$financaInserir = new Financa();
		$inputPeriodo = $financaInserir->setPeriodo($_POST["inputPeriodo"] . " de " . $_POST["inputAno"]);
		$inputArquivo = $financaInserir->hashArquivo($_FILES["inputArquivo"]["name"]);

		if(!$inputPeriodo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar a finança. Verifique o período.";
			$_SESSION["financaInserir"] = serialize($financaInserir);
			header("Location: ../sgc/inserir_financa.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a finança. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a finança. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a finança. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["financaInserir"] = serialize($financaInserir);
				header("Location: ../sgc/inserir_financa.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $financaInserir->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a finança. Verifique o arquivo.";
				$_SESSION["financaInserir"] = serialize($financaInserir);
				header("Location: ../sgc/inserir_financa.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar a finança. Verifique o formato do arquivo.";
			$_SESSION["financaInserir"] = serialize($financaInserir);
			header("Location: ../sgc/inserir_financa.php");
			return FALSE;
		}

		$financaInserir->setUsuario($usuarioModelo);
		$financaInserir->setData(date("Y-m-d H:i:s"));
		$financaInserir->setStatus(TRUE);
		$financaDAO->inserir($financaInserir);
		$financaInserir->setId($financaDAO->procurarUltimo()->getId());
		if(strcmp(substr($financaInserir->getFlag(), -2), ".1") == 0) {
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU A FINANÇA DO " . strtoupper($financaInserir->getPeriodo()) . " (" . $financaInserir->getId() . ") . ");
		}
		else {
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU A FINANÇA DE " . strtoupper($financaInserir->getPeriodo()) . " (" . $financaInserir->getId() . ") . ");
		}
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Finança cadastrada com sucesso.";
		header("Location: ../sgc/inserir_financa.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar a finança. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_financa.php");
		return FALSE;
	}
?>