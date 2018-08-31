<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'convencoes.php';
	$title = 'Convenções';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='CONVENCOES') / 30);
	$convencoes['VIGENTE'] = sql_read($table='CONVENCOES', $condition='TIPO=1 ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ',15', $unique=false);
	$convencoes['ANTERIOR'] = sql_read($table='CONVENCOES', $condition='TIPO=0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ',15', $unique=false);
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
	if($convencoes['VIGENTE']) {
?>
				<h3 class="has-text-centered">Vigentes</h3>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($convencoes['VIGENTE'] as $convencao) {
?>
					<div class="card column container is-half">
						<a href="<?= $convencao['DOCUMENTO'] ?>">
							<h4><?= $convencao['TITULO'] ?></h4>
						</a>
					</div>
<?php
		}
	}
	if($convencoes['ANTERIOR']) {
?>
				</div>
				<h3 class="has-text-centered">Anteriores</h3>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($convencoes['ANTERIOR'] as $convencao) {
?>
					<div class="card column container is-half">
						<a href="<?= $convencao['DOCUMENTO'] ?>">
							<h4><?= $convencao['TITULO'] ?></h4>
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