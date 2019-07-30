<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'convenios.php';
	$title = 'Convênios';

	$pages = ceil(sql_length($table='CONVENIOS') / 15); // QUANTIDADE DE PÁGINAS PARA 15 CONVÊNIOS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$convenios = sql_read($table='CONVENIOS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ', 15', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>

<?php
	if(!empty($convenios)) { // HÁ CONVÊNIOS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($convenios as $convenio) { // PERCORRE A LISTA DE CONVÊNIOS CADASTRADOS
?>
			<div class="col m4 s6">
				<div class="card">
					<div class="card-image">
						<img alt="Convênio" src="<?= $website . $convenio['IMAGEM'] ?>"/>
					</div>
					<div class="card-content">
						<span class="black-text card-title"><?= $convenio['TITULO'] ?></span>
<?php
			if($convenio['TELEFONE']) {
?>
						<strong>Telefone:</strong> <span><?= $convenio['TELEFONE'] ?></span>
						<br/>
<?php
			}
			if($convenio['CELULAR']) {
?>
						<strong>Celular:</strong> <a class="teal-text" href="tel:<?= $convenio['CELULAR'] ?>"><?= $convenio['CELULAR'] ?></a>
						<br/>
<?php
			}
			if($convenio['EMAIL']) {
?>
						<strong>E-mail:</strong> <a class="teal-text" href="mailto:<?= $convenio['EMAIL'] ?>"><?= $convenio['EMAIL'] ?></a>
						<br/>
<?php
			}
			if($convenio['URL']) {
?>
						<strong>Website:</strong> <a class="teal-text" href="<?= $convenio['URL'] ?>"><?= $convenio['URL'] ?></a>
						<br/>
<?php
			}
?>
						<span><?= nl2br($convenio['TEXTO']) ?></span>
						<br/>
<?php
			if($convenio['DOCUMENTO']) {
?>
						<a class="teal-text" href="<?= $website . $convenio['DOCUMENTO'] ?>">Documento com detalhes</a>
						<br/>
<?php
			}
?>

					</div>
				</div>
			</div>
<?php
		}
?>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ CONVÊNIOS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos convênios disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once('navegador.php');
	require_once('rodape.php');
?>