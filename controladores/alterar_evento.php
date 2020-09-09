<?php
	/**
	 * Controlador de alteração de evento
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("evento.php");
	include_once("imagem.php");
	include_once("registro.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isEvento() == FALSE) {
			header("Location: ../erro.html");
			return FALSE;
		}
		$eventoAlterar = unserialize($_SESSION["eventoAlterar"]);
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$inputTitulo = $eventoAlterar->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $eventoAlterar->setDescricao($_POST["inputDescricao"]);
		$imagens = array();
		$quantidade = count($_FILES["inputImagens"]["name"]);
		for($i = 0; $i < $quantidade; $i++) {
			if($_FILES["inputImagens"]["error"][$i] == 0) {
				array_push($imagens, ["IMAGEM" => $_FILES["inputImagens"]["name"][$i], "EVENTO" => $eventoAlterar->getId(), "STATUS" => TRUE]);
			}
		}
		$inputImagens = $eventoAlterar->setImagens(array_merge($eventoAlterar->getImagens(), $imagens));

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível alterar o evento. Verifique o título.";
			$_SESSION["eventoAlterar"] = serialize($eventoAlterar);
			header("Location: ../sgc/alterar_evento.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível alterar o evento. Verifique a descrição.";
			$_SESSION["eventoAlterar"] = serialize($eventoAlterar);
			header("Location: ../sgc/alterar_evento.php");
			return FALSE;
		}
		if($inputImagens) {
			for($i = 0; $i < $quantidade; $i++) {
				if($_FILES["inputImagens"]["error"][$i] == 0) {
					move_uploaded_file($_FILES["inputImagens"]["tmp_name"][$i], "../" . $eventoAlterar->getDiretorio() . "/" . $imagens[$i]["IMAGEM"]);
				}
			}
		}

		$eventoAlterar->setUsuario($usuarioModelo);
		$eventoAlterar->setData(date("Y-m-d H:i:s"));
		$eventoDAO->alterar($eventoAlterar);
		foreach($imagens as $imagem) {
			$imagemDAO->inserir($imagem);
		}
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " ALTEROU O EVENTO " . strtoupper($eventoAlterar->getTitulo()) . " (" . $eventoAlterar->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Evento alterado com sucesso.";
		header("Location: ../sgc/alterar_evento.php?id=" . $eventoAlterar->getId());
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível alterar o evento. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/alterar_evento.php");
		return FALSE;
	}
?>