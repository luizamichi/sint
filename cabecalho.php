<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	// DESABILITA O ACESSO À PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
	if(count(get_included_files()) <= 1) {
		header('Location: index.php');
		exit;
	}

	$title ??= 'Sinteemar'; // VARIÁVEL OBTIDA NA INCLUSÃO
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8"/>
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi"/>
	<meta name="description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Sinteemar - <?= $title ?></title>

	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá"/>
	<meta property="og:image" content="<?= BASE_URL ?>img/card.png"/>
	<meta property="og:image:secure_url" content="<?= BASE_URL ?>img/card.png"/>
	<meta property="og:locale" content="pt_BR"/>
	<meta property="og:site_name" content="Sinteemar"/>
	<meta property="og:type" content="website"/>
	<meta property="og:title" content="Sinteemar - <?= $title ?>"/>
	<meta property="og:url" content="<?= BASE_URL ?>"/>

	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá"/>
	<meta name="twitter:image" content="<?= BASE_URL ?>img/card.png"/>
	<meta name="twitter:title" content="Sinteemar - <?= $title ?>"/>

	<link rel="icon" href="<?= BASE_URL ?>img/sinteemar.svg"/>
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>css/materialize.min.css"/>
</head>

<?php
	$title = strtok($title, ' ');
?>

<body>
	<nav class="darken-4 green nav-extended">
		<div class="nav-wrapper">
			<a class="brand-logo" href="<?= BASE_URL ?>index.php" title="Sinteemar">
				<img alt="Sinteemar" loading="lazy" src="<?= BASE_URL ?>img/sinteemar.svg" style="margin-left: 10px;" width="25"/>
				<span class="hide-on-small-only">SINTEEMAR</span>
			</a>
			<a class="sidenav-trigger" data-target="mobile-demo" href="javascript:void(0)">&#9776;</a>
			<ul class="dropdown-content" id="dropdown-menu">
				<li class="<?= $title === 'Diretoria' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>diretoria.php">Diretoria</a></li>
				<li class="<?= $title === 'Estatuto' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>estatuto.php">Estatuto</a></li>
				<li class="<?= $title === 'Histórico' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>historico.php">Histórico</a></li>
			</ul>
			<ul class="hide-on-med-and-down right">
				<li class="<?= $title === 'Início' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>index.php">Início</a></li>
				<li class="<?= $title === 'Boletins' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>boletins.php">Boletins</a></li>
				<li class="<?= $title === 'Convenções' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>convencoes.php">Convenções</a></li>
				<li class="<?= $title === 'Convênios' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>convenios.php">Convênios</a></li>
				<li class="<?= $title === 'Editais' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>editais.php">Editais</a></li>
				<li class="<?= $title === 'Eventos' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>eventos.php">Eventos</a></li>
				<li class="<?= $title === 'Finanças' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>financas.php">Finanças</a></li>
				<li class="<?= in_array($title, ['Diretoria', 'Estatuto', 'Histórico']) ? 'active' : '' ?>"><a class="dropdown-trigger" href="javascript:void(0)" data-target="dropdown-menu">Institucional</a></li>
				<li class="<?= $title === 'Jornais' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>jornais.php">Jornais</a></li>
				<li class="<?= $title === 'Jurídicos' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>juridicos.php">Jurídicos</a></li>
				<li class="<?= $title === 'Notícias' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>noticias.php">Notícias</a></li>
				<li class="<?= $title === 'Podcasts' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>podcasts.php">Podcasts</a></li>
				<li class="<?= $title === 'Vídeos' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>videos.php">Vídeos</a></li>
			</ul>
			<ul class="sidenav" id="mobile-demo">
				<li class="<?= $title === 'Início' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>index.php">Início</a></li>
				<li class="<?= $title === 'Boletins' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>boletins.php">Boletins</a></li>
				<li class="<?= $title === 'Convenções' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>convencoes.php">Convenções</a></li>
				<li class="<?= $title === 'Convênios' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>convenios.php">Convênios</a></li>
				<li class="<?= $title === 'Diretoria' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>diretoria.php">Diretoria</a></li>
				<li class="<?= $title === 'Editais' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>editais.php">Editais</a></li>
				<li class="<?= $title === 'Estatuto' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>estatuto.php">Estatuto</a></li>
				<li class="<?= $title === 'Eventos' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>eventos.php">Eventos</a></li>
				<li class="<?= $title === 'Finanças' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>financas.php">Finanças</a></li>
				<li class="<?= $title === 'Histórico' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>historico.php">Histórico</a></li>
				<li class="<?= $title === 'Jornais' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>jornais.php">Jornais</a></li>
				<li class="<?= $title === 'Jurídicos' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>juridicos.php">Jurídicos</a></li>
				<li class="<?= $title === 'Notícias' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>noticias.php">Notícias</a></li>
				<li class="<?= $title === 'Podcasts' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>podcasts.php">Podcasts</a></li>
				<li class="<?= $title === 'Vídeos' ? 'active' : '' ?>"><a href="<?= BASE_URL ?>videos.php">Vídeos</a></li>
			</ul>
		</div>
	</nav>
