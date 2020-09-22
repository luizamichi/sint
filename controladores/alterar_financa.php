<?php
	/**
	 * Controlador de alteração de finança
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
		if($usuarioModelo->getPermissao()->isFinanca() == FALSE || !isset($_SESSION["financaAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$financaAlterar = unserialize($_SESSION["financaAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputPeriodo"]) && !empty($_POST["inputAno"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputPeriodo = $financaAlterar->setPeriodo($_POST["inputPeriodo"] . " de " . $_POST["inputAno"]);
		$inputArquivo = $financaAlterar->hashArquivo($_FILES["inputArquivo"]["name"]);

		if(!$inputPeriodo) {
			$_SESSION["resposta"] = "Não foi possível alterar a finança. Verifique o período.";
			$_SESSION["financaAlterar"] = serialize($financaAlterar);
			header("Location: ../sgc/alterar_financa.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar a finança. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar a finança. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar a finança. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["financaAlterar"] = serialize($financaAlterar);
				header("Location: ../sgc/alterar_financa.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $financaAlterar->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível alterar a finança. Verifique o arquivo.";
				$_SESSION["financaAlterar"] = serialize($financaAlterar);
				header("Location: ../sgc/alterar_financa.php");
				return FALSE;
			}
		}

		$financaAlterar->setUsuario($usuarioModelo);
		$financaAlterar->setData(date("Y-m-d H:i:s"));
		$financaDAO->alterar($financaAlterar);
		if(strcmp(substr($financaAlterar->getFlag(), -2), ".1") == 0) {
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU A FINANÇA DO " . strtoupper($financaAlterar->getPeriodo()) . " (" . $financaAlterar->getId() . ") . ");
		}
		else {
			$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU A FINANÇA DE " . strtoupper($financaAlterar->getPeriodo()) . " (" . $financaAlterar->getId() . ") . ");
		}
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Finança alterada com sucesso.";
		header("Location: ../sgc/alterar_financa.php?id=" . $financaAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar a finança. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_financa.php");
		return FALSE;
	}
?>