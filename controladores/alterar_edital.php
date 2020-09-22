<?php
	/**
	 * Controlador de alteração de edital
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
		if($usuarioModelo->getPermissao()->isEdital() == FALSE || !isset($_SESSION["editalAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$editalAlterar = unserialize($_SESSION["editalAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $editalAlterar->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $editalAlterar->setDescricao($_POST["inputDescricao"]);
		$inputImagem = $editalAlterar->hashImagem($_FILES["inputImagem"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar o edital. Verifique o título.";
			$_SESSION["editalAlterar"] = serialize($editalAlterar);
			header("Location: ../sgc/alterar_edital.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível alterar o edital. Verifique a descrição.";
			$_SESSION["editalAlterar"] = serialize($editalAlterar);
			header("Location: ../sgc/inserir_edital.php");
			return FALSE;
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o edital. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o edital. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o edital. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["editalAlterar"] = serialize($editalAlterar);
				header("Location: ../sgc/alterar_edital.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $editalAlterar->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível alterar o edital. Verifique a imagem.";
				$_SESSION["editalAlterar"] = serialize($editalAlterar);
				header("Location: ../sgc/alterar_edital.php");
				return FALSE;
			}
		}

		$editalAlterar->setUsuario($usuarioModelo);
		$editalAlterar->setData(date("Y-m-d H:i:s"));
		$editalDAO->alterar($editalAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O EDITAL " . strtoupper($editalAlterar->getTitulo()) . " (" . $editalAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Edital alterado com sucesso.";
		header("Location: ../sgc/alterar_edital.php?id=" . $editalAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o edital. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_edital.php");
		return FALSE;
	}
?>