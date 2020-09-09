<?php
	/**
	 * Controlador de alteração de convênio
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
		if($usuarioModelo->getPermissao()->isConvenio() == FALSE || !isset($_SESSION["convenioAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$convenioAlterar = unserialize($_SESSION["convenioAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_POST["inputDescricao"]) && !empty($_POST["inputCidade"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $convenioAlterar->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $convenioAlterar->setDescricao($_POST["inputDescricao"]);
		$inputCidade = $convenioAlterar->setCidade($_POST["inputCidade"]);
		$inputTelefone = $convenioAlterar->setTelefone($_POST["inputTelefone"]);
		$inputCelular = $convenioAlterar->setCelular($_POST["inputCelular"]);
		$inputSite = $convenioAlterar->setSite($_POST["inputSite"]);
		$inputEmail = $convenioAlterar->setEmail($_POST["inputEmail"]);
		$inputImagem = $convenioAlterar->hashImagem($_FILES["inputImagem"]["name"]);
		$inputArquivo = $convenioAlterar->hashArquivo($_FILES["inputArquivo"]["name"], !empty($_POST["inputLimpaArquivo"]));

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique o título.";
			$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
			header("Location: ../sgc/alterar_convenio.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique a descrição.";
			$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
			header("Location: ../sgc/alterar_convenio.php");
			return FALSE;
		}
		if(!$inputCidade) {
			$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique a cidade.";
			$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
			header("Location: ../sgc/alterar_convenio.php");
			return FALSE;
		}
		if(!$inputTelefone) {
			$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique o telefone.";
			$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
			header("Location: ../sgc/alterar_convenio.php");
			return FALSE;
		}
		if(!$inputCelular) {
			$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique o celular.";
			$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
			header("Location: ../sgc/alterar_convenio.php");
			return FALSE;
		}
		if(!$inputSite) {
			$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique o site.";
			$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
			header("Location: ../sgc/alterar_convenio.php");
			return FALSE;
		}
		if(!$inputEmail) {
			$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique o e-mail.";
			$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
			header("Location: ../sgc/alterar_convenio.php");
			return FALSE;
		}
		if($inputArquivo) {
			if($_FILES["inputArquivo"]["error"] == 1 || $_FILES["inputArquivo"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. O arquivo excede o tamanho máximo de 3MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. O envio do arquivo foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputArquivo"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. Nenhum arquivo foi enviado.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
				header("Location: ../sgc/alterar_convenio.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputArquivo"]["tmp_name"], "../" . $convenioAlterar->getArquivo())) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique o arquivo.";
				$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
				header("Location: ../sgc/alterar_convenio.php");
				return FALSE;
			}
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
				header("Location: ../sgc/alterar_convenio.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $convenioAlterar->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível alterar o convênio. Verifique a imagem.";
				$_SESSION["convenioAlterar"] = serialize($convenioAlterar);
				header("Location: ../sgc/alterar_convenio.php");
				return FALSE;
			}
		}

		$convenioAlterar->setUsuario($usuarioModelo);
		$convenioAlterar->setData(date("Y-m-d H:i:s"));
		$convenioDAO->alterar($convenioAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O CONVÊNIO " . strtoupper($convenioAlterar->getTitulo()) . " (" . $convenioAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Convênio alterado com sucesso.";
		header("Location: ../sgc/alterar_convenio.php?id=" . $convenioAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o convênio. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_convenio.php");
		return FALSE;
	}
?>