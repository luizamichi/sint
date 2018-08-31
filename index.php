<?php
	$name = 'index.php';
	$title = 'Início';
	require_once('cabecalho.php');
	require_once('sgc/dao.php');
	$banners = sql_read($table='BANNERS', $condition='ID ORDER BY ID DESC', $unique=false);
	$editais = sql_read($table='EDITAIS', $condition='ID ORDER BY ID DESC LIMIT 5', $unique=false);
	$eventos = sql_read($table='EVENTOS', $condition='ID ORDER BY ID DESC LIMIT 3', $unique=false);
	$noticias = sql_read($table='NOTICIAS', $condition='STATUS=1 ORDER BY ID DESC LIMIT 10', $unique=false);
	$tuplas = array_merge($noticias, $editais, $eventos);
?>

	<div class="container is-fluid">
		<section class="section">
			<h1 style="visibility: hidden;">Início</h1>
			<div class="container content">

<?php
	if($banners) {
?>
				<div class="mt-5 slideshow-container">
<?php
		foreach($banners as $banner) {
?>
					<div class="fade slide">
						<img alt="Banner" class="img" src="<?= $banner['IMAGEM'] ?>" style="width:100%"/>
					</div>
<?php
		}
?>
					<a class="before" onclick="plusSlides(-1)">&#10094;</a>
					<a class="after" onclick="plusSlides(1)">&#10095;</a>

				</div>
				<br/>
				<div style="text-align:center">
<?php
		for($i=1; $i <= count($banners); $i++) {
?>
					<span class="dot" onclick="currentSlide(<?= $i ?>)"></span>

<?php
		}
?>
				</div>
<?php
	}
	if($tuplas) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($tuplas as $tupla) {
?>
					<div class="card column container is-half-tablet is-one-third-desktop">
						<a href="<?php if(isset($tupla['STATUS'])) echo 'noticias'; elseif(isset($tupla['IMAGENS'])) echo 'eventos'; else echo 'editais'; ?>.php?id=<?= rtrim(strtr(base64_encode($tupla['ID']), '+/', '-_'), '=') ?>">
<?php
			if(isset($tupla['IMAGEM']) && $tupla['IMAGEM']) {
?>
							<div class="card-image">
								<figure class="image is-3by1">
									<img alt="Imagem" src="<?= $tupla['IMAGEM'] ?>"/>
								</figure>
							</div>
<?php
			}
?>
							<div class="card-content">
								<div class="media">
									<div class="media-content">
										<p class="is-4 title"><?= $tupla['TITULO'] ?></p>
									</div>
								</div>
								<div class="content line-clamp <?= isset($tupla['IMAGEM']) && $tupla['IMAGEM'] ? 'line-clamp-3' : 'line-clamp-9' ?>">
									<?= strip_tags($tupla['TEXTO']). "\n" ?>
								</div>
								<strong><?php if(isset($tupla['STATUS'])) echo 'Notícia'; elseif(isset($tupla['IMAGENS'])) echo 'Evento'; else echo 'Edital'; ?></strong>
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