<?php
	/**
	 * Controlador de alteração de boletim
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("boletim.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isBoletim() == FALSE || !isset($_SESSION["boletimAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$boletimAlterar = unserialize($_SESSION["boletimAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $boletimAlterar->setTitulo($_POST["inputTitulo"]);
		$inputArquivo = $boletimAlterar->hashArquivo($_FILES["inputArquivo"]["name"]);
		$inputImagem = $boletimAlterar->hashImagem($_FILES["inputImagem"]["name"], !empty($_POST["inputLimpaImagem"]));

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar o boletim. Verifique o título.";
			$_SESSION["boletimAlterar"] = serialize($boletimAlterar);
			header("Location: ../sgc/alterar_boletim.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["boletimAlterar"] = serialize($boletimAlterar);
				header("Location: ../sgc/alterar_boletim.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $boletimAlterar->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. Verifique o arquivo.";
				$_SESSION["boletimAlterar"] = serialize($boletimAlterar);
				header("Location: ../sgc/alterar_boletim.php");
				return FALSE;
			}
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["boletimAlterar"] = serialize($boletimAlterar);
				header("Location: ../sgc/alterar_boletim.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $boletimAlterar->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível alterar o boletim. Verifique a imagem.";
				$_SESSION["boletimAlterar"] = serialize($boletimAlterar);
				header("Location: ../sgc/alterar_boletim.php");
				return FALSE;
			}
		}

		$boletimAlterar->setUsuario($usuarioModelo);
		$boletimAlterar->setData(date("Y-m-d H:i:s"));
		$boletimDAO->alterar($boletimAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O BOLETIM " . strtoupper($boletimAlterar->getTitulo()) . " (" . $boletimAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Boletim alterado com sucesso.";
		header("Location: ../sgc/alterar_boletim.php?id=" . $boletimAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o boletim. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_boletim.php");
		return FALSE;
	}
?>
