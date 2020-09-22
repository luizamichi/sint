<?php
	/**
	 * Controlador de inserção de convenção
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("convencao.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isConvencao() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && isset($_POST["inputTipo"]) && !empty($_FILES["inputArquivo"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$convencaoInserir = new Convencao();
		$inputTitulo = $convencaoInserir->setTitulo($_POST["inputTitulo"]);
		$inputArquivo = $convencaoInserir->hashArquivo($_FILES["inputArquivo"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar a convenção. Verifique o título.";
			$_SESSION["convencaoInserir"] = serialize($convencaoInserir);
			header("Location: ../sgc/inserir_convencao.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a convenção. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a convenção. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a convenção. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["convencaoInserir"] = serialize($convencaoInserir);
				header("Location: ../sgc/inserir_convencao.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $convencaoInserir->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a convenção. Verifique o arquivo.";
				$_SESSION["convencaoInserir"] = serialize($convencaoInserir);
				header("Location: ../sgc/inserir_convencao.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar a convenção. Verifique o formato do arquivo.";
			$_SESSION["convencaoInserir"] = serialize($convencaoInserir);
			header("Location: ../sgc/inserir_convencao.php");
			return FALSE;
		}

		$convencaoInserir->setTipo((bool) $_POST["inputTipo"]);
		$convencaoInserir->setUsuario($usuarioModelo);
		$convencaoInserir->setData(date("Y-m-d H:i:s"));
		$convencaoInserir->setStatus(TRUE);
		$convencaoDAO->inserir($convencaoInserir);
		$convencaoInserir->setId($convencaoDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU A CONVENÇÃO " . strtoupper($convencaoInserir->getTitulo()) . " (" . $convencaoInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Convenção cadastrada com sucesso.";
		header("Location: ../sgc/inserir_convencao.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar a convenção. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_convencao.php");
		return FALSE;
	}
?>