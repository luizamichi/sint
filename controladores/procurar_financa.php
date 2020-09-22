<?php
	/**
	 * Controlador de recuperação de finança
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("financa.php");
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isFinanca() == FALSE) {
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
				$_SESSION["tuplas"] = $financaDAO->procurarId($_POST["input"]);
				if($_SESSION["tuplas"] && $_SESSION["tuplas"]->isStatus() == TRUE) {
					$_SESSION["tuplas"] = serialize([$_SESSION["tuplas"]]);
					header("Location: ../sgc/financas.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma finança com o ID informado.";
					header("Location: ../sgc/financas.php");
					return FALSE;
				}
			}
			else {
				$_SESSION["resposta"] = "Não foi possível consultar a finança. O número informado é inválido.";
				header("Location: ../sgc/financas.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "periodo") == 0) { // INFORMOU O PERÍODO
			$_SESSION["tuplas"] = $financaDAO->procurarPeriodo($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/financas.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma finança com o período informado.";
					header("Location: ../sgc/financas.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma finança com o período informado.";
				header("Location: ../sgc/financas.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "arquivo") == 0) { // INFORMOU O ARQUIVO
			$_SESSION["tuplas"] = $financaDAO->procurarArquivo($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/financas.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma finança com o arquivo informado.";
					header("Location: ../sgc/financas.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma finança com o arquivo informado.";
				header("Location: ../sgc/financas.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "data") == 0) { // INFORMOU A DATA
			$_SESSION["tuplas"] = $financaDAO->procurarData($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/financas.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrada nenhuma finança com a data informada.";
					header("Location: ../sgc/financas.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrada nenhuma finança com a data informada.";
				header("Location: ../sgc/financas.php");
				return FALSE;
			}
		}
	}
?>