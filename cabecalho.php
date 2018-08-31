<?php
	$website = ($_SERVER['REQUEST_SCHEME'] ?? 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<link rel="icon" href="img/sinteemar.svg"/>
	<link rel="stylesheet" type="text/css" href="css/bulma.min.css"/>
	<meta charset="utf-8"/>
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi"/>
	<meta name="description" content="Sindicato Dos Trabalhadores em Estabelecimentos de Ensino de Maringá"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Sinteemar - <?= $title ?></title>

	<meta property="og:description" content="Sindicato Dos Trabalhadores em Estabelecimentos de Ensino de Maringá"/>
	<meta property="og:image" content="<?= $website . "img/card.png" ?>"/>
	<meta property="og:image:secure_url" content="<?= $website . "img/card.png" ?>"/>
	<meta property="og:locale" content="pt_BR"/>
	<meta property="og:site_name" content="Sinteemar"/>
	<meta property="og:type" content="website"/>
	<meta property="og:title" content="Sinteemar - <?= $title ?>"/>
	<meta property="og:url" content="<?= $website . "convenios.php" ?>"/>

	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:description" content="Sindicato Dos Trabalhadores em Estabelecimentos de Ensino de Maringá"/>
	<meta name="twitter:image" content="<?= $website . "img/card.png" ?>"/>
	<meta name="twitter:title" content="Sinteemar - <?= $title ?>"/>
</head>

<body>
	<nav class="is-fixed-top is-success navbar">
		<div class="navbar-brand">
			<a class="navbar-item" href="index.php" title="Sinteemar">
				<img alt="Sinteemar" src="img/sinteemar.svg" width="30"/>
				<h2 class="mx-3">SINTEEMAR</h2>
			</a>
			<div class="burger navbar-burger" data-target="header">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>

		<div class="navbar-menu" id="header">
			<div class="navbar-end">
				<a class="<?= strcmp($title, 'Início') == 0 ? 'is-active' : '' ?> navbar-item" href="index.php">Início</a>
				<a class="<?= strcmp($title, 'Boletins') == 0 ? 'is-active' : '' ?> navbar-item" href="boletins.php">Boletins</a>
				<a class="<?= strcmp($title, 'Convenções') == 0 ? 'is-active' : '' ?> navbar-item" href="convencoes.php">Convenções</a>
				<a class="<?= strcmp($title, 'Convênios') == 0 ? 'is-active' : '' ?> navbar-item" href="convenios.php">Convênios</a>
				<a class="<?= strcmp($title, 'Editais') == 0 ? 'is-active' : '' ?> navbar-item" href="editais.php">Editais</a>
				<a class="<?= strcmp($title, 'Eventos') == 0 ? 'is-active' : '' ?> navbar-item" href="eventos.php">Eventos</a>
				<a class="<?= strcmp($title, 'Finanças') == 0 ? 'is-active' : '' ?> navbar-item" href="financas.php">Finanças</a>
				<div class="has-dropdown is-hoverable navbar-item">
					<a class="<?= in_array($title, array('Diretoria', 'Estatuto', 'Histórico', 'Sobre nós')) ? 'is-active' : '' ?> navbar-link" href="#">Institucional</a>
					<div class="is-boxed navbar-dropdown">
						<a class="<?= strcmp($title, 'Diretoria') == 0 ? 'is-active' : '' ?> navbar-item" href="diretoria.php">Diretoria</a>
						<a class="<?= strcmp($title, 'Estatuto') == 0 ? 'is-active' : '' ?> navbar-item" href="estatuto.php">Estatuto</a>
						<a class="<?= strcmp($title, 'Histórico') == 0 ? 'is-active' : '' ?> navbar-item" href="historico.php">Histórico</a>
						<a class="<?= strcmp($title, 'Sobre nós') == 0 ? 'is-active' : '' ?> navbar-item" href="sobre.php">Sobre nós</a>
					</div>
				</div>
				<a class="<?= strcmp($title, 'Jornais') == 0 ? 'is-active' : '' ?> navbar-item" href="jornais.php">Jornais</a>
				<a class="<?= strcmp($title, 'Jurídicos') == 0 ? 'is-active' : '' ?> navbar-item" href="juridicos.php">Jurídicos</a>
				<a class="<?= strcmp($title, 'Notícias') == 0 ? 'is-active' : '' ?> navbar-item" href="noticias.php">Notícias</a>
				<a class="<?= strcmp($title, 'Vídeos') == 0 ? 'is-active' : '' ?> navbar-item" href="videos.php">Vídeos</a>
				<div class="navbar-item">
					<a class="mx-2" href="https://www.facebook.com/sinteemar" target="_blank" title="Siga-nos no Facebook"><img alt="Facebook" src="img/facebook.svg" width="25"/></a>
					<a class="mx-2" href="https://wa.me/554499613561" target="_blank" title="Entre em contato conosco pelo WhatsApp"><img alt="WhatsApp" src="img/whatsapp.svg" width="25"/></a>
					<a class="mx-2" href="https://www.youtube.com/user/sinteemar" target="_blank" title="Acompanhe o nosso canal no YouTube"><img alt="YouTube" src="img/youtube.svg" width="25"/></a>
				</div>
			</div>
		</div>
	</nav>