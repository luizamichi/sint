<?php
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'editais.php';
	$title = 'Editais';
	require_once('sgc/dao.php');
	if($id)
		$edital = sql_read($table='EDITAIS', $condition='ID=' . intval(base64_decode($id)), $unique=true);
	else {
		$pages = ceil(sql_length($table='EDITAIS') / 24);
		$editais = sql_read($table='EDITAIS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ',24', $unique=false);
	}
?>

<?php
	require_once('cabecalho.php');
?>

	<div class="container is-fluid">
		<section class="section">
			<div class="has-background-success has-text-centered my-5 px-3 py-3">
				<h1 class="has-text-white is-1 title"><?= isset($edital['TITULO']) ? $edital['TITULO'] : $title ?></h1>
			</div>
			<div class="container content">
<?php
	if(isset($edital) && $edital) {
?>
				<figure class="image is-2by3">
					<img alt="Edital" class="has-text-centered" src="<?= $edital['IMAGEM'] ?>"/>
				</figure>
				<div class="mb-3"><?= $edital['TEXTO'] ?></div>

<?php
	}
	elseif(isset($editais) && $editais) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($editais as $edital) {
?>
					<div class="card column container is-half-tablet is-one-quarter-desktop">
						<a href="editais.php?id=<?= rtrim(strtr(base64_encode($edital['ID']), '+/', '-_'), '=') ?>">
<?php
			if($edital['IMAGEM']) {
?>
							<div class="card-image">
								<figure class="image is-4by3">
									<img alt="Edital" src="<?= $edital['IMAGEM'] ?>"/>
								</figure>
							</div>
<?php
			}
?>
							<div class="card-content">
								<div class="media">
									<div class="media-content">
										<p class="is-4 title"><?= $edital['TITULO'] ?></p>
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