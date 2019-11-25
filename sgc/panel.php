<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/load.php');

	// VERIFICA SE O USUÁRIO ESTÁ AUTENTICADO
	if(!LOGGED_USER) {
		include_once(__DIR__ . '/index.php');
		return false;
	}
?>

<!DOCTYPE html>
<html lang="pt-br" style="min-height: 100%; position: relative;">

<head>
	<meta charset="utf-8"/>
	<meta content="Luiz Joaquim Aderaldo Amichi" name="author"/>
	<meta content="<?= SYSTEM_DESCRIPTION ?>" name="description"/>
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<title><?= SYSTEM_TITLE . ' - ' . CRUD_SUBTITLE . ' ' . MODELS_TITLES[REQUIRED_PAGE] ?></title>

	<link href="<?= BASE_URL ?>img/sgc.svg" rel="icon"/>
	<link href="<?= BASE_URL ?>css/materialize.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= BASE_URL ?>css/datatables.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= BASE_URL ?>css/richtext.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= BASE_URL ?>css/fontawesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?= BASE_URL ?>css/dark.css" rel="stylesheet" type="text/css"/>
</head>

<body class="grey lighten-5">
	<!-- MENU SUPERIOR -->
	<nav class="darken-4 green nav-extended">
		<div class="nav-wrapper">
			<a class="brand-logo" href="<?= BASE_URL ?>sgc/panel.php" title="Sistema de Gerenciamento de Conteúdo">
				<img alt="Sistema de Gerenciamento de Conteúdo" loading="lazy" src="<?= BASE_URL ?>img/sgc.svg" style="margin-left: 10px;" width="30"/>
			</a>
			<a class="sidenav-trigger" data-target="mobile-demo" href="javascript:void(0)">&#9776;</a>
			<ul class="dropdown-content" id="dropdown-menu">
				<li>
					<a href="<?= BASE_URL ?>" target="_blank">Website</a>
				</li>
				<li class="divider"></li>
				<li>
					<a class="change-theme" href="javascript:void(0)">Tema</a>
				</li>
				<li>
					<a class="renew-session" href="javascript:void(0)">Atualizar</a>
				</li>
				<li>
					<a class="logout-system red-text" href="javascript:void(0)">Sair</a>
				</li>
			</ul>
			<ul class="hide-on-med-and-down right">
<?php
	foreach(MODELS_TITLES as $m => $model) { // CRIAÇÃO DO MENU SUPERIOR
		if(LOGGED_USER[$m] ?? false) { // VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO
?>
				<li class="<?= REQUIRED_PAGE === $m ? 'active' : '' ?>">
					<a href="<?= BASE_URL ?>sgc/panel.php?action=LIST&page=<?= $m ?>"><?= $model ?></a>
				</li>
<?php
		} //! VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO
	} //! CRIAÇÃO DO MENU SUPERIOR
?>
				<li>
					<a class="dropdown-trigger" data-target="dropdown-menu" href="javascript:void(0)">Opções</a>
				</li>
			</ul>
			<ul class="sidenav" id="mobile-demo">
				<li>
					<div class="user-view">
						<div class="background darken-4 green"></div>
						<a href="javascript:void(0)"><img alt="<?= LOGGED_USER['NOME'] ?>" class="circle" loading="lazy" src="<?= BASE_URL ?>img/usuario.png" style="filter: hue-rotate(300deg);"/></a>
						<a href="javascript:void(0)"><span class="white-text name"><strong><?= LOGGED_USER['NOME'] ?></strong></span></a>
						<a href="javascript:void(0)"><span class="white-text email"><?= LOGGED_USER['EMAIL'] ?></span></a>
						<div class="right" style="margin-right: 5px;"></div>
					</div>
				</li>
