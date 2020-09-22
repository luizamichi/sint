<?php
	/**
	 * Controlador de recuperação de notícia
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("noticia.php");
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isConvencao() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_POST["input"]) && isset($_POST["inputSelecao"])) { // TODOS OS DADOS ESTÃO CORRETOS
		if(strcmp($_POST["inputSelecao"], "id") == 0) { // INFORMOU O ID
			if(is_numeric($_POST["input"]) && floor($_POST["input"]) > 0) {
				$_SESSION["tuplas"] = $noticiaDAO->procurarId($_POST["input"]);
				if($_SESSION["tuplas"] && $_SESSION["tuplas"]->isStatus() == TRUE) {
					$_SESSION["tuplas"] = serialize([$_SESSION["tuplas"]]);
					header("Location: ../sgc/noticias.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com o ID informado.";
					header("Location: ../sgc/noticias.php");
					return FALSE;
				}
			}
			else {
				$_SESSION["resposta"] = "Não foi possível consultar a notícia. O número informado é inválido.";
				header("Location: ../sgc/noticias.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "titulo") == 0) { // INFORMOU O TÍTULO
			$_SESSION["tuplas"] = $noticiaDAO->procurarTitulo($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/noticias.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com o título informado.";
					header("Location: ../sgc/noticias.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com o título informado.";
				header("Location: ../sgc/noticias.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "subtitulo") == 0) { // INFORMOU O SUBTÍTULO
			$_SESSION["tuplas"] = $noticiaDAO->procurarSubtitulo($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/noticias.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com o subtítulo informado.";
					header("Location: ../sgc/noticias.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com o subtítulo informado.";
				header("Location: ../sgc/noticias.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "texto") == 0) { // INFORMOU O TEXTO
			$_SESSION["tuplas"] = $noticiaDAO->procurarTexto($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/noticias.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com o texto informado.";
					header("Location: ../sgc/noticias.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com o texto informado.";
				header("Location: ../sgc/noticias.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "imagem") == 0) { // INFORMOU A IMAGEM
			$_SESSION["tuplas"] = $noticiaDAO->procurarImagem($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/noticias.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com a imagem informada.";
					header("Location: ../sgc/noticias.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com a imagem informada.";
				header("Location: ../sgc/noticias.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "data") == 0) { // INFORMOU A DATA
			$_SESSION["tuplas"] = $noticiaDAO->procurarData($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/noticias.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com a data informada.";
					header("Location: ../sgc/noticias.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma notícia com a data informada.";
				header("Location: ../sgc/noticias.php");
				return FALSE;
			}
		}
	}
?>