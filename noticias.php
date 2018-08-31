<?php
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'noticias.php';
	$title = 'Notícias';
	require_once('sgc/dao.php');
	if($id)
		$noticia = sql_read($table='NOTICIAS', $condition='ID=' . intval(base64_decode($id)), $unique=true);
	else {
		$pages = ceil(sql_length($table='NOTICIAS', $condition='STATUS=1') / 24);
		$noticias = sql_read($table='NOTICIAS', $condition='STATUS=1 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ',24', $unique=false);
	}
?>

<?php
	require_once('cabecalho.php');
?>

	<div class="container is-fluid">
		<section class="section">
			<div class="has-background-success has-text-centered my-5 px-3 py-3">
				<h1 class="has-text-white is-1 title"><?= isset($noticia['TITULO']) ? $noticia['TITULO'] : $title ?></h1>
			</div>
			<div class="container content">
<?php
	if(isset($noticia) && $noticia) {
		if($noticia['IMAGEM']) {
?>
				<figure class="image is-3by1">
					<img alt="Notícia" class="has-text-centered" src="<?= $noticia['IMAGEM'] ?>"/>
				</figure>
<?php
		}
?>
				<div class="mb-3 pb-3"><?= $noticia['TEXTO'] ?></div>

<?php
	}
	elseif(isset($noticias) && $noticias) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($noticias as $noticia) {
?>
					<div class="card column container is-half-tablet is-one-quarter-desktop">
						<a href="noticias.php?id=<?= rtrim(strtr(base64_encode($noticia['ID']), '+/', '-_'), '=') ?>">
<?php
			if($noticia['IMAGEM']) {
?>
							<div class="card-image">
								<figure class="image is-3by1">
									<img alt="Notícia" src="<?= $noticia['IMAGEM'] ?>"/>
								</figure>
							</div>
<?php
			}
?>
							<div class="card-content">
								<div class="media">
									<div class="media-content">
										<p class="is-4 title"><?= $noticia['TITULO'] ?></p>
									</div>
								</div>
								<div class="content line-clamp <?= $noticia['IMAGEM'] ? 'line-clamp-3' : 'line-clamp-7' ?>">
									<?= strip_tags($noticia['TEXTO']) . "\n" ?>
								</div>
								<time datetime="<?= $noticia['DATA'] . ' ' . $noticia['HORA'] ?>"><?= date_format(date_create($noticia['DATA'] . ' ' . $noticia['HORA']), 'H:i - d/m/Y') ?></time>
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