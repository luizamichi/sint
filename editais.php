<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'editais.php';
	$title = 'Editais';

	if(!empty($id)) // FOI INFORMADO UM ID NA URL
		$edital = sql_read($table='EDITAIS', $condition='ID=' . (int) base64_decode($id), $unique=true);
	else {
		$pages = ceil(sql_length($table='EDITAIS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 EDITAIS POR PÁGINA
		$page = min($page, $pages); // EVITA O ACESSO À PÁGINAS INEXISTENTES
		$editais = sql_read($table='EDITAIS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24', $unique=false);
	}

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-1"><?= $edital['TITULO'] ?? $title ?></h1>
		</div>
<?php
	if(isset($edital) && !empty($edital)) { // EXIBE O EDITAL SOLICITADO
?>
		<div class="center">
			<img alt="Edital" class="materialboxed responsive-img" src="<?= $website . $edital['IMAGEM'] ?>" width="300"/>
		</div>
		<div class="flow-text"><?= $edital['TEXTO'] ?></div>
<?php
	}
	elseif(isset($editais) && !empty($editais)) { // HÁ EDITAIS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($editais as $edital) { // PERCORRE A LISTA DE EDITAIS
?>
			<div class="col m4 s6">
				<a href="<?= $website ?>editais.php?id=<?= rtrim(strtr(base64_encode($edital['ID']), '+/', '-_'), '=') ?>">
					<div class="card small">
						<div class="card-image">
							<img alt="Edital" src="<?= $website . $edital['IMAGEM'] ?>"/>
						</div>
						<div class="card-content">
							<span class="black-text card-title"><?= $edital['TITULO'] ?></span>
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
	else {
		if(!empty($editais)) { // AINDA NÃO HÁ EDITAIS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos editais disponíveis :(</h3>
<?php
		}
		else { // FOI INFORMADO UM ID INVÁLIDO
?>
		<h3 class="center-align">Não foi encontrado o edital solicitado :(</h3>
<?php
		}
	}
?>
	</div>
<?php
	require_once('navegador.php');
	require_once('rodape.php');
?>