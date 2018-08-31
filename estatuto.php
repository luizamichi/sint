<?php
	$name = 'estatuto.php';
	$title = 'Estatuto';
	require_once('sgc/dao.php');
	$estatuto = sql_read($table='ESTATUTO', $condition='ID ORDER BY ID DESC LIMIT 1', $unique=true);
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
	if($estatuto) {
?>
				<a href="<?= $estatuto['DOCUMENTO'] ?>"><img alt="Estatuto" class="has-text-centered image" src="img/document.svg" width="75"/></a>
				<p class="has-text-centered mt-2">Clique no documento para visualizar.</p>
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