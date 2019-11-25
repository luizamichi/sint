<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'financas.php';
	$title = 'Finanças';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$financa = sqlRead(table: 'FINANCAS', condition: 'ID = ' . (int) base64_decode($id), unique: true);

		if(!empty($financa)) { // REDIRECIONA PARA O CAMINHO DO DOCUMENTO
			header('Location: ' . BASE_URL . $financa['DOCUMENTO']);
			return true;
		}
	}

	$pages = ceil(sqlLength('FINANCAS') / 33); // QUANTIDADE DE PÁGINAS PARA 33 FINANÇAS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$financas = sqlRead(table: 'FINANCAS', condition: 'ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 33 . ', 33');

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>

<?php
	if(!empty($financas)) { // HÁ FINANÇAS CADASTRADAS
?>
		<div class="row">
<?php
		foreach($financas as $f => $financa) { // PERCORRE A LISTA DE FINANÇAS
			if($f % 11 === 0) {
?>
			<div class="col m4 s4">
				<ul class="collection">
<?php
			}
?>
					<li class="collection-item">
						<a class="black-text" href="<?= BASE_URL . $financa['DOCUMENTO'] ?>">
<?php
			if(stristr($financa['TITULO'], 'TRIMESTRE')) { // DESTACA AS FINANÇAS TRIMESTRAIS
?>
							<h6><strong><?= $financa['TITULO'] ?></strong></h6>
<?php
			}
			else {
?>
							<h6><?= $financa['TITULO'] ?></h6>
<?php
			}
?>
						</a>
					</li>
<?php
			if(($f + 1) % 11 === 0 || $f === count($financas) - 1) {
?>
				</ul>
			</div>
<?php
			}
		}
?>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ FINANÇAS CADASTRADAS
?>
		<h3 class="center-align">Ainda não temos finanças disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
