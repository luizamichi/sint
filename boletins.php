<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'boletins.php';
	$title = 'Boletins';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='BOLETINS') / 24);
	$boletins = sql_read($table='BOLETINS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ',24', $unique=false);
?>

<?php
	require_once('cabecalho.php');
?>

	<div class="container is-fluid">
		<section class="section">
			<div class="has-background-success has-text-centered my-5 px-3 py-3">
				<h1 class="has-text-white is-1 title"><?= isset($boletim['TITULO']) ? $boletim['TITULO'] : $title ?></h1>
			</div>
			<div class="container content">
<?php
	if($boletins) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($boletins as $boletim) {
?>
					<div class="card column container is-half-tablet is-one-quarter-desktop">
						<a href="<?= $boletim['IMAGEM'] ?>">
							<div class="card-image">
								<figure class="image is-3by1">
									<img alt="Boletim" src="<?= $boletim['IMAGEM'] ?? 'img/boletim.jpg' ?>"/>
								</figure>
							</div>
							<div class="card-content">
								<div class="media">
									<div class="media-content">
										<p class="title is-4"><?= $boletim['TITULO'] ?></p>
									</div>
								</div>
							</div>
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