<?php
	/**
	 * Controlador de recuperação de evento
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("evento.php");
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isEvento() == FALSE) {
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
				$_SESSION["tuplas"] = $eventoDAO->procurarId($_POST["input"]);
				if($_SESSION["tuplas"] && $_SESSION["tuplas"]->isStatus() == TRUE) {
					$_SESSION["tuplas"] = serialize([$_SESSION["tuplas"]]);
					header("Location: ../sgc/eventos.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum evento com o ID informado.";
					header("Location: ../sgc/eventos.php");
					return FALSE;
				}
			}
			else {
				$_SESSION["resposta"] = "Não foi possível consultar o evento. O número informado é inválido.";
				header("Location: ../sgc/eventos.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "titulo") == 0) { // INFORMOU O TÍTULO
			$_SESSION["tuplas"] = $eventoDAO->procurarTitulo($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/eventos.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum evento com o título informado.";
					header("Location: ../sgc/eventos.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum evento com o título informado.";
				header("Location: ../sgc/eventos.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "descricao") == 0) { // INFORMOU A DESCRIÇÃO
			$_SESSION["tuplas"] = $eventoDAO->procurarDescricao($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/eventos.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum evento com a descrição informada.";
					header("Location: ../sgc/eventos.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum evento com a descrição informada.";
				header("Location: ../sgc/eventos.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "data") == 0) { // INFORMOU A DATA
			$_SESSION["tuplas"] = $eventoDAO->procurarData($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/eventos.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum evento com a data informada.";
					header("Location: ../sgc/eventos.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum evento com a data informada.";
				header("Location: ../sgc/eventos.php");
				return FALSE;
			}
		}
	}
?>