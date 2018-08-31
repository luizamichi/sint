<?php
	$name = 'diretoria.php';
	$title = 'Diretoria';
	require_once('sgc/dao.php');
	$diretoria = sql_read($table='DIRETORIA', $condition='ID ORDER BY ID DESC LIMIT 1', $unique=true);
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
	if($diretoria) {
?>
				<h4 class="has-text-centered"><?= $diretoria['TITULO'] ?></h4>
<?php
		if($diretoria['IMAGEM']) {
?>
				<figure class="image is-3by1">
					<img alt="Diretoria" src="<?= $diretoria['IMAGEM'] ?>"/>
				</figure>
<?php
		}
?>
				<div><?= $diretoria['TEXTO'] ?></div>
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
	require_once('rodape.php');
?>