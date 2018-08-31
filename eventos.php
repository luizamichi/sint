<?php
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'eventos.php';
	$title = 'Eventos';
	require_once('sgc/dao.php');
	if($id)
		$evento = sql_read($table='EVENTOS', $condition='ID=' . intval(base64_decode($id)), $unique=true);
	else {
		$pages = ceil(sql_length($table='EVENTOS') / 24);
		$eventos = sql_read($table='EVENTOS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ',24', $unique=false);
	}
?>

<?php
	require_once('cabecalho.php');
?>

	<div class="container is-fluid">
		<section class="section">
			<div class="has-background-success has-text-centered my-5 px-3 py-3">
				<h1 class="has-text-white is-1 title"><?= isset($evento['TITULO']) ? $evento['TITULO'] : $title ?></h1>
			</div>
			<div class="container content">
<?php
	if(isset($evento) && $evento) {
		if($evento['TEXTO']) {
?>
				<div class="mb-3"><?= $evento['TEXTO'] ?></div>
<?php
		}
		if($evento['IMAGENS'] && is_dir($evento['IMAGENS'])) {
?>

				<div class="columns is-multiline">
<?php
			foreach(array_slice(scandir($evento['IMAGENS']), 2) as $imagem) {
?>
					<div class="column container is-half-tablet is-one-quarter-desktop">
						<img alt="Evento" class="has-text-centered image" src="<?= $evento['IMAGENS'] . $imagem ?>"/>
					</div>
<?php
			}
?>
				</div>
<?php
		}
	}
	elseif(isset($eventos) && $eventos) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($eventos as $evento) {
?>
					<div class="card column container is-half-tablet is-one-quarter-desktop">
						<a href="eventos.php?id=<?= rtrim(strtr(base64_encode($evento['ID']), '+/', '-_'), '=') ?>">
							<div class="card-content">
								<div class="media">
									<div class="media-content">
										<p class="is-4 title"><?= $evento['TITULO'] ?></p>
									</div>
								</div>
								<div class="content line-clamp line-clamp-3">
									<?= strip_tags($evento['TEXTO']) . "\n" ?: '' ?>
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