<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'eventos.php';
	$title = 'Eventos';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$evento = sql_read($table='EVENTOS', $condition='ID=' . (int) base64_decode($id), $unique=true);
		$title = empty($evento) ? 'Eventos' : 'Eventos - ' . $evento['TITULO'];
	}
	else {
		$pages = ceil(sql_length($table='EVENTOS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 EVENTOS POR PÁGINA
		$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
		$eventos = sql_read($table='EVENTOS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24', $unique=false);
	}

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $evento['TITULO'] ?? $title ?></h1>
		</div>
<?php
	if(isset($evento) && !empty($evento)) { // EXIBE O EVENTO SOLICITADO
		if($evento['TEXTO']) {
?>
		<div id="text-content"><?= $evento['TEXTO'] ?></div>
		<div class="fixed-action-btn">
			<a class="btn-floating btn-large tooltipped" data-id="text-content" data-position="left" data-tooltip="Alterar o tamanho da fonte" id="button-toggle" href="javascript:void(0)">
				<img alt="Alterar o tamanho da fonte" src="img/fonte.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>
		<br/><br/>
<?php
		}
		if(is_dir($evento['IMAGENS'])) { // EXISTE O DIRETÓRIO COM AS IMAGENS
			foreach(array_slice(scandir($evento['IMAGENS']), 2) as $imagem) { // PERCORRE A LISTA DE IMAGENS
				if(in_array(pathinfo($imagem)['extension'], array('jpeg', 'jpg', 'png'))) { // BLOQUEIA A INSERÇÃO DE IMAGENS COM EXTENSÕES NÃO PERMITIDAS
?>
		<img alt="Evento" class="responsive-img" src="<?= $website . $evento['IMAGENS'] . $imagem ?>" width="232"/>
<?php
				}
			}
		}
	}
	elseif(isset($eventos) && !empty($eventos)) { // HÁ EVENTOS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($eventos as $evento) { // PERCORRE A LISTA DE EVENTOS
?>
			<div class="col m4 s6">
				<a href="<?= $website ?>eventos.php?id=<?= rtrim(strtr(base64_encode($evento['ID']), '+/', '-_'), '=') ?>">
					<div class="card hoverable small">
						<div class="card-content">
							<span class="black-text card-title"><?= $evento['TITULO'] ?></span>
							<div class="teal-text"><?= strip_tags($evento['TEXTO']) ?></div>
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
		if(isset($eventos) || empty($eventos)) { // AINDA NÃO HÁ EVENTOS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos eventos disponíveis :(</h3>
<?php
		}
		else { // FOI INFORMADO UM ID INVÁLIDO
?>
		<h3 class="center-align">Não foi encontrado o evento solicitado :(</h3>
<?php
		}
	}
?>
	</div>
<?php
	require_once('navegador.php');
	require_once('rodape.php');
?>