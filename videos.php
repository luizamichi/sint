<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'videos.php';
	$title = 'Vídeos';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$video = sqlRead(table: 'VIDEOS', condition: 'ID = ' . (int) base64_decode($id), unique: true);

		if(!empty($video)) { // REDIRECIONA PARA A URL DO VÍDEO
			header('Location: ' . $video['URL']);
			return true;
		}
	}

	$pages = ceil(sqlLength('VIDEOS') / 30); // QUANTIDADE DE PÁGINAS PARA 30 VÍDEOS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$videos = sqlRead(table: 'VIDEOS', condition: 'ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 30 . ', 30');

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>

<?php
	if(!empty($videos)) { // HÁ VÍDEOS CADASTRADOS
?>
		<div class="collection">
<?php
		foreach($videos as $video) { // PERCORRE A LISTA DE VÍDEOS CADASTRADOS
?>
			<a class="collection-item" href="<?= $video['URL'] ?>">
				<h6 class="black-text"><?= $video['TITULO'] ?></h6>
				<small><?= $video['URL'] ?></small>
			</a>
<?php
		}
?>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ VÍDEOS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos vídeos disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
