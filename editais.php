<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'editais.php';
	$title = 'Editais';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$edital = sqlRead(table: 'EDITAIS', condition: 'ID = ' . (int) base64_decode($id), unique: true);
		$title = empty($edital) ? 'Editais' : 'Editais - ' . $edital['TITULO'];
	}
	else {
		$pages = ceil(sqlLength('EDITAIS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 EDITAIS POR PÁGINA
		$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
		$editais = sqlRead(table: 'EDITAIS', condition: 'ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24');
	}

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $edital['TITULO'] ?? $title ?></h1>
		</div>
<?php
	if(isset($edital) && !empty($edital)) { // EXIBE O EDITAL SOLICITADO
?>
		<div class="center">
			<img alt="Edital" loading="lazy" class="materialboxed responsive-img" src="<?= BASE_URL . $edital['IMAGEM'] ?>" width="300"/>
		</div>
		<div id="text-content"><?= $edital['TEXTO'] ?></div>
		<div class="fixed-action-btn">
			<a class="btn-floating btn-large tooltipped" data-id="text-content" data-position="left" data-tooltip="Alterar o tamanho da fonte" id="button-toggle" href="javascript:void(0)">
				<img alt="Alterar o tamanho da fonte" loading="lazy" src="img/fonte.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>
<?php
	}
	elseif(isset($editais) && !empty($editais)) { // HÁ EDITAIS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($editais as $edital) { // PERCORRE A LISTA DE EDITAIS
?>
			<div class="col m4 s6">
				<a href="<?= BASE_URL ?>editais.php?id=<?= rtrim(strtr(base64_encode($edital['ID']), '+/', '-_'), '=') ?>">
					<div class="card hoverable small">
						<div class="card-image">
							<img alt="Edital" loading="lazy" src="<?= BASE_URL . $edital['IMAGEM'] ?>"/>
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
		if(isset($editais) || empty($editais)) { // AINDA NÃO HÁ EDITAIS CADASTRADOS
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
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
