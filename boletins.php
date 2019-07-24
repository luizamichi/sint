<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'boletins.php';
	$title = 'Boletins';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$boletim = sql_read($table='BOLETINS', $condition='ID=' . (int) base64_decode($id), $unique=true);

		if(!empty($boletim)) { // REDIRECIONA PARA O CAMINHO DA IMAGEM
			header('Location: ' . $boletim['IMAGEM']);
			return true;
		}
	}

	$pages = ceil(sql_length($table='BOLETINS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 BOLETINS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$boletins = sql_read($table='BOLETINS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>
<?php
	if(!empty($boletins)) { // HÁ BOLETINS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($boletins as $boletim) { // PERCORRE A LISTA DE BOLETINS
?>
			<div class="col m4 s6">
				<a href="<?= $website . $boletim['IMAGEM'] ?>">
					<div class="card hoverable small">
						<div class="card-image">
							<img alt="Boletim" src="<?= $website . $boletim['IMAGEM'] ?>"/>
						</div>
						<div class="card-content">
							<span class="black-text card-title"><?= $boletim['TITULO'] ?></span>
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
	else { // AINDA NÃO HÁ BOLETINS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos boletins disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once('navegador.php');
	require_once('rodape.php');
?>