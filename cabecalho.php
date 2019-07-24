<?php
	if(count(get_included_files()) <= 1) { // DESABILITA O ACESSO À PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
		header('Location: index.php');
		return false;
	}

	$title ??= 'Sinteemar'; // VARIÁVEL OBTIDA NA INCLUSÃO
	if(isset($_SERVER['REQUEST_SCHEME'])) { // DEFINE A URL BASE DO WEBSITE
		$website = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/';
		$website = stristr($website, 'sinteemar.com.br') ? $website : $website . explode('/', $_SERVER['REQUEST_URI'])[1] . '/';
	}
	else {
		$website = '';
	}
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
	<meta property="og:image" content="<?= $website ?>img/card.png"/>
	<meta property="og:image:secure_url" content="<?= $website ?>img/card.png"/>
	<meta property="og:locale" content="pt_BR"/>
	<meta property="og:site_name" content="Sinteemar"/>
	<meta property="og:type" content="website"/>
	<meta property="og:title" content="Sinteemar - <?= $title ?>"/>
	<meta property="og:url" content="<?= $website ?>"/>

	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá"/>
	<meta name="twitter:image" content="<?= $website ?>img/card.png"/>
	<meta name="twitter:title" content="Sinteemar - <?= $title ?>"/>

	<link rel="icon" href="<?= $website ?>img/sinteemar.svg"/>
	<link rel="stylesheet" type="text/css" href="<?= $website ?>css/materialize.min.css"/>
</head>

<?php
	$title = strtok($title, ' ');
?>

<body>
	<nav class="darken-4 green nav-extended">
		<div class="nav-wrapper">
			<a class="brand-logo" href="<?= $website ?>index.php" title="Sinteemar">
				<img alt="Sinteemar" src="<?= $website ?>img/sinteemar.svg" style="margin-left: 10px;" width="25"/>
				<span class="hide-on-small-only">SINTEEMAR</span>
			</a>
			<a class="sidenav-trigger" data-target="mobile-demo" href="javascript:void(0)">&#9776;</a>
			<ul class="dropdown-content" id="dropdown-menu">
				<li class="<?= strcmp($title, 'Diretoria') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>diretoria.php">Diretoria</a></li>
				<li class="<?= strcmp($title, 'Estatuto') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>estatuto.php">Estatuto</a></li>
				<li class="<?= strcmp($title, 'Histórico') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>historico.php">Histórico</a></li>
			</ul>
			<ul class="hide-on-med-and-down right">
				<li class="<?= strcmp($title, 'Início') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>index.php">Início</a></li>
				<li class="<?= strcmp($title, 'Boletins') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>boletins.php">Boletins</a></li>
				<li class="<?= strcmp($title, 'Convenções') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>convencoes.php">Convenções</a></li>
				<li class="<?= strcmp($title, 'Convênios') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>convenios.php">Convênios</a></li>
				<li class="<?= strcmp($title, 'Editais') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>editais.php">Editais</a></li>
				<li class="<?= strcmp($title, 'Eventos') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>eventos.php">Eventos</a></li>
				<li class="<?= strcmp($title, 'Finanças') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>financas.php">Finanças</a></li>
				<li class="<?= in_array($title, array('Diretoria', 'Estatuto', 'Histórico')) ? 'active' : '' ?>"><a class="dropdown-trigger" href="javascript:void(0)" data-target="dropdown-menu">Institucional</a></li>
				<li class="<?= strcmp($title, 'Jornais') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>jornais.php">Jornais</a></li>
				<li class="<?= strcmp($title, 'Jurídicos') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>juridicos.php">Jurídicos</a></li>
				<li class="<?= strcmp($title, 'Notícias') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>noticias.php">Notícias</a></li>
				<li class="<?= strcmp($title, 'Podcasts') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>podcasts.php">Podcasts</a></li>
				<li class="<?= strcmp($title, 'Vídeos') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>videos.php">Vídeos</a></li>
			</ul>
			<ul class="sidenav" id="mobile-demo">
				<li class="<?= strcmp($title, 'Início') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>index.php">Início</a></li>
				<li class="<?= strcmp($title, 'Boletins') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>boletins.php">Boletins</a></li>
				<li class="<?= strcmp($title, 'Convenções') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>convencoes.php">Convenções</a></li>
				<li class="<?= strcmp($title, 'Convênios') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>convenios.php">Convênios</a></li>
				<li class="<?= strcmp($title, 'Diretoria') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>diretoria.php">Diretoria</a></li>
				<li class="<?= strcmp($title, 'Editais') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>editais.php">Editais</a></li>
				<li class="<?= strcmp($title, 'Estatuto') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>estatuto.php">Estatuto</a></li>
				<li class="<?= strcmp($title, 'Eventos') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>eventos.php">Eventos</a></li>
				<li class="<?= strcmp($title, 'Finanças') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>financas.php">Finanças</a></li>
				<li class="<?= strcmp($title, 'Histórico') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>historico.php">Histórico</a></li>
				<li class="<?= strcmp($title, 'Jornais') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>jornais.php">Jornais</a></li>
				<li class="<?= strcmp($title, 'Jurídicos') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>juridicos.php">Jurídicos</a></li>
				<li class="<?= strcmp($title, 'Notícias') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>noticias.php">Notícias</a></li>
				<li class="<?= strcmp($title, 'Podcasts') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>podcasts.php">Podcasts</a></li>
				<li class="<?= strcmp($title, 'Vídeos') == 0 ? 'active' : '' ?>"><a href="<?= $website ?>videos.php">Vídeos</a></li>
			</ul>
		</div>
	</nav>