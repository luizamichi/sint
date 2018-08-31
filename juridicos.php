<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'juridicos.php';
	$title = 'Jurídicos';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='JURIDICOS') / 30);
	$juridicos = sql_read($table='JURIDICOS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 30 . ',30', $unique=false);
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
	if($juridicos) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($juridicos as $juridico) {
?>
					<div class="card column container is-half">
						<a href="<?= $juridico['DOCUMENTO'] ?>">
							<h4><?= $juridico['TITULO'] ?></h4>
							<p><?= $juridico['TEXTO'] ?: '' ?></p>
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