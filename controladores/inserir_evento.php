<?php
	/**
	 * Controlador de inserção de evento
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
	}
	else {
		header("Location: ../erro.html");
		return FALSE;
	}

	if(!empty($_POST["inputTitulo"]) && !empty($_FILES["inputImagens"]["name"])) { // TODOS OS DADOS ESTÃO CORRETOS
		$eventoInserir = new Evento();
		$inputTitulo = $eventoInserir->setTitulo($_POST["inputTitulo"]);
		$inputDescricao = $eventoInserir->setDescricao($_POST["inputDescricao"]);
		$imagens = array();
		$quantidade = count($_FILES["inputImagens"]["name"]);
		for($i = 0; $i < $quantidade; $i++) {
			if($_FILES["inputImagens"]["error"][$i] == 0) {
				array_push($imagens, ["IMAGEM" => $_FILES["inputImagens"]["name"][$i], "EVENTO" => 0, "STATUS" => TRUE]);
			}
		}
		$inputImagens = $eventoInserir->setImagens($imagens);

		if(!$inputTitulo) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o evento. Verifique o título.";
			$_SESSION["eventoInserir"] = serialize($eventoInserir);
			header("Location: ../sgc/inserir_evento.php");
			return FALSE;
		}
		if(!$inputDescricao) {
			$_SESSION["resposta"] = "Não foi possível cadastrar o evento. Verifique a descrição.";
			$_SESSION["eventoInserir"] = serialize($eventoInserir);
			header("Location: ../sgc/inserir_evento.php");
			return FALSE;
		}
		if($inputImagens) {
			$eventoInserir->hashDiretorio(preg_replace("/[^A-Za-z0-9\-]/", "", str_replace(" ", "-", $_POST["inputTitulo"])));
			mkdir("../" . $eventoInserir->getDiretorio());
			for($i = 0; $i < $quantidade; $i++) {
				if($_FILES["inputImagens"]["error"][$i] == 0) {
					move_uploaded_file($_FILES["inputImagens"]["tmp_name"][$i], "../" . $eventoInserir->getDiretorio() . "/" . $eventoInserir->getImagens()[$i]["IMAGEM"]);
				}
			}
		}
		else {
			$_SESSION["resposta"] = "Não foi possível cadastrar o evento. Verifique as imagens.";
			$_SESSION["eventoInserir"] = serialize($eventoInserir);
			header("Location: ../sgc/inserir_evento.php");
			return FALSE;
		}

		$eventoInserir->setUsuario($usuarioModelo);
		$eventoInserir->setData(date("Y-m-d H:i:s"));
		$eventoInserir->setStatus(TRUE);
		$eventoDAO->inserir($eventoInserir);
		$eventoInserir->setId($eventoDAO->procurarUltimo()->getId());
		foreach($eventoInserir->getImagens() as $imagem) {
			$imagemDAO->inserir($imagem);
		}
		$registroModelo->setDescricao(strtoupper($usuarioModelo->getNome()) . " CADASTROU O EVENTO " . strtoupper($eventoInserir->getTitulo()) . " (" . $eventoInserir->getId() . ") . ");
		$registroModelo->setIp($_SERVER["REMOTE_ADDR"]);
		$registroModelo->setData(date("Y-m-d H:i:s"));
		$registroDAO->inserir($registroModelo);
		$_SESSION["resposta"] = "Evento cadastrado com sucesso.";
		header("Location: ../sgc/inserir_evento.php");
		return TRUE;
	}
	else {
		$_SESSION["resposta"] = "Não foi possível cadastrar o evento. Preencha todos os campos obrigatórios.";
		header("Location: ../sgc/inserir_evento.php");
		return FALSE;
	}
?>