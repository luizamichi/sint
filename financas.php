<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'financas.php';
	$title = 'Finanças';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='FINANCAS') / 33);
	$financas = sql_read($table='FINANCAS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 33 . ',33', $unique=false);
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
	if($financas) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($financas as $financa) {
?>
					<div class="card column container is-one-third">
						<a href="<?= $financa['DOCUMENTO'] ?>">
							<?= strstr(strtoupper($financa['TITULO']), 'TRIMESTRE') ? '<h4><em>'. $financa['TITULO'] . "</em></h4>\n" : '<h4>'. $financa['TITULO'] . "</h4>\n" ?>
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