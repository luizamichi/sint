<?php
	include_once("../controladores/acesso.php");
	include_once("../controladores/banner.php");
	include_once("../controladores/boletim.php");
	include_once("../controladores/convencao.php");
	include_once("../controladores/convenio.php");
	include_once("../controladores/edital.php");
	include_once("../controladores/evento.php");
	include_once("../controladores/financa.php");
	include_once("../controladores/imagem.php");
	include_once("../controladores/jornal.php");
	include_once("../controladores/juridico.php");
	include_once("../controladores/noticia.php");
	include_once("../controladores/permissao.php");
	include_once("../controladores/podcast.php");
	include_once("../controladores/registro.php");
	include_once("../controladores/usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isTabela() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}
		else {
			$acessos = $acessoDAO->listar();
			$banners = $bannerDAO->listar();
			$boletins = $boletimDAO->listar();
			$convencoes = $convencaoDAO->listar();
			$convenios = $convenioDAO->listar();
			$editais = $editalDAO->listar();
			$eventos = $eventoDAO->listar();
			$financas = $financaDAO->listar();
			$imagens = $imagemDAO->listar();
			usort($imagens, "comparar");
			$jornais = $jornalDAO->listar();
			$juridicos = $juridicoDAO->listar();
			$noticias = $noticiaDAO->listar();
			$permissoes = $permissaoDAO->listar();
			$podcasts = $podcastDAO->listar();
			$registros = $registroDAO->listar();
			$usuarios = $usuarioDAO->listar();
		}
	}

	function comparar(array $a, array $b) {
		return ($a["ID"] > $b["ID"]) ? -1 : 1;
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Sistema de Gerenciamento de Conteúdo</title>
	<link type="image/ico" rel="icon" href="../imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="../css/bootstrap.min.css" id="bootstrap-css">
	<?php if(strcmp($_SESSION["tema"], "Claro") == 0) { ?>
	<link type="text/css" rel="stylesheet" href="../css/sgc.css" id="sgc-css">
	<?php } else { ?>
	<link type="text/css" rel="stylesheet" href="../css/sgc-dark.css" id="sgc-css">
	<?php } ?>
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de visualização de tabelas">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "tabelas";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Tabelas</h1>
		<div class="col-sm">
			<div class="form-row align-items-center">
				<div class="col-sm-3 mt-3">
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<img src="../imagens/procurar.svg" alt="Procurar" width="20">
							</div>
						</div>
						<select class="custom-select mr-sm-2" id="input">
							<option selected value="acessos">Acessos</option>
							<option value="banners">Banners</option>
							<option value="boletins">Boletins</option>
							<option value="convencoes">Convençôes</option>
							<option value="convenios">Convênios</option>
							<option value="editais">Editais</option>
							<option value="eventos">Eventos</option>
							<option value="financas">Finanças</option>
							<option value="imagens">Imagens</option>
							<option value="jornais">Jornais</option>
							<option value="juridicos">Jurídicos</option>
							<option value="noticias">Notícias</option>
							<option value="podcasts">Podcasts</option>
							<option value="permissoes">Permissões</option>
							<option value="registros">Registros</option>
							<option value="usuarios">Usuários</option>
						</select>
					</div>
				</div>
			</div>

			<h3 class="h3 mt-5" id="acessos">Acessos</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">IP</th>
						<th scope="col">Data - Hora</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($acessos as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getIp() . "</td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="banners">Banners</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Imagem</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($banners as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td><a href=\"../" . $tupla->getImagem() . "\" title=\"" . substr($tupla->getImagem(), 16) . "\"><img class=\"cinza\" src=\"../imagens/" . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "png.svg" : "jpg.svg") . "\" alt=\"Documento JPG\" width=\"25\" height=\"25\"></a></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="boletins">Boletins</h3>
			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Arquivo</th>
						<th scope="col">Imagem</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($boletins as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td><a href=\"../" . $tupla->getArquivo() . "\" title=\"" . substr($tupla->getArquivo(), 17) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/pdf.svg\" alt=\"Documento PDF\" width=\"25\" height=\"25\"></a></td>";
								echo ($tupla->getImagem()) ? "<td><a href=\"../" . $tupla->getImagem() . "\" title=\"" . substr($tupla->getImagem(), 17) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/" . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "png.svg" : "jpg.svg") . "\" alt=\"Imagem " . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "PNG" : "JPG") . "\" width=\"25\" height=\"25\"></a></td>" : "<td></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="convencoes">Convenções</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Arquivo</th>
						<th scope="col">Tipo</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($convencoes as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td><a href=\"../" . $tupla->getArquivo() . "\" title=\"" . substr($tupla->getArquivo(), 8) . "\"><img class=\"cinza\" src=\"../imagens/pdf.svg\" alt=\"Documento PDF\" width=\"25\" height=\"25\"></a></td>";
								echo "<td>" . ($tupla->isTipo() ? "Vigente" : "Anterior") . "</td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="convenios">Convênios</h3>
			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Descrição</th>
						<th scope="col">Cidade</th>
						<th scope="col">Telefone</th>
						<th scope="col">Celular</th>
						<th scope="col">Site</th>
						<th scope="col">E-mail</th>
						<th scope="col">Imagem</th>
						<th scope="col">Arquivo</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($convenios as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td class=\"text-truncate\" style=\"max-width: 15vw;\">" . $tupla->getDescricao() . "</td>";
								echo "<td>" . $tupla->getCidade() . "</td>";
								echo "<td>" . preg_replace("/^([0-9]{2})([0-9]{4})([0-9]{4})$/", "($1) $2-$3", (string) $tupla->getTelefone()) . "</td>";
								echo "<td>" . preg_replace("/^([0-9]{2})([0-9]{5})([0-9]{4})$/", "($1) $2-$3", (string) $tupla->getCelular()) . "</td>";
								echo ($tupla->getSite()) ? "<td><a href=\"" . $tupla->getSite() . "\" title=\"" . $tupla->getSite() . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/web.svg\" alt=\"Website\" width=\"25\" height=\"25\"></a></td>" : "<td></td>";
								echo ($tupla->getEmail()) ? "<td><a href=\"mailto:" . $tupla->getEmail() . "\" title=\"" . $tupla->getEmail() . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/email.svg\" alt=\"E-mail\" width=\"25\" height=\"25\"></a></td>" : "<td></td>";
								echo "<td><a href=\"../" . $tupla->getImagem() . "\" title=\"" . substr($tupla->getImagem(), 18) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/" . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "png.svg" : "jpg.svg") . "\" alt=\"Imagem " . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "PNG" : "JPG") . "\" width=\"25\" height=\"25\"></a></td>";
								echo ($tupla->getArquivo()) ? "<td><a href=\"../" . $tupla->getArquivo() . "\" title=\"" . substr($tupla->getArquivo(), 18) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/pdf.svg\" alt=\"Documento PDF\" width=\"25\" height=\"25\"></a></td>" : "<td></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="editais">Editais</h3>
			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Descrição</th>
						<th scope="col">Imagem</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($editais as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td class=\"text-truncate\" style=\"max-width: 15vw;\">" . strip_tags((string) $tupla->getDescricao()) . "</td>";
								echo ($tupla->getImagem()) ? "<td><a href=\"../" . $tupla->getImagem() . "\" title=\"" . substr($tupla->getImagem(), 18) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/" . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "png.svg" : "jpg.svg") . "\" alt=\"Documento JPG\" width=\"25\" height=\"25\"></a></td>" : "<td></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="eventos">Eventos</h3>
			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Descrição</th>
						<th scope="col">Diretório</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($eventos as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\" id=\"evento" . $tupla->getId() . "\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td class=\"text-truncate\" style=\"max-width: 15vw;\">" . strip_tags((string) $tupla->getDescricao()) . "</td>";
								echo "<td><a href=\"../" . $tupla->getDiretorio() . "\" title=\"" . $tupla->getDiretorio() . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/diretorio.svg\" alt=\"Diretório\" width=\"25\" height=\"25\"></a></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="financas">Finanças</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Período</th>
						<th scope="col">Arquivo</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($financas as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getPeriodo() . "</td>";
								echo "<td><a href=\"../" . $tupla->getArquivo() . "\" title=\"" . substr($tupla->getArquivo(), 17) . "\"><img class=\"cinza\" src=\"../imagens/pdf.svg\" alt=\"Documento PDF\" width=\"25\" height=\"25\"></a></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="imagens">Imagens</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Imagem</th>
						<th scope="col">Evento</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($imagens as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla["ID"] . "</th>";
								echo "<td><a href=\"../" . $tupla["DIRETORIO"] . "/" . $tupla["IMAGEM"] . "\" title=\"" . $tupla["IMAGEM"] . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/" . (strcmp(strtolower(substr($tupla["IMAGEM"], -3)), "png") == 0 ? "png.svg" : "jpg.svg") . "\" alt=\"Imagem " . (strcmp(strtolower(substr($tupla["IMAGEM"], -3)), "png") == 0 ? "PNG" : "JPG") . "\" width=\"25\" height=\"25\"></a></td>";
								echo "<td><a class=\"text-dark\" href=\"#evento" . $tupla["EVENTO"] . "\">" . $tupla["EVENTO"] . "</a></td>";
								echo "<td>" . $tupla["STATUS"] . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="jornais">Jornais</h3>
			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Edição</th>
						<th scope="col">Arquivo</th>
						<th scope="col">Imagem</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($jornais as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td>" . $tupla->getEdicao() . "</td>";
								echo "<td><a href=\"../" . $tupla->getArquivo() . "\" title=\"" . substr($tupla->getArquivo(), 16) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/pdf.svg\" alt=\"Documento PDF\" width=\"25\" height=\"25\"></a></td>";
								echo ($tupla->getImagem()) ? "<td><a href=\"../" . $tupla->getImagem() . "\" title=\"" . substr($tupla->getImagem(), 18) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/" . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "png.svg" : "jpg.svg") . "\" alt=\"Documento JPG\" width=\"25\" height=\"25\"></a></td>" : "<td></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="juridicos">Jurídicos</h3>
			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Descrição</th>
						<th scope="col">Arquivo</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($juridicos as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td class=\"text-truncate\" style=\"max-width: 15vw;\">" . $tupla->getDescricao() . "</td>";
								echo "<td><a href=\"../" . $tupla->getArquivo() . "\" title=\"" . substr($tupla->getArquivo(), 16) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/pdf.svg\" alt=\"Documento PDF\" width=\"25\" height=\"25\"></a></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="noticias">Notícias</h3>
			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Subtítulo</th>
						<th scope="col">Texto</th>
						<th scope="col">Imagem</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($noticias as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td class=\"text-truncate\" style=\"max-width: 10vw;\">" . $tupla->getSubtitulo() . "</td>";
								echo "<td class=\"text-truncate\" style=\"max-width: 20vw;\">" . strip_tags($tupla->getTexto()) . "</td>";
								echo ($tupla->getImagem()) ? "<td><a href=\"../" . $tupla->getImagem() . "\" title=\"" . substr($tupla->getImagem(), 18) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/" . (strcmp(strtolower(substr($tupla->getImagem(), -3)), "png") == 0 ? "png.svg" : "jpg.svg") . "\" alt=\"Documento JPG\" width=\"25\" height=\"25\"></a></td>" : "<td></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="permissoes">Permissões</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Banner</th>
						<th scope="col">Boletim</th>
						<th scope="col">Convenção</th>
						<th scope="col">Convênio</th>
						<th scope="col">Diretório</th>
						<th scope="col">Edital</th>
						<th scope="col">Evento</th>
						<th scope="col">Finança</th>
						<th scope="col">Jornal</th>
						<th scope="col">Jurídico</th>
						<th scope="col">Notícia</th>
						<th scope="col">Podcast</th>
						<th scope="col">Registro</th>
						<th scope="col">Tabela</th>
						<th scope="col">Usuário</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($permissoes as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\" id=\"permissao" . $tupla->getId() . "\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->isBanner() . "</td>";
								echo "<td>" . $tupla->isBoletim() . "</td>";
								echo "<td>" . $tupla->isConvencao() . "</td>";
								echo "<td>" . $tupla->isConvenio() . "</td>";
								echo "<td>" . $tupla->isDiretorio() . "</td>";
								echo "<td>" . $tupla->isEdital() . "</td>";
								echo "<td>" . $tupla->isEvento() . "</td>";
								echo "<td>" . $tupla->isFinanca() . "</td>";
								echo "<td>" . $tupla->isJornal() . "</td>";
								echo "<td>" . $tupla->isJuridico() . "</td>";
								echo "<td>" . $tupla->isNoticia() . "</td>";
								echo "<td>" . $tupla->isPodcast() . "</td>";
								echo "<td>" . $tupla->isRegistro() . "</td>";
								echo "<td>" . $tupla->isTabela() . "</td>";
								echo "<td>" . $tupla->isUsuario() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="podcasts">Podcasts</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Título</th>
						<th scope="col">Áudio</th>
						<th scope="col">Usuário</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($podcasts as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getTitulo() . "</td>";
								echo "<td><a href=\"../" . $tupla->getAudio() . "\" title=\"" . substr($tupla->getAudio(), 17) . "\"><img class=\"cinza\" src=\"../imagens/mp3.svg\" alt=\"Aúdio MP3\" width=\"25\" height=\"25\"></a></td>";
								echo "<td><a class=\"text-dark\" href=\"#usuario" . $tupla->getUsuario()->getId() . "\">" . $tupla->getUsuario()->getId() . "</a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="registros">Registros</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Descrição</th>
						<th scope="col">Endereço</th>
						<th scope="col">Data - Hora</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($registros as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getDescricao() . "</td>";
								echo "<td>" . $tupla->getIp() . "</td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>

			<h3 class="h3 mt-5" id="usuarios">Usuários</h3>
			<table class="table table-striped table-hover mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Nome</th>
						<th scope="col">E-mail</th>
						<th scope="col">Login</th>
						<th scope="col">Senha</th>
						<th scope="col">Permissão</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($usuarios as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\" id=\"usuario" . $tupla->getId() . "\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getNome() . "</td>";
								echo "<td>" . $tupla->getEmail() . "</td>";
								echo "<td>" . $tupla->getLogin() . "</td>";
								echo "<td>" . $tupla->getSenha() . "</td>";
								echo "<td><a class=\"text-dark\" href=\"#permissao" . $tupla->getPermissao()->getId() . "\">" . $tupla->getPermissao()->getId() . "</a></td>";
								echo "<td>" . $tupla->isStatus() . "</td>";
								echo "</tr>";
							}
						}
					?>
				<tbody>
			</table>
		</div>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>

	<!-- Script -->
	<script>
		$(document).ready(function() {
			$("#input").change(function() {
				window.location.hash = this.value;
			});
		});
	</script>
</body>

</html>