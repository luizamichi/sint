<?php
	/**
	 * Controlador de alteração de senha
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
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputSenhaAnterior"]) && !empty($_POST["inputSenha"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputSenhaAnterior = (strcmp(md5($_POST["inputSenhaAnterior"]), $usuarioModelo->getSenha()) == 0);
		$inputSenhaNova = (md5($_POST["inputSenha"]) == $usuarioModelo->getSenha());
		$inputSenha = $usuarioModelo->setSenha($_POST["inputSenha"]);
		if(!$inputSenhaAnterior) { // SENHA ANTERIOR INCORRETA
			$_SESSION["resposta"] = "Não foi possível alterar a senha. Verifique a senha anterior.";
			header("Location: ../sgc/alterar_senha.php");
			return FALSE;
		}
		if(!$inputSenha) {
			$_SESSION["resposta"] = "Não foi possível alterar a senha. Verifique a nova senha.";
			header("Location: ../sgc/alterar_senha.php");
			return FALSE;
		}
		if($inputSenhaNova) { // NOVA SENHA IGUAL A ANTERIOR
			$_SESSION["resposta"] = "Não foi possível alterar a senha. A nova senha é igual a anterior.";
			header("Location: ../sgc/alterar_senha.php");
			return FALSE;
		}

		$forca_senha = 3;
		if(preg_match("/^[a-zA-Z]+$/", $_POST["inputSenha"]) || preg_match("/^\d+$/", $_POST["inputSenha"])) { // CONTÉM APENAS LETRAS OU APENAS NÚMEROS
			$forca_senha--;
		}
		if(!preg_match("/[^a-zA-Z\d]/", $_POST["inputSenha"])) { // NÃO CONTÉM CARACTERES ESPECIAIS
			$forca_senha--;
		}
		if(strcmp($usuarioModelo->getLogin(), $_POST["inputSenha"]) == 0) { // SENHA IGUAL AO LOGIN
			$forca_senha--;
		}
		switch($forca_senha) {
			case 3:
				$_SESSION["senha"] = "Nível excelente";
				break;
			case 2:
				$_SESSION["senha"] = "Nível bom";
				break;
			case 1:
				$_SESSION["senha"] = "Nível médio";
				break;
			case 0:
				$_SESSION["senha"] = "Nível baixo";
				break;
			default:
				$_SESSION["senha"] = "Nível indeterminado";
		}

		$usuarioModelo->hashSenha($_POST["inputSenha"]);
		$usuarioDAO->alterar($usuarioModelo);
		$registroModelo->setDescricao($usuarioModelo->getNome() . " ALTEROU A SUA SENHA.");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Senha alterada com sucesso.";
		$_SESSION["usuarioModelo"] = serialize($usuarioModelo);
		header("Location: ../sgc/alterar_senha.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar a senha. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_senha.php");
		return FALSE;
	}
?>