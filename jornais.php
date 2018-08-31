<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'jornais.php';
	$title = 'Jornais';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='JORNAIS') / 24);
	$jornais = sql_read($table='JORNAIS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ',24', $unique=false);
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
	if($jornais) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($jornais as $jornal) {
?>
					<div class="card column container is-half-tablet is-one-quarter-desktop">
						<a href="<?= $jornal['DOCUMENTO'] ?>">
							<div class="card-image">
								<figure class="image is-3by1">
									<img alt="Jornal" src="<?= $jornal['IMAGEM'] ?? 'img/jornal.jpg' ?>"/>
								</figure>
							</div>
							<div class="card-content">
								<div class="media">
									<div class="media-content">
										<p class="title is-4"><?= $jornal['TITULO'] ?></p>
									</div>
								</div>
								<div class="content">
									<?= $jornal['EDICAO'] ?>ª edição
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