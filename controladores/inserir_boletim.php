<?php
	/**
	 * Controlador de inserção de boletim
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
		if($usuarioModelo->getPermissao()->isBoletim() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_FILES["inputArquivo"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$boletimInserir = new Boletim();
		$inputTitulo = $boletimInserir->setTitulo($_POST["inputTitulo"]);
		$inputArquivo = $boletimInserir->hashArquivo($_FILES["inputArquivo"]["name"]);
		$inputImagem = $boletimInserir->hashImagem($_FILES["inputImagem"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. Verifique o título.";
			$_SESSION["boletimInserir"] = serialize($boletimInserir);
			header("Location: ../sgc/inserir_boletim.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["boletimInserir"] = serialize($boletimInserir);
				header("Location: ../sgc/inserir_boletim.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $boletimInserir->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. Verifique o arquivo.";
				$_SESSION["boletimInserir"] = serialize($boletimInserir);
				header("Location: ../sgc/inserir_boletim.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. Verifique o formato do arquivo.";
			$_SESSION["boletimInserir"] = serialize($boletimInserir);
			header("Location: ../sgc/inserir_boletim.php");
			return FALSE;
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["boletimInserir"] = serialize($boletimInserir);
				header("Location: ../sgc/inserir_boletim.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $boletimInserir->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. Verifique a imagem.";
				$_SESSION["boletimInserir"] = serialize($boletimInserir);
				header("Location: ../sgc/inserir_boletim.php");
				return FALSE;
			}
		}

		$boletimInserir->setUsuario($usuarioModelo);
		$boletimInserir->setData(date("Y-m-d H:i:s"));
		$boletimInserir->setStatus(TRUE);
		$boletimDAO->inserir($boletimInserir);
		$boletimInserir->setId($boletimDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O BOLETIM " . strtoupper($boletimInserir->getTitulo()) . " (" . $boletimInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Boletim cadastrado com sucesso.";
		header("Location: ../sgc/inserir_boletim.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o boletim. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_boletim.php");
		return FALSE;
	}
?>