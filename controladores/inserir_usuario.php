<?php
	/**
	 * Controlador de inserção de usuário
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
		if($usuarioModelo->getPermissao()->isUsuario() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
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
		$usuarioInserir = new Usuario();
		$inputNome = $usuarioInserir->setNome($_POST["inputNome"]);
		$inputEmail = $usuarioInserir->setEmail($_POST["inputEmail"]);
		$inputLogin = $usuarioInserir->setLogin($_POST["inputLogin"]);
		$inputSenha = $usuarioInserir->setSenha($_POST["inputSenha"]);
		$usuarioInserir->setPermissao($permissaoModelo);

		if(!$inputNome) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o usuário. Verifique o nome.";
			$_SESSION["usuarioInserir"] = serialize($usuarioInserir);
			header("Location: ../sgc/inserir_usuario.php");
			return FALSE;
		}
		if(!$inputEmail) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o usuário. Verifique o e-mail.";
			$_SESSION["usuarioInserir"] = serialize($usuarioInserir);
			header("Location: ../sgc/inserir_usuario.php");
			return FALSE;
		}
		if(!$inputLogin) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o usuário. Verifique o login.";
			$_SESSION["usuarioInserir"] = serialize($usuarioInserir);
			header("Location: ../sgc/inserir_usuario.php");
			return FALSE;
		}
		if(!$inputSenha) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o usuário. Verifique a senha.";
			$_SESSION["usuarioInserir"] = serialize($usuarioInserir);
			header("Location: ../sgc/inserir_usuario.php");
			return FALSE;
		}
		if(!isset($cargo)) { // NENHUMA PERMISSÃO FOI ATRIBUÍDA
			$_SESSION["resposta"] = "Não foi possível cadastrar o usuário. Selecione alguma permissão de acesso.";
			$_SESSION["usuarioInserir"] = serialize($usuarioInserir);
			header("Location: ../sgc/inserir_usuario.php");
			return FALSE;
		}

		$usuarioLogin = $usuarioDAO->procurarUsuario($usuarioInserir->getLogin());
		if($usuarioLogin && $usuarioLogin->getId() != $usuarioInserir->getId()) { // O LOGIN PERTENCE A OUTRO USUÁRIO
			$_SESSION["resposta"] = "Não foi possível inserir o usuário. O login pertence a outro usuário.";
			$_SESSION["usuarioInserir"] = serialize($usuarioInserir);
			header("Location: ../sgc/inserir_usuario.php");
			return FALSE;
		}

		$usuarioInserir->hashSenha($_POST["inputSenha"]);
		$usuarioInserir->setStatus(TRUE);
		$permissaoDAO->inserir($permissaoModelo);
		$usuarioInserir->setPermissao($permissaoDAO->procurarUltimo());
		$usuarioDAO->inserir($usuarioInserir);
		$usuarioInserir->setId($permissaoDAO->procurarUltimo()->getId());
		$registroModelo->setDescricao($usuarioModelo->getNome() . " CADASTROU O USUÁRIO " . $usuarioInserir->getNome() . " (" . $usuarioInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Usuário cadastrado com sucesso.";
		header("Location: ../sgc/inserir_usuario.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o usuário. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_usuario.php");
		return FALSE;
	}
?>