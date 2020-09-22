<?php
	/**
	 * Controlador de inserção de edital
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("edital.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isEdital() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_FILES["inputImagem"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$editalInserir = new Edital();
		$inputTitulo = $editalInserir->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $editalInserir->setDescricao($_POST["inputDescricao"]);
		$inputImagem = $editalInserir->hashImagem($_FILES["inputImagem"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o edital. Verifique o título.";
			$_SESSION["editalInserir"] = serialize($editalInserir);
			header("Location: ../sgc/inserir_edital.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o edital. Verifique a descrição.";
			$_SESSION["editalInserir"] = serialize($editalInserir);
			header("Location: ../sgc/inserir_edital.php");
			return FALSE;
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o edital. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o edital. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o edital. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["editalInserir"] = serialize($editalInserir);
				header("Location: ../sgc/inserir_edital.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $editalInserir->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o edital. Verifique a imagem.";
				$_SESSION["editalInserir"] = serialize($editalInserir);
				header("Location: ../sgc/inserir_edital.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar o edital. Verifique o formato da imagem.";
			$_SESSION["editalInserir"] = serialize($editalInserir);
			header("Location: ../sgc/inserir_edital.php");
			return FALSE;
		}

		$editalInserir->setUsuario($usuarioModelo);
		$editalInserir->setData(date("Y-m-d H:i:s"));
		$editalInserir->setStatus(TRUE);
		$editalDAO->inserir($editalInserir);
		$editalInserir->setId($editalDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O EDITAL " . strtoupper($editalInserir->getTitulo()) . " (" . $editalInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Edital cadastrado com sucesso.";
		header("Location: ../sgc/inserir_edital.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o edital. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_edital.php");
		return FALSE;
	}
?>