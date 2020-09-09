<?php
	/**
	 * Controlador de alteração de usuário
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("permissao.php");
	include_once("registro.php");
	include_once("usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isUsuario() == FALSE || !isset($_SESSION["usuarioAlterar"])) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$usuarioAlterar = unserialize($_SESSION["usuarioAlterar"]);
		$permissaoModelo->setId($usuarioAlterar->getPermissao()->getId());
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(isset($_POST["inputBanner"])) {
		$permissaoModelo->setBanner(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputBoletim"])) {
		$permissaoModelo->setBoletim(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputConvencao"])) {
		$permissaoModelo->setConvencao(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputConvenio"])) {
		$permissaoModelo->setConvenio(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputEdital"])) {
		$permissaoModelo->setEdital(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputEvento"])) {
		$permissaoModelo->setEvento(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputFinanca"])) {
		$permissaoModelo->setFinanca(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputJornal"])) {
		$permissaoModelo->setJornal(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputJuridico"])) {
		$permissaoModelo->setJuridico(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputNoticia"])) {
		$permissaoModelo->setNoticia(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputPodcast"])) {
		$permissaoModelo->setPodcast(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputRegistro"])) {
		$permissaoModelo->setRegistro(TRUE);
		$cargo = TRUE;
	}
	if(isset($_POST["inputUsuario"])) {
		$permissaoModelo->setUsuario(TRUE);
		$cargo = TRUE;
	}

	if(!empty($_POST["inputNome"]) && !empty($_POST["inputEmail"]) && !empty($_POST["inputLogin"]) && !empty($_POST["inputSenha"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputNome = $usuarioAlterar->setNome($_POST["inputNome"]);
		$inputEmail = $usuarioAlterar->setEmail($_POST["inputEmail"]);
		$inputLogin = $usuarioAlterar->setLogin($_POST["inputLogin"]);
		$inputSenha = $usuarioAlterar->setSenha($_POST["inputSenha"]);
		$usuarioAlterar->setPermissao($permissaoModelo);

		if(!$inputNome) {
			$_SESSION["resposta"] = "Não foi possível alterar o usuário. Verifique o nome.";
			$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			header("Location: ../sgc/alterar_usuario.php");
			return FALSE;
		}
		if(!$inputEmail) {
			$_SESSION["resposta"] = "Não foi possível alterar o usuário. Verifique o e-mail.";
			$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			header("Location: ../sgc/alterar_usuario.php");
			return FALSE;
		}
		if(!$inputLogin) {
			$_SESSION["resposta"] = "Não foi possível alterar o usuário. Verifique o login.";
			$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			header("Location: ../sgc/alterar_usuario.php");
			return FALSE;
		}
		if(!$inputSenha) {
			$_SESSION["resposta"] = "Não foi possível alterar o usuário. Verifique a senha.";
			$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			header("Location: ../sgc/alterar_usuario.php");
			return FALSE;
		}
		if(!isset($cargo)) { // NENHUMA PERMISSÃO FOI ATRIBUÍDA
			$_SESSION["resposta"] = "Não foi possível alterar o usuário. Selecione alguma permissão de acesso.";
			$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			header("Location: ../sgc/alterar_usuario.php");
			return FALSE;
		}

		$usuarioLogin = $usuarioDAO->procurarUsuario($usuarioAlterar->getLogin());
		if($usuarioLogin && $usuarioLogin->getId() != $usuarioAlterar->getId()) { // O LOGIN PERTENCE A OUTRO USUÁRIO
			$_SESSION["resposta"] = "Não foi possível alterar o usuário. O login pertence a outro usuário.";
			$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			header("Location: ../sgc/alterar_usuario.php");
			return FALSE;
		}

		$usuarioAlterar->hashSenha($_POST["inputSenha"]);
		if(!$usuarioDAO->alterar($usuarioAlterar)) { // É NECESSÁRIO COLOCAR UMA SENHA DIFERENTE DA ANTERIOR
			$_SESSION["resposta"] = "Não foi possível alterar o usuário. A senha informada é atual.";
			$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			header("Location: ../sgc/alterar_usuario.php");
			return FALSE;
		}

		$permissaoDAO->alterar($permissaoModelo);
		$registroModelo->setDescricao($usuarioModelo->getNome() . " ALTEROU O USUÁRIO " . $usuarioAlterar->getNome() . " (" . $usuarioAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Usuário alterado com sucesso.";
		header("Location: ../sgc/alterar_usuario.php?id=" . $usuarioAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o usuário. Preencha todos os campos obrigatórios.";
		$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
		header("Location: ../sgc/alterar_usuario.php");
		return FALSE;
	}
?>