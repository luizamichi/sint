<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'podcasts.php';
	$title = 'Podcasts';

	$pages = ceil(sql_length($table='PODCASTS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 PODCASTS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO À PÁGINAS INEXISTENTES
	$podcasts = sql_read($table='PODCASTS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-1"><?= $title ?></h1>
		</div>

<?php
	if(!empty($podcasts)) { // HÁ PODCASTS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($podcasts as $podcast) { // PERCORRE A LISTA DE PODCASTS CADASTRADOS
?>
			<div class="col m6 s12">
				<div class="card <?= ($id && strcmp(base64_decode($id), $podcast['ID']) == 0 ? 'green lighten-5' : '') ?>">
					<div class="card-content">
						<span class="black-text card-title"><?= $podcast['TITULO'] ?></span>
						<audio <?= ($id && strcmp($id, $podcast['ID']) == 0 ? 'allow="autoplay" autoplay="autoplay"' : '') ?> controls style="width: 100%;">
							<source src="<?= $website . $podcast['AUDIO'] ?>" type="audio/mp3"/>
						</audio>
					</div>
					<div class="black-text card-action">
						<a class="black-text" href="#">Áudio postado em: <time datetime="<?= $podcast['DATA'] . ' ' . $podcast['HORA'] ?>"><?= date_format(date_create($podcast['DATA'] . ' ' . $podcast['HORA']), 'd/m/Y - H:i') ?></time></a>
						<a class="darken-4 green-text" download href="<?= $website . $podcast['AUDIO'] ?>">Download</a>
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
	require_once('navegador.php');
	require_once('rodape.php');
?>