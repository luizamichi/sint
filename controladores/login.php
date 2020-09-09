<?php
	/**
	 * Controlador de login (autenticação)
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("registro.php");
	include_once("usuario.php");
	if(isset($_POST["inputLogin"]) && isset($_POST["inputSenha"])) {
		$usuarioModelo = $usuarioDAO->procurarUsuario($_POST["inputLogin"]);
		if($usuarioModelo && strcmp(md5($_POST["inputSenha"]), $usuarioModelo->getSenha()) == 0 && $usuarioModelo->isStatus() == TRUE) {
			if(session_status() !== PHP_SESSION_ACTIVE) {
				session_start();
			}
			$_SESSION["usuarioModelo"] = serialize($usuarioModelo);
			$_SESSION["tema"] = "Claro";
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
			$registroModelo->setDescricao($usuarioModelo->getNome() . " ENTROU.");
			$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
			$registroModelo->setData(date("Y-m-d H:i:s"));
			if($usuarioModelo->getPermissao()->isDiretorio() == FALSE || $usuarioModelo->getPermissao()->isTabela() == FALSE) {
				$registroDAO->inserir($registroModelo);
			}
			echo "<script>
				alert('Login efetuado com sucesso!');
				location.href = '../sgc/index.php';
				</script>";
			return TRUE;
		}
		else {
			echo "<script>
				alert('Dados inválidos, insira corretamente!');
				location.href = '../sgc.php';
				</script>";
			return FALSE;
		}
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}
?>