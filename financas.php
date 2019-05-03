<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'financas.php';
	$title = 'Finanças';

	$pages = ceil(sql_length($table='FINANCAS') / 33); // QUANTIDADE DE PÁGINAS PARA 33 FINANÇAS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO À PÁGINAS INEXISTENTES
	$financas = sql_read($table='FINANCAS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 33 . ', 33', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-1"><?= $title ?></h1>
		</div>

<?php
	if(isset($financas) && !empty($financas)) { // HÁ FINANÇAS CADASTRADAS
?>
		<div class="row">
<?php
		foreach($financas as $f => $financa) { // PERCORRE A LISTA DE FINANÇAS
			if($f % 11 == 0) {
?>
			<div class="col m4 s4">
				<ul class="collection">
<?php
			}
?>
					<li class="collection-item">
						<a class="black-text" href="<?= $website . $financa['DOCUMENTO'] ?>">
<?php
			if(strstr(strtoupper($financa['TITULO']), 'TRIMESTRE')) { // DESTACA AS FINANÇAS TRIMESTRAIS
?>
							<h6><b><?= $financa['TITULO'] ?></b></h6>
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
			if(in_array($f, array(10, 21, 32))) {
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
	require_once('navegador.php');
	require_once('rodape.php');
?>