<?php
	/**
	 * Controlador de inserção de convênio
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("convenio.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isConvenio() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_POST["inputDescricao"]) && !empty($_POST["inputCidade"]) && !empty($_FILES["inputImagem"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$convenioInserir = new Convenio();
		$inputTitulo = $convenioInserir->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $convenioInserir->setDescricao($_POST["inputDescricao"]);
		$inputCidade = $convenioInserir->setCidade($_POST["inputCidade"]);
		$inputTelefone = $convenioInserir->setTelefone($_POST["inputTelefone"]);
		$inputCelular = $convenioInserir->setCelular($_POST["inputCelular"]);
		$inputSite = $convenioInserir->setSite($_POST["inputSite"]);
		$inputEmail = $convenioInserir->setEmail($_POST["inputEmail"]);
		$inputImagem = $convenioInserir->hashImagem($_FILES["inputImagem"]["name"]);
		$inputArquivo = $convenioInserir->hashArquivo($_FILES["inputArquivo"]["name"]);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique o título.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique a descrição.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}
		if(!$inputCidade) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique a cidade.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}
		if(!$inputTelefone) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique o telefone.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}
		if(!$inputCelular) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique o celular.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}
		if(!$inputSite) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique o site.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}
		if(!$inputEmail) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique o e-mail.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["convenioInserir"] = serialize($convenioInserir);
				header("Location: ../sgc/inserir_convenio.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $convenioInserir->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique o arquivo.";
				$_SESSION["convenioInserir"] = serialize($convenioInserir);
				header("Location: ../sgc/inserir_convenio.php");
				return FALSE;
			}
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["convenioInserir"] = serialize($convenioInserir);
				header("Location: ../sgc/inserir_convenio.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $convenioInserir->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique a imagem.";
				$_SESSION["convenioInserir"] = serialize($convenioInserir);
				header("Location: ../sgc/inserir_convenio.php");
				return FALSE;
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Verifique o formato da imagem.";
			$_SESSION["convenioInserir"] = serialize($convenioInserir);
			header("Location: ../sgc/inserir_convenio.php");
			return FALSE;
		}

		$convenioInserir->setUsuario($usuarioModelo);
		$convenioInserir->setData(date("Y-m-d H:i:s"));
		$convenioInserir->setStatus(TRUE);
		$convenioDAO->inserir($convenioInserir);
		$convenioInserir->setId($convenioDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O CONVÊNIO " . strtoupper($convenioInserir->getTitulo()) . " (" . $convenioInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Convênio cadastrado com sucesso.";
		header("Location: ../sgc/inserir_convenio.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o convênio. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_convenio.php");
		return FALSE;
	}
?>