<?php
	foreach(MODELS_TITLES as $m => $model) { // CRIAÇÃO DO MENU SUPERIOR (MOBILE)
		if(LOGGED_USER[$m] ?? false) { // VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO (MOBILE)
?>
				<li class="<?= REQUIRED_PAGE === $m ? 'active' : '' ?>">
					<a href="<?= BASE_URL ?>sgc/panel.php?action=LIST&page=<?= $m ?>"><?= $model ?></a>
				</li>
<?php
		} //! CRIAÇÃO DO MENU SUPERIOR (MOBILE)
	} //! VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO (MOBILE)
?>

				<li class="divider"></li>
				<li>
					<a href="<?= BASE_URL ?>" target="_blank">Website</a>
				</li>
				<li>
					<a class="renew-session" href="javascript:void(0)">Atualizar</a>
				</li>
				<li>
					<a class="darken-3 logout-system red white-text" href="javascript:void(0)">Sair</a>
				</li>
			</ul>
		</div>
	</nav>
	<!--/ MENU SUPERIOR -->

	<div class="progress" id="loading" style="display: none;">
		<div class="indeterminate"></div>
	</div>

	<div class="container" style="margin-bottom: 100px;">
		<blockquote class="green lighten-5">
			<h2>
<?php
	!REQUIRED_PAGE ?: require(SYSTEM_PAGES[REQUIRED_PAGE]);
	if(isset($favicon) && file_exists(__DIR__ . '/../' . $favicon)) { // EXIBIR O ÍCONE DO MODELO
?>
				<img alt="<?= $title ?>" class="image" loading="lazy" src="<?= BASE_URL . $favicon ?>" width="35"/>
<?php
	} //! EXIBIR O ÍCONE DO MODELO
?>
				<?= MODELS_TITLES[REQUIRED_PAGE] ?>

			</h2>
			<h5 class="teal-text"><?= REQUIRED_PAGE ? CRUD_SUBTITLE : SYSTEM_DESCRIPTION ?></h5>
		</blockquote>
		<br/>
