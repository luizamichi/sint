<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$name = 'index.php';
	$title = 'Início';

	$aviso = (array) sqlRead(table: 'AVISOS', condition: 'INICIO <= "' . date('Y-m-d') . '" AND FIM >= "' . date('Y-m-d') . '" ORDER BY ID DESC LIMIT 1', unique: true);
	$banners = (array) sqlRead(table: 'BANNERS', condition: 'ID > 0 ORDER BY ID DESC LIMIT 5');
	$boletins = (array) sqlRead(table: 'BOLETINS', condition: 'ID > 0 ORDER BY ID DESC LIMIT 6');
	$editais = (array) sqlRead(table: 'EDITAIS', condition: 'ID > 0 ORDER BY ID DESC LIMIT 6');
	$eventos = (array) sqlRead(table: 'EVENTOS', condition: 'ID > 0 ORDER BY ID DESC LIMIT 3');
	$noticias = (array) sqlRead(table: 'NOTICIAS', condition: 'STATUS = 1 AND DATA <= "' . date('Y-m-d') . '" ORDER BY ID DESC LIMIT 29');
	$podcasts = (array) sqlRead(table: 'PODCASTS', condition: 'ID > 0 ORDER BY ID DESC LIMIT 1');

	$noticiasPodcasts = array_merge($noticias, $podcasts);
	usort($noticiasPodcasts, function(array $register1, array $register2): int {
		return (($register1['DATA'] . ' - ' . $register1['HORA']) > ($register2['DATA'] . ' - ' . $register2['HORA'])) ? -1 : 1;
	});
	$tuplas = array_merge($noticiasPodcasts, $editais, $boletins, $eventos);

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">

<?php
	if(!empty($aviso)) { // HÁ UM AVISO CADASTRADO
?>
		<div class="modal" id="aviso">
			<div class="modal-content">
				<h4 class="center-align"><?= $aviso['TITULO'] ?></h4>
				<p><?= nl2br($aviso['TEXTO']) ?></p>
			</div>
			<div class="modal-footer">
				<a class="btn-flat modal-close waves-effect waves-green" href="javascript:void(0)">Fechar</a>
			</div>
		</div>

<?php
	}
	if(!empty($banners)) { // HÁ BANNERS CADASTRADOS
?>
		<div class="carousel carousel-slider" data-indicators="true">
<?php
		foreach($banners as $banner) { // PERCORRE A LISTA DE BANNERS CADASTRADOS
?>
			<a class="carousel-item" href="<?= $banner['LINK'] ?: 'javascript:void(0)' ?>">
				<img alt="Banner" loading="lazy" src="<?= BASE_URL . $banner['IMAGEM'] ?>"/>
			</a>
<?php
		}
?>
		</div>

<?php
	}
	if(!empty($tuplas)) { // HÁ BOLETINS, EDITAIS, EVENTOS, NOTÍCIAS OU PODCASTS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($tuplas as $tupla) { // PERCORRE A LISTA DE REGISTROS
			if(array_key_exists('STATUS', $tupla)) { // SOMENTE AS NOTÍCIAS POSSUEM STATUS
				$tipo = 'noticias';
			}
			elseif(array_key_exists('IMAGENS', $tupla)) { // SOMENTE OS EVENTOS POSSUEM DIRETÓRIO DE IMAGENS
				$tipo = 'eventos';
			}
			elseif(array_key_exists('AUDIO', $tupla)) { // SOMENTE OS PODCASTS POSSUEM ÁUDIO
				$tipo = 'podcasts';
			}
			elseif(array_key_exists('TEXTO', $tupla)) { // SOMENTE OS EDITAIS E NOTÍCIAS (DESCARTADAS ANTERIORMENTE) POSSUEM TEXTO
				$tipo = 'editais';
			}
			else {
				$tipo = 'boletins';
			}
?>
			<div class="col m4 s12">
				<a href="<?= BASE_URL . $tipo ?>.php?id=<?= rtrim(strtr(base64_encode($tupla['ID']), '+/', '-_'), '=') ?>">
					<div class="card small">
<?php
			if(isset($tupla['IMAGEM']) && !empty($tupla['IMAGEM'])) { // INSERE A IMAGEM DE CABEÇALHO DOS BOLETINS, EDITAIS E NOTÍCIAS
?>
						<div class="card-image">
							<img alt="Figura" loading="lazy" src="<?= BASE_URL . $tupla['IMAGEM'] ?>"/>
						</div>
<?php
			}
			elseif(isset($tupla['IMAGENS']) && is_dir($tupla['IMAGENS'])) { // INSERE A PRIMEIRA IMAGEM DOS EVENTOS
				$diretorio = scandir(__DIR__ . '/' . $tupla['IMAGENS']);
				if(array_slice($diretorio, 2)[0] ?? '') {
?>
						<div class="card-image">
							<img alt="Figura" loading="lazy" src="<?= BASE_URL . $tupla['IMAGENS'] . array_slice($diretorio, 2)[0] ?>"/>
						</div>
<?php
				}
			}
			else { // INSERE IMAGENS GENÉRICAS PARA AS NOTÍCIAS OU PODCASTS (OU EVENTOS SEM IMAGEM)
?>
						<div class="card-image">
							<img alt="Figura" loading="lazy" src="<?= match($tipo) {'noticias' => 'img/noticia.jpg', 'podcasts' => 'img/podcast.jpg', default => 'img/sinteemar.jpg'} ?>"/>
						</div>
<?php
			}
?>
						<div class="card-content">
							<span class="black-text card-title"><?= $tupla['TITULO'] ?></span>
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
	else { // AINDA NÃO HÁ BOLETINS, EDITAIS, EVENTOS, NOTÍCIAS OU PODCASTS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos conteúdo disponível :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
