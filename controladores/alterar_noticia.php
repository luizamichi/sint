<?php
	/**
	 * Controlador de alteração de notícia
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
		if($usuarioModelo->getPermissao()->isNoticia() == FALSE || !isset($_SESSION["noticiaAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$noticiaAlterar = unserialize($_SESSION["noticiaAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_POST["inputTexto"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $noticiaAlterar->setTitulo($_POST["inputTitulo"]);
		$inputSubtitulo = $noticiaAlterar->setSubtitulo($_POST["inputSubtitulo"]);
		$inputTexto = $noticiaAlterar->setTexto($_POST["inputTexto"]);
		$inputImagem = $noticiaAlterar->hashImagem($_FILES["inputImagem"]["name"], !empty($_POST["inputLimpaImagem"]));

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar a notícia. Verifique o título.";
			$_SESSION["noticiaAlterar"] = serialize($noticiaAlterar);
			header("Location: ../sgc/alterar_noticia.php");
			return FALSE;
		}
		if(!$inputSubtitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar a notícia. Verifique o subtítulo.";
			$_SESSION["noticiaAlterar"] = serialize($noticiaAlterar);
			header("Location: ../sgc/alterar_noticia.php");
			return FALSE;
		}
		if(!$inputTexto) {
			$_SESSION["resposta"] = "Não foi possível alterar a notícia. Verifique o texto.";
			$_SESSION["noticiaAlterar"] = serialize($noticiaAlterar);
			header("Location: ../sgc/alterar_noticia.php");
			return FALSE;
		}
		if($inputImagem) {
			if($_FILES["inputImagem"]["error"] == 1 || $_FILES["inputImagem"]["error"] == 2) {
				$_SESSION["resposta"] = "Não foi possível alterar a notícia. A imagem excede o tamanho máximo de 1MB.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 3) {
				$_SESSION["resposta"] = "Não foi possível alterar a notícia. O envio da imagem foi feito parcialmente.";
				$erro = TRUE;
			}
			else if($_FILES["inputImagem"]["error"] == 4) {
				$_SESSION["resposta"] = "Não foi possível alterar a notícia. Nenhuma imagem foi enviada.";
				$erro = TRUE;
			}
			if(isset($erro)) {
				$_SESSION["noticiaAlterar"] = serialize($noticiaAlterar);
				header("Location: ../sgc/alterar_noticia.php");
				return FALSE;
			}
			if(!move_uploaded_file($_FILES["inputImagem"]["tmp_name"], "../" . $noticiaAlterar->getImagem())) {
				$_SESSION["resposta"] = "Não foi possível alterar a notícia. Verifique a imagem.";
				$_SESSION["noticiaAlterar"] = serialize($noticiaAlterar);
				header("Location: ../sgc/alterar_noticia.php");
				return FALSE;
			}
		}

		$noticiaAlterar->setUsuario($usuarioModelo);
		$noticiaAlterar->setData(date("Y-m-d H:i:s"));
		$noticiaDAO->alterar($noticiaAlterar);
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU A NOTÍCIA " . strtoupper($noticiaAlterar->getTitulo()) . " (" . $noticiaAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Notícia alterada com sucesso.";
		header("Location: ../sgc/alterar_noticia.php?id=" . $noticiaAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar a notícia. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_noticia.php");
		return FALSE;
	}
?>