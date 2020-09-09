<?php
	/**
	 * Controlador de inserção de notícia
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("noticia.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isNoticia() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_POST["inputTexto"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$noticiaInserir = new Noticia();
		$inputTitulo = $noticiaInserir->setTitulo($_POST["inputTitulo"]);
		$inputSubtitulo = $noticiaInserir->setSubtitulo($_POST["inputSubtitulo"]);
		$inputTexto = $noticiaInserir->setTexto($_POST["inputTexto"]);
		$inputImagem = $noticiaInserir->hashImagem($_FILES["inputImagem"]["name"]);
		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. Verifique o título.";
			$_SESSION["noticiaInserir"] = serialize($noticiaInserir);
			header("Location: ../sgc/inserir_noticia.php");
			return FALSE;
		}
		if(!$inputSubtitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. Verifique o subtítulo.";
			$_SESSION["noticiaInserir"] = serialize($noticiaInserir);
			header("Location: ../sgc/inserir_noticia.php");
			return FALSE;
		}
		if(!$inputTexto) {
			$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. Verifique o texto.";
			$_SESSION["noticiaInserir"] = serialize($noticiaInserir);
			header("Location: ../sgc/inserir_noticia.php");
			return FALSE;
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["noticiaInserir"] = serialize($noticiaInserir);
				header("Location: ../sgc/inserir_noticia.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $noticiaInserir->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. Verifique a imagem.";
				$_SESSION["noticiaInserir"] = serialize($noticiaInserir);
				header("Location: ../sgc/inserir_noticia.php");
				return FALSE;
			}
		}

		$noticiaInserir->setUsuario($usuarioModelo);
		$noticiaInserir->setData(date("Y-m-d H:i:s"));
		$noticiaInserir->setStatus(TRUE);
		$noticiaDAO->inserir($noticiaInserir);
		$noticiaInserir->setId($noticiaDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU A NOTÍCIA " . strtoupper($noticiaInserir->getTitulo()) . " (" . $noticiaInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Notícia cadastrada com sucesso.";
		header("Location: ../sgc/inserir_noticia.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar a notícia. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_noticia.php");
		return FALSE;
	}
?>