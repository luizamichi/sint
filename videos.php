<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'videos.php';
	$title = 'Vídeos';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='VIDEOS') / 30);
	$videos = sql_read($table='VIDEOS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 30 . ',30', $unique=false);
?>

<?php
	require_once('cabecalho.php');
?>

	<div class="container is-fluid">
		<section class="section">
			<div class="has-background-success has-text-centered my-5 px-3 py-3">
				<h1 class="has-text-white is-1 title"><?= $title ?></h1>
			</div>
			<div class="container content">

<?php
	if($videos) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($videos as $video) {
?>
					<div class="card column container is-half">
						<a href="<?= $video['URL'] ?>">
							<h4><?= $video['TITULO'] ?></h4>
						</a>
					</div>
<?php
		}
?>
				</div>
<?php
	}
	else {
?>
				<h3 class="has-text-centered mt-5">Ainda não temos conteúdo disponível :(</h3>
<?php
	}
?>
			</div>
		</section>
	</div>
<?php
	require_once('navegador.php');
	require_once('rodape.php');
?>