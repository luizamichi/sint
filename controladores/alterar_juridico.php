<?php
	/**
	 * Controlador de alteração de jurídico
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
		if($usuarioModelo->getPermissao()->isJuridico() == FALSE || !isset($_SESSION["juridicoAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$juridicoAlterar = unserialize($_SESSION["juridicoAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $juridicoAlterar->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $juridicoAlterar->setDescricao($_POST["inputDescricao"]);
		$inputArquivo = $juridicoAlterar->hashArquivo($_FILES["inputArquivo"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar o jurídico. Verifique o título.";
			$_SESSION["juridicoAlterar"] = serialize($juridicoAlterar);
			header("Location: ../sgc/alterar_juridico.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível alterar o jurídico. Verifique a descrição.";
			$_SESSION["juridicoAlterar"] = serialize($juridicoAlterar);
			header("Location: ../sgc/inserir_juridico.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o jurídico. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o jurídico. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o jurídico. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["juridicoAlterar"] = serialize($juridicoAlterar);
				header("Location: ../sgc/alterar_juridico.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $juridicoAlterar->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível alterar o jurídico. Verifique o arquivo.";
				$_SESSION["juridicoAlterar"] = serialize($juridicoAlterar);
				header("Location: ../sgc/alterar_juridico.php");
				return FALSE;
			}
		}

		$juridicoAlterar->setUsuario($usuarioModelo);
		$juridicoAlterar->setData(date("Y-m-d H:i:s"));
		$juridicoDAO->alterar($juridicoAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O JURÍDICO " . strtoupper($juridicoAlterar->getTitulo()) . " (" . $juridicoAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Jurídico alterado com sucesso.";
		header("Location: ../sgc/alterar_juridico.php?id=" . $juridicoAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o jurídico. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_juridico.php");
		return FALSE;
	}
?>