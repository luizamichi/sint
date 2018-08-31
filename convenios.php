<?php
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));
	$name = 'convenios.php';
	$title = 'Convênios';
	require_once('sgc/dao.php');
	$pages = ceil(sql_length($table='CONVENIOS') / 15);
	$convenios = sql_read($table='CONVENIOS', $condition='ID ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ',15', $unique=false);
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
	if($convenios) {
?>
				<div class="columns is-multiline mb-2 mt-5">
<?php
		foreach($convenios as $convenio) {
?>
					<div class="card column container is-one-third">
						<div class="card-image">
							<figure class="image is-3by1">
								<img alt="Convênio" src="<?= $convenio['IMAGEM'] ?>"/>
							</figure>
						</div>
						<div class="card-content">
							<h4 class="title is-4"><?= $convenio['TITULO'] ?></h4>
							<div class="content">
								<?= $convenio['TELEFONE'] ? '<strong>Telefone</strong>: ' . $convenio['TELEFONE'] . '<br/>' : '' ?>
								<?= $convenio['CELULAR'] ? '<strong>Celular</strong>: ' . $convenio['CELULAR'] . '<br/>' : '' ?>
								<?= $convenio['TEXTO'] ?>
								<?= $convenio['DOCUMENTO'] ? '<br/><a href="' . $convenio['DOCUMENTO'] . '">Documento com detalhes.</a>': '' ?>
								<br/>
							</div>
						</div>
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