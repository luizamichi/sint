<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'videos.php';
	$title = 'Vídeos';

	$pages = ceil(sql_length($table='VIDEOS') / 30); // QUANTIDADE DE PÁGINAS PARA 30 VÍDEOS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$videos = sql_read($table='VIDEOS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 30 . ', 30', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
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
	require_once('navegador.php');
	require_once('rodape.php');
?>