<?php
	/**
	 * Controlador de recuperação de registro
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("registro.php");
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->getRegistro() == FALSE) {
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
				$_SESSION["tuplas"] = $registroDAO->procurarId($_POST["input"]);
				if($_SESSION["tuplas"]) {
					$_SESSION["tuplas"] = serialize([$_SESSION["tuplas"]]);
					header("Location: ../sgc/registros.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum registro com o ID informado.";
					header("Location: ../sgc/registros.php");
					return FALSE;
				}
			}
			else {
				$_SESSION["resposta"] = "Não foi possível consultar o registro. O número informado é inválido.";
				header("Location: ../sgc/registros.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "descricao") == 0) { // INFORMOU A DESCRIÇÃO
			$_SESSION["tuplas"] = $registroDAO->procurarDescricao($_POST["input"]);
			if($_SESSION["tuplas"]) {
				$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
				header("Location: ../sgc/registros.php");
				return TRUE;
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum registro com a descrição informada.";
				header("Location: ../sgc/registros.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "data") == 0) { // INFORMOU A DATA
			$_SESSION["tuplas"] = $registroDAO->procurarData($_POST["input"]);
			if($_SESSION["tuplas"]) {
				$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
				header("Location: ../sgc/registros.php");
				return TRUE;
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum registro com a data informada.";
				header("Location: ../sgc/registros.php");
				return FALSE;
			}
		}
	}
?>