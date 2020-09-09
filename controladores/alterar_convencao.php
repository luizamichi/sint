<?php
	/**
	 * Controlador de alteração de convenção
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
		if($usuarioModelo->getPermissao()->isConvencao() == FALSE || !isset($_SESSION["convencaoAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$convencaoAlterar = unserialize($_SESSION["convencaoAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && isset($_POST["inputTipo"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $convencaoAlterar->setTitulo($_POST["inputTitulo"]);
		$inputArquivo = $convencaoAlterar->hashArquivo($_FILES["inputArquivo"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar a convenção. Verifique o título.";
			$_SESSION["convencaoAlterar"] = serialize($convencaoAlterar);
			header("Location: ../sgc/alterar_convencao.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar a convenção. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar a convenção. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar a convenção. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["convencaoAlterar"] = serialize($convencaoAlterar);
				header("Location: ../sgc/alterar_convencao.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $convencaoAlterar->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível alterar a convenção. Verifique o arquivo.";
				$_SESSION["convencaoAlterar"] = serialize($convencaoAlterar);
				header("Location: ../sgc/alterar_convencao.php");
				return FALSE;
			}
		}

		$convencaoAlterar->setTipo((bool) $_POST["inputTipo"]);
		$convencaoAlterar->setUsuario($usuarioModelo);
		$convencaoAlterar->setData(date("Y-m-d H:i:s"));
		$convencaoDAO->alterar($convencaoAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU A CONVENÇÃO " . strtoupper($convencaoAlterar->getTitulo()) . " (" . $convencaoAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Convenção alterada com sucesso.";
		header("Location: ../sgc/alterar_convencao.php?id=" . $convencaoAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar a convenção. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_convencao.php");
		return FALSE;
	}
?>