<?php
	if(!in_array(REQUIRED_PAGE, SYSTEM_MODELS)) { // EXIBIR INFORMAÇÕES DO SERVIDOR
		$rows = sqlRead(table:'REGISTROS', condition: 'USUARIO = ' . LOGGED_USER['ID'] . ' ORDER BY ID DESC LIMIT 20');
?>
		<h5 class="center-align"><?= greetings() ?>, seja bem-vindo <strong title="Tempo de atividade: <?= substr(number_format(time() - ACTIVE_TIME, 2, '', '.'), 0, -2) ?> segundos"><?= LOGGED_USER['NOME'] ?></strong>.</h5>
		<br/>

		<div class="row">
<?php
		foreach($rows as $r => $row) {
			if(intdiv(count($rows) + 1, 2) === $r || $r === 0) { // EXIBIR PRIMEIRA COLUNA DE REGISTROS
?>
			<div class="col m6 s12">
<?php
			} //! EXIBIR PRIMEIRA COLUNA DE REGISTROS
?>
				<p><strong><?= date('d/m/Y - H:i:s', strtotime($row['DATA'])) ?>:</strong> <?= $row['OPERACAO'] ?> <a class="teal-text" href="<?= BASE_URL ?>sgc/panel.php?action=VIEW&page=<?= $row['TABELA'] ?>&column=ID&value=<?= $row['REGISTRO'] ?>" title="Visualizar registro"><?= $row['TABELA'] . ' (' . $row['REGISTRO'] . ')' ?></a></p>
<?php
			if(intdiv(count($rows) - 1, 2) === $r || count($rows) === $r + 1) { // EXIBIR SEGUNDA COLUNA DE REGISTROS
?>
			</div>
<?php
			} //! EXIBIR SEGUNDA COLUNA DE REGISTROS
?>
<?php
		} //! EXIBIR INFORMAÇÕES DO SERVIDOR
?>

		</div>

<?php
	} //! EXIBIR INFORMAÇÕES DO SERVIDOR
	elseif(in_array(REQUIRED_ACTION, ['CREATE', 'INSERT', 'UPDATE', 'CHANGE'])) { // INSERIR OU ALTERAR REGISTRO
		// VERIFICAR QUAL O TIPO DE AÇÃO REQUERIDA
		$action = in_array(REQUIRED_ACTION, ['CREATE', 'INSERT']) ? 'INSERT' : 'UPDATE';
		$fields = $action === 'INSERT' ? $insert : $update;

		// PROCURAR SE TEM O ATRIBUTO 'ID' E VERIFICAR O NÚMERO DISPONÍVEL NO BANCO DE DADOS
		$columns['ID']['default'] = isset($columns['ID']) ? sqlMax($table) + 1 : $columns['ID'];

		$row = $action === 'UPDATE' ? sqlRead(table: $table, condition: REQUIRED_COLUMN . ' = "' . REQUIRED_VALUE . '"', unique: true) : [];
?>

		<form enctype="multipart/form-data">

<?php
		foreach($fields as $i => $field) { // PERCORRER CAMPOS DE ATRIBUTOS
			if($field['tag'] === 'input') { // TAG DO TIPO INPUT
				if($field['type'] === 'file') { // INPUT DO TIPO FILE
?>

			<div class="file-field input-field">
				<div class="btn">
					<span><?= $columns[$i]['label'] ?></span>
					<input id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="file" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>/>
				</div>
				<div class="file-path-wrapper">
					<input class="file-path validate" placeholder="Enviar arquivo" type="text" value="<?= ($row[$i] ?? '') ?>"/>
				</div>
			</div>
			<br/>

<?php
				} //! INPUT DO TIPO FILE
				elseif($field['type'] === 'date') { // INPUT DO TIPO DATE
?>
			<div class="input-field col s12">
				<input class="datepicker validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="date" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= substr($row[$i] ?? $columns[$i]['default'], 0, 10) ?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>
<?php
				} //! INPUT DO TIPO DATE
				elseif($field['type'] === 'hidden') { // INPUT DO TIPO HIDDEN
?>
			<div>
				<input id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="hidden" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $row[$i] ?? $columns[$i]['default'] ?>"/>
			</div>
<?php
				} //! INPUT DO TIPO HIDDEN
				elseif($field['type'] === 'time') { // INPUT DO TIPO TIME
?>
			<div class="input-field col s12">
				<input class="timepicker validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="time" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= substr($row[$i] ?? $columns[$i]['default'], 0, 5) ?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>
<?php
				} //! INPUT DO TIPO TIME
				elseif($field['type'] === 'checkbox') { // INPUT DO TIPO CHECKBOX
?>

			<label><?= $columns[$i]['label'] ?></label>
			<p>

<?php
					foreach($field['labels'] as $l => $label) { // PERCORRER A LISTA DE VALORES
?>

				<label style="padding-right: 35px;">
				<input class="filled-in" <?= ($row[$field['names'][$l]] ?? '') ? 'checked' : '' ?> name="<?= $columns[$i]['name'] ?>" type="checkbox" value="<?= $field['values'][$l] ?>"/>
					<span><?= $label ?></span>
				</label>

<?php
					} //! PERCORRER A LISTA DE VALORES
?>

			</p>
			<br/>

<?php
				} //! INPUT DO TIPO CHECKBOX
				elseif($field['type'] === 'password') { // INPUT DO TIPO PASSWORD
?>
			<div class="input-field col s12">
			<input class="validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="password" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
				} //! INPUT DO TIPO PASSWORD
				else { // INPUT DOS TIPOS NUMBER, TEXT
?>

			<div class="input-field col s12">
				<input class="validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="<?= $field['type'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $row[$i] ?? $columns[$i]['default'] ?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
				} //! INPUT DOS TIPOS NUMBER, TEXT
			} //! TAG DO TIPO INPUT
			elseif($field['tag'] === 'select') { // TAG DO TIPO SELECT
?>

			<div class="input-field col s12">
				<select id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>>
					<option disabled value="">Selecione uma opção</option>
<?php
				foreach($field['options'] as $option) { // PERCORRER AS OPÇÕES DO CAMPO SELECT
?>
					<option <?= ($row[$i] ?? '') === $option['value'] ? 'selected' : '' ?> value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
<?php
				} //! PERCORRER AS OPÇÕES DO CAMPO SELECT
?>
				</select>
				<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
			} //! TAG DO TIPO SELECT
			elseif($field['tag'] === 'textarea') { // TAG DO TIPO TEXTAREA
?>

			<div class="input-field col s12">
			<textarea class="materialize-textarea" id="<?= $fields[$i]['id'] ?? $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>><?= $row[$i] ?? ' ' ?></textarea>
				<label for="<?= $fields[$i]['id'] ?? $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
			} //! TAG DO TIPO TEXTAREA
		} //! PERCORRER CAMPOS DE ATRIBUTOS
?>

			<div class="row">
				<div class="col m2 s6">
					<button class="btn darken-3 green waves-effect" style="z-index: 0;" type="submit">Enviar</button>
				</div>
				<div class="col m2 s6">
					<button class="btn grey lighten-1 waves-effect" id="cancelar" style="z-index: 0;" type="button">Cancelar</button>
				</div>
			</div>
		</form>

		<div class="fixed-action-btn">
			<a class="btn-floating btn-large modal-trigger pulse tooltipped" data-position="top" data-tooltip="Ajuda" id="help-button" href="#modal">
				<img alt="Ajuda" loading="lazy" src="<?= BASE_URL ?>img/ajuda.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>

		<div class="modal" id="modal">
			<div class="modal-content">
				<h4 class="center-align">Ajuda</h4>
				<p class="center-align"><em><?= $help ?></em></p>

<?php
		foreach($columns as $c => $column) { // EXIBIR ATRIBUTOS DOS MODELOS
?>
				<p><strong><?= $column['label'] ?>:</strong> <?= str_replace(' ,', '', trim(implode(', ', [isset($insert[$c]['type']) ? ['checkbox' => 'caixa de seleção', 'date' => 'data', 'email' => 'e-mail', 'file' => 'arquivo', 'hidden' => 'oculto', 'number' => 'número', 'password' => 'senha', 'tel' => 'telefone', 'text' => 'texto', 'time' => 'horário', 'url' => 'URL'][$insert[$c]['type']] : ['a' => 'âncora', 'audio' => 'áudio', 'iframe' => 'quadro', 'img' => 'imagem', 'input' => 'campo', 'p' => 'parágrafo', 'select' => 'selecionador', 'textarea' => 'área de texto'][$insert[$c]['tag']], isset($columns[$c]['unique']) && $columns[$c]['unique'] ? 'único' : '', isset($insert[$c]['attributes']['required']) ? 'obrigatório' : '', isset($insert[$c]['attributes']['maxlength']) ? 'tamanho máximo de ' . $insert[$c]['attributes']['maxlength'] . ' caracteres' : '', isset($insert[$c]['attributes']['minlength']) ? 'tamanho mínimo de ' . $insert[$c]['attributes']['minlength'] . ' caracteres' : '']), ' ,')) ?>.</p>
<?php
		} //! EXIBIR ATRIBUTOS DOS MODELOS
?>

			</div>
			<div class="modal-footer">
				<button class="btn-flat modal-close waves-effect waves-green">Fechar</button>
			</div>
		</div>

<?php
	} //! INSERIR OU ALTERAR REGISTRO
	elseif(in_array(REQUIRED_ACTION, ['LIST', 'SHOW'])) { // LISTAR REGISTROS
		// LER TODOS OS REGISTROS DO BANCO DE DADOS
		$rows = (array) sqlRead(table: $table);
?>
		<div class="center">
			<a class="btn darken-3 green waves-effect" href="<?= BASE_URL ?>sgc/panel.php?action=INSERT&page=<?= REQUIRED_PAGE ?>">Cadastrar</a>
		</div>

		<table class="highlight responsive-table striped">
			<caption></caption>
			<thead class="darken-3 green">
				<tr>

<?php
		foreach($list as $l => $field) { // PERCORRER CAMPOS DE ATRIBUTOS
?>

					<th scope="col" class="white-text"><?= $columns[$l]['label'] ?></th>

<?php
		} //! PERCORRER CAMPOS DE ATRIBUTOS
?>

					<th scope="col" class="white-text">GERENCIAR</th>
				</tr>
			</thead>
			<tbody>

<?php
		foreach($rows as $row) { // PERCORRER OS REGISTROS RECUPERADOS DO BANCO DE DADOS
?>
				<tr>
<?php
			foreach($list as $l => $field) { // PERCORRER ATRIBUTOS
				if(!isset($field['tag'])) { // EXIBIR CAMPO PERSONALIZADO
					$attr = array_map(function(string $a): string {
						global $columns, $row, $l;
						return isset($columns[$l][$a]['mask']) ? $columns[$l][$a]['mask'][$row[$a]] : $row[$a];
					}, $field);
?>
					<td><?= str_replace(' ,', '', trim(implode(', ', $attr), ' ,')) ?></td>
<?php
				} //! EXIBIR CAMPO PERSONALIZADO
				else {
?>
					<td><?= isset($columns[$l]['mask']) ? $columns[$l]['mask'][$row[$l]] : (isset($field['pretty']) && is_callable($field['pretty']) ? $field['pretty']($row[$l]) : strip_tags($row[$l])) ?></td>
<?php
				}
			} //! PERCORRER ATRIBUTOS
?>

					<td>
						<a href="<?= BASE_URL ?>sgc/panel.php?action=VIEW&page=<?= REQUIRED_PAGE ?>&column=ID&value=<?= $row['ID'] ?>"><span class="badge green new" data-badge-caption="Visualizar"></span></a>
						<a href="<?= BASE_URL ?>sgc/panel.php?action=UPDATE&page=<?= REQUIRED_PAGE ?>&column=ID&value=<?= $row['ID'] ?>"><span class="badge blue new" data-badge-caption="Alterar"></span></a>
						<a data-id="<?= $row['ID'] ?>" data-page="<?= mb_strtolower($table) ?>" data-table="<?= mb_strtolower($title) ?>" href="#"><span class="badge new red" data-badge-caption="Remover"></span></a>
					</td>

				</tr>

<?php
		} //! PERCORRER OS REGISTROS RECUPERADOS DO BANCO DE DADOS
?>

			</tbody>
		</table>
		<br/>

<?php
	} //! LISTAR REGISTROS
	elseif(in_array(REQUIRED_ACTION, ['SELECT', 'VIEW'])) { // VISUALIZAR REGISTRO
		// LER O REGISTRO DO BANCO DE DADOS A PARTIR DOS PARÂMETROS INFORMADOS
		$row = sqlRead(table: $table, condition: REQUIRED_COLUMN . ' = "' . REQUIRED_VALUE . '"', unique: true);

		if($row) { // CASO ENCONTROU UM REGISTRO
			foreach($view as $v => $tag) { // PERCORRER CAMPOS DE ATRIBUTOS
?>
		<h5><?= $columns[$v]['label'] ?></h5>
<?php
				if(!isset($tag['tag'])) { // EXIBIR CAMPO PERSONALIZADO
					$attr = array_map(function(string $a): string {
						global $columns, $row, $v;
						return isset($columns[$v][$a]['mask']) ? $columns[$v][$a]['mask'][$row[$a]] : $row[$a];
					}, $tag);
?>
		<p><?= str_replace(' ,', '', trim(implode(', ', $attr), ' ,')) ?></p>
<?php
				} //! EXIBIR CAMPO PERSONALIZADO
				elseif($tag['tag'] === 'p' && strlen($row[$v]) !== 0) { // EXIBIR PARÁGRAFO
?>
		<p><?= isset($columns[$v]['mask']) ? $columns[$v]['mask'][$row[$v]] : (isset($tag['pretty']) && is_callable($tag['pretty']) ? $tag['pretty']($row[$v]) : $row[$v]) ?></p>
<?php
				} //! EXIBIR PARÁGRAFO
				elseif($tag['tag'] === 'a' && strlen($row[$v]) !== 0) { // EXIBIR ÂNCORA
?>
		<a class="teal-text" href="<?= BASE_URL . $row[$v] ?>"><?= $row[$v] ?></a>
		<br/><br/>
<?php
				} //! EXIBIR ÂNCORA
				elseif($tag['tag'] === 'audio' && strlen($row[$v]) !== 0) { // EXIBIR ÁUDIO
?>
		<audio controls>
			<source src="<?= BASE_URL . $row[$v] ?>" type="audio/mp3"/>
			<?= $row[$v] ?>
		</audio>
<?php
				} //! EXIBIR ÁUDIO
				elseif($tag['tag'] === 'img' && strlen($row[$v]) !== 0) { // EXIBIR IMAGEM
?>
		<img alt="Figura" class="materialboxed" loading="lazy" src="<?= BASE_URL . $row[$v] ?>" width="300"/>
		<br/>
<?php
				} //! EXIBIR IMAGEM
				elseif($tag['tag'] === 'iframe' && strlen($row[$v]) !== 0) { // EXIBIR VÍDEO
					if(stristr($row[$v], 'youtube.com')) { // EXIBIR IFRAME DO YOUTUBE
?>
		<div class="video-container">
			<iframe allowfullscreen height="480" src="<?= str_replace('watch?v=', 'embed/', $row[$v]) ?>" title="YouTube" width="854"></iframe>
		</div>
		<a class="teal-text" href="<?= $row[$v] ?>"><?= $row[$v] ?></a>
		<br/><br/>
<?php
					} //! EXIBIR IFRAME DO YOUTUBE
					elseif(filter_var($row[$v], FILTER_VALIDATE_URL)) { // EXIBIR ÂNCORA DE UMA PÁGINA WEB
?>
		<a class="teal-text" href="<?= $row[$v] ?>"><?= $row[$v] ?></a>
		<br/><br/>
<?php
					} //! EXIBIR ÂNCORA DE UMA PÁGINA WEB
					else { // EXIBIR CAIXA DE VÍDEO
?>
		<video class="responsive-video" controls preload="metadata">
			<source src="<?= BASE_URL . $row[$v] ?>" type="video/mp4"/>
			<track kind="captions" label="Português (Brasil)" src="<?= BASE_URL ?>uploads/video.vtt" srclang="pt-br">
		</video>
		<br/>
		<a class="teal-text" href="<?= $row[$v] ?>"><?= $row[$v] ?></a>
		<br/><br/>
<?php
					} //! EXIBIR CAIXA DE VÍDEO
				} //! EXIBIR VÍDEO
				else { // EXIBIR VALOR NULO
?>
		<p class="grey-text">Indefinido</p>
<?php
				} //! EXIBIR VALOR NULO
			} //! PERCORRER CAMPOS DE ATRIBUTOS
?>
		<a class="blue btn waves-effect" href="<?= BASE_URL ?>sgc/panel.php?action=UPDATE&page=<?= REQUIRED_PAGE ?>&column=ID&value=<?= $row['ID'] ?>" style="z-index: 0;">Alterar</a>
		<a class="btn darken-3 red waves-effect" data-id="<?= $row['ID'] ?>" data-page="<?= mb_strtolower($table) ?>" data-table="<?= mb_strtolower($title) ?>" href="javascript:void(0)" style="z-index: 0;">Remover</a>
<?php
		} //! CASO ENCONTROU UM REGISTRO
		else { // O REGISTRO PARA VISUALIZAÇÃO É INVÁLIDO
?>
		<h5 class="teal-text">Não foi encontrado nenhum registro.</h5>
<?php
		} //! O REGISTRO PARA VISUALIZAÇÃO É INVÁLIDO
	} //! VISUALIZAR REGISTRO
?>
	</div>

	<footer class="darken-4 green page-footer" style="bottom: 0; position: absolute; width: 100%; margin: 0;">
		<div class="footer-copyright">
			<div class="container">
				Copyright &copy; 2017 - <?= date('Y') ?>. Todos os direitos reservados para Luiz Joaquim Aderaldo Amichi.
				<br/>
				<small title="Atualização: <?= RELEASE_DATE ?>">Versão <?= SYSTEM_VERSION ?></small>
				<a class="grey-text right text-lighten-4" href="https://luizamichi.com.br" target="_blank" title="Desenvolvido por Luiz Joaquim Aderaldo Amichi">
					<img alt="Luiz Joaquim Aderaldo Amichi" class="mx-2" loading="lazy" src="<?= BASE_URL ?>img/luizamichi.svg" width="30"/>
				</a>
			</div>
		</div>
	</footer>

	<script src="<?= BASE_URL ?>js/jquery.min.js"></script>
	<script src="<?= BASE_URL ?>js/materialize.min.js"></script>
	<script src="<?= BASE_URL ?>js/datatables.min.js"></script>
	<script src="<?= BASE_URL ?>js/jquery.mask.min.js"></script>
	<script src="<?= BASE_URL ?>js/jquery.richtext.min.js"></script>

	<script>
		// JQUERY
		$(document).ready(function() {
			// DROPDOWN MENU
			$(".dropdown-trigger").dropdown();

			// MENU LATERAL
			$(".sidenav").sidenav();

			// TOOLTIP
			$(".tooltipped").tooltip();

			// MODAL
			$(".modal").modal();

			// ANIMAÇÃO DO CARREGAMENTO DE PÁGINAS
			$("a").click(function(evento) {
				if(!($(this).attr("href")[0] === "#" || $(this).attr("href") === "javascript:void(0)") && !evento.ctrlKey && $(this).attr("target") !== "_blank")
					$("#loading").show();
			});

			// SELECT BOX
			$("select").formSelect();

			// ZOOM AO CLICAR NA IMAGEM
			$(".materialboxed").materialbox();

			// VISUALIZAÇÃO DE CALENDÁRIO
			$(".datepicker").datepicker({format: "yyyy-mm-dd"});
			$(".datepicker").prop("type", "text");

			// VISUALIZAÇÃO DE RELÓGIO
			$(".timepicker").timepicker({twelveHour: false});
			$(".timepicker").prop("type", "text");

			// TEXTAREA
			if($("textarea").length)
				M.textareaAutoResize($("textarea"));

			// ENVIAR TODOS OS CAMPOS NO FORMULÁRIO
			$("form").submit(function() {
				$("input[disabled=disabled]").each(function() {
					$(this).removeAttr("disabled");
				});
			});

			// ANIMAÇÃO DO BOTÃO FLUTUANTE DE AJUDA
			setTimeout(function() {
				$("#help-button").removeClass("pulse");
			}, 3000);

			// DATATABLES
			$("table").DataTable({
				"columnDefs": [{
					"targets": [1, 2, -1, -2, -3],
					"render": function(data, type, row) {
						return data.replace(/(<([^>]+)>)/gi, "").length > 500 ? data.substr(0, 497) + "..." : data;
					}
				}],
				"order": [[0, "desc"]]
			});

			// JANELA DE CONFIRMAÇÃO DE REMOÇÃO DE REGISTRO
			$("a").click(function() {
				if($(this).data("page")) {
					let response = confirm("Deseja remover o registro " + $(this).data("id") + " da tabela de " + $(this).data("table") + "?");

					if(response === true) {
						$.ajax({
							type: "get",
							url: "<?= BASE_URL ?>sgc/crud.php",
							dataType: "json",
							data: {
								action: "REMOVE",
								page: $(this).data("page"),
								id: $(this).data("id")
							},
							beforeSend: function() {
								$("button, input, select, textarea").attr("disabled", true);
								$("#loading").show();
								window.scrollTo(0, 0);
							},
							success: function(response) {
								alert(response.message ?? "Você será redirecionado.");
								window.location.href = "<?= BASE_URL ?>sgc/panel.php?action=LIST&page=<?= REQUIRED_PAGE ?>";
							},
							error: function(response) {
								alert(response.responseJSON && response.responseJSON.message);
							},
							complete: function() {
								$("button, input, select, textarea").attr("disabled", false);
								$("#loading").hide();
							}
						});
					}
				}
			});

			// RENOVAÇÃO DE SESSÃO / LOGOUT
			$(".logout-system, .renew-session").click(function() {
				json = $(this).hasClass("renew-session") ? { renew: 1 } : { logout: 1};
				$.ajax({
					type: "get",
					url: "<?= BASE_URL ?>sgc/login.php",
					dataType: "json",
					data: json,
					beforeSend: function() {
						$("#loading").show();
					},
					success: function(response) {
						alert(response.message ?? "Atualizeremos a página.");
						window.location.reload();
					},
					error: function(response) {
						alert(response.responseJSON && response.responseJSON.message);
					},
					complete: function() {
						$("#loading").hide();
					}
				});
			});

			// ALTERAÇÃO DE TEMA
			const changeTheme = function(change = true) {
				if(change) {
					localStorage.setItem("mode", (localStorage.getItem("mode") || "dark") === "dark" ? "light" : "dark");
				}
				if(localStorage.getItem("mode") === "dark") {
					$("body").removeClass("grey lighten-5");
					$("body").addClass("dark");
				}
				else {
					$("body").removeClass("dark");
					$("body").addClass("grey lighten-5");
				}
			}
			$(".change-theme").click(function() {
				changeTheme(true);
			});
			changeTheme(false);

			// SELECT BOX
			$("select").formSelect();

			// RETORNAR PARA A PÁGINA ANTERIOR
			$("#cancelar").click(function() {
				window.history.back();
			});

			// EXIBIR DADOS DO(S) ARQUIVO(S) ENVIADO(S) ANTERIORMENTE
			$("input[type=file]").change(function() {
				let files = $(this)[0].files.length;
				if(files === 1)
					$(this).siblings(".file-name").html($(this).val().split("\\").pop());
				else
					$(this).siblings(".file-name").html(files + " arquivos selecionados");
			});

			// CONTADOR DE CARACTERES
			$("input[type=email], input[type=password], input[type=text], textarea").characterCounter();

			// EDITOR DE TEXTOS
			$("#richtexteditor").richText();

			// OPERAÇÃO DE INSERÇÃO/ALTERAÇÃO DE REGISTRO
			$("form").submit(function(event) {
				event.preventDefault();
				let formData = new FormData($("form").get(0));
				$.ajax({
					type: "post",
					url: "<?= BASE_URL ?>sgc/crud.php?action=<?= REQUIRED_ACTION ?>&page=<?= REQUIRED_PAGE ?>",
					data: formData,
					processData: false,
					contentType: false,
					beforeSend: function() {
						$("button, input, select, textarea").attr("disabled", true);
						$("#loading").show();
						window.scrollTo(0, 0);
					},
					success: function(response) {
						alert(response.message ?? "Você será redirecionado.");
						window.location.href = "<?= BASE_URL ?>sgc/panel.php?action=LIST&page=<?= REQUIRED_PAGE ?>";
					},
					error: function(response) {
						alert(response.responseJSON && response.responseJSON.message);
					},
					complete: function() {
						$("button, input, select, textarea").attr("disabled", false);
						$("#loading").hide();
					}
				});
			});
		});
	</script>

</body>

</html>
