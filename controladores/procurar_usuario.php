<?php
	/**
	 * Controlador de recuperação de usuário
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("permissao.php");
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isUsuario() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["input"]) && !empty($_POST["inputSelecao"])) { // TODOS OS DADOS ESTÃO CORRETOS
		if(strcmp($_POST["inputSelecao"], "id") == 0) { // INFORMOU O ID
			if(is_numeric($_POST["input"]) && floor($_POST["input"]) > 0) {
				$_SESSION["tuplas"] = $usuarioDAO->procurarId($_POST["input"]);
				if($_SESSION["tuplas"] && $_SESSION["tuplas"]->isStatus() == TRUE) {
					$_SESSION["tuplas"] = serialize([$_SESSION["tuplas"]]);
					header("Location: ../sgc/usuarios.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com o ID informado.";
					header("Location: ../sgc/usuarios.php");
					return FALSE;
				}
			}
			else {
				$_SESSION["resposta"] = "Não foi possível consultar o usuário. O número informado é inválido.";
				header("Location: ../sgc/usuarios.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "nome") == 0) { // INFORMOU O NOME
			$_SESSION["tuplas"] = $usuarioDAO->procurarNome($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/usuarios.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com o nome informado.";
					header("Location: ../sgc/usuarios.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com o nome informado.";
				header("Location: ../sgc/usuarios.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "email") == 0) { // INFORMOU O E-MAIL
			$_SESSION["tuplas"] = $usuarioDAO->procurarEmail($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/usuarios.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com o e-mail informado.";
					header("Location: ../sgc/usuarios.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com o e-mail informado.";
				header("Location: ../sgc/usuarios.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "login") == 0) { // INFORMOU O LOGIN
			$_SESSION["tuplas"] = $usuarioDAO->procurarLogin($_POST["input"]);
			if($_SESSION["tuplas"]) {
				foreach($_SESSION["tuplas"] as $tupla) {
					if($tupla->isStatus() == FALSE) {
						$status = array_search($tupla, $_SESSION["tuplas"]);
						unset($_SESSION["tuplas"][$status]);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/usuarios.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com o login informado.";
					header("Location: ../sgc/usuarios.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com o login informado.";
				header("Location: ../sgc/usuarios.php");
				return FALSE;
			}
		}
		else if(strcmp($_POST["inputSelecao"], "permissao") == 0) { // INFORMOU A PERMISSÃO
			switch($_POST["input"]) {
				case "1":
					$permissoes = $permissaoDAO->procurarBanner();
					break;
				case "2":
					$permissoes = $permissaoDAO->procurarBoletim();
					break;
				case "3":
					$permissoes = $permissaoDAO->procurarConvencao();
					break;
				case "4":
					$permissoes = $permissaoDAO->procurarConvenio();
					break;
				case "5":
					$permissoes = $permissaoDAO->procurarEdital();
					break;
				case "6":
					$permissoes = $permissaoDAO->procurarEvento();
					break;
				case "7":
					$permissoes = $permissaoDAO->procurarFinanca();
					break;
				case "8":
					$permissoes = $permissaoDAO->procurarJornal();
					break;
				case "9":
					$permissoes = $permissaoDAO->procurarJuridico();
					break;
				case "10":
					$permissoes = $permissaoDAO->procurarNoticia();
					break;
				case "11":
					$permissoes = $permissaoDAO->procurarPodcast();
					break;
				case "12":
					$permissoes = $permissaoDAO->procurarRegistro();
					break;
				case "13":
					$permissoes = $permissaoDAO->procurarUsuario();
					break;
				default:
					$permissoes = array();
					break;
			}
			if($permissoes) {
				$_SESSION["tuplas"] = array();
				foreach($permissoes as $tupla) {
					$usuario = $usuarioDAO->procurarPermissao($tupla);
					if($usuario->isStatus()) {
						array_push($_SESSION["tuplas"], $usuario);
					}
				}
				if(count($_SESSION["tuplas"]) > 0) {
					$_SESSION["tuplas"] = serialize($_SESSION["tuplas"]);
					header("Location: ../sgc/usuarios.php");
					return TRUE;
				}
				else {
					unset($_SESSION["tuplas"]);
					$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com a permissão informada.";
					header("Location: ../sgc/usuarios.php");
					return FALSE;
				}
			}
			else {
				unset($_SESSION["tuplas"]);
				$_SESSION["resposta"] = "Não foi encontrado nenhum usuário com a permissão informada.";
				header("Location: ../sgc/usuarios.php");
				return FALSE;
			}
		}
	}
?>