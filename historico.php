<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'historico.php';
	$title = 'Histórico';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='DIRETORIA', $condition='ID<(SELECT COUNT(*) FROM DIRETORIA)'));
	$historico = sql_read($table='DIRETORIA', $condition='ID<(SELECT COUNT(*) FROM DIRETORIA)-'. ($page - 1) . ' ORDER BY ID DESC LIMIT 1', $unique=true);
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
	if($historico) {
?>
				<h4 class="has-text-centered"><?= $historico['TITULO'] ?></h4>
<?php
		if($historico['IMAGEM']) {
?>
				<figure class="image is-3by1">
					<img alt="Histórico" src="<?= $historico['IMAGEM'] ?>"/>
				</figure>
<?php
		}
?>
				<div><?= $historico['TEXTO'] ?></div>
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