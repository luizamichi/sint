<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$id = (int) base64_decode($id);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'podcasts.php';
	$title = 'Podcasts';

	$pages = ceil(sqlLength('PODCASTS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 PODCASTS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$podcasts = sqlRead(table: 'PODCASTS', condition: 'ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24');

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>

<?php
	if(!empty($podcasts)) { // HÁ PODCASTS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($podcasts as $podcast) { // PERCORRE A LISTA DE PODCASTS CADASTRADOS
?>
			<div class="col m6 s12">
				<div class="card <?= ($id === (int) $podcast['ID'] ? 'green lighten-5' : '') ?>">
					<div class="card-content">
						<span class="black-text card-title"><?= $podcast['TITULO'] ?></span>
						<audio <?= ($id === (int) $podcast['ID'] ? 'autoplay="autoplay"' : '') ?> controls style="width: 100%;">
							<source src="<?= BASE_URL . $podcast['AUDIO'] ?>" type="audio/mp3"/>
						</audio>
					</div>
					<div class="black-text card-action">
						<a class="black-text" href="javascript:void(0)">Áudio postado em: <time datetime="<?= substr($podcast['DATA'], 0, 10) . ' ' . substr($podcast['HORA'], 0, 5) ?>"><?= date_format(date_create(substr($podcast['DATA'], 0, 10) . ' ' . $podcast['HORA']), 'd/m/Y - H:i') ?></time></a>
						<a class="darken-4 green-text" download href="<?= BASE_URL . $podcast['AUDIO'] ?>">Download</a>
					</div>
				</div>
			</div>
<?php
		}
?>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ PODCASTS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos podcasts disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
