<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'jornais.php';
	$title = 'Jornais';

	$pages = ceil(sql_length($table='JORNAIS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 JORNAIS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO À PÁGINAS INEXISTENTES
	$jornais = sql_read($table='JORNAIS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-1"><?= $title ?></h1>
		</div>
<?php
	if(isset($jornais) && !empty($jornais)) { // HÁ JORNAIS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($jornais as $jornal) { // PERCORRE A LISTA DE JORNAIS
?>
			<div class="col m4 s6">
				<a href="<?= $website . $jornal['DOCUMENTO'] ?>">
					<div class="card small">
						<div class="card-image">
							<img alt="Jornal" src="<?= $website . ($jornal['IMAGEM'] ?? 'img/jornal.jpg') ?>"/>
						</div>
						<div class="card-content">
							<span class="black-text card-title"><?= $jornal['TITULO'] ?></span>
							<div class="teal-text"><?= $jornal['EDICAO'] ?>ª edição</div>
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
	else { // AINDA NÃO HÁ JORNAIS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos jornais disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once('navegador.php');
	require_once('rodape.php');
?>