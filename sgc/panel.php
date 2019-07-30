<?php
	if(session_status() != PHP_SESSION_ACTIVE) { // ACESSO SOMENTE COM UMA SESSÃO CRIADA COM OS DADOS DO USUÁRIO
		session_name('SINTEEMAR');
		session_start();
	}

	if(isset($_SESSION['user'])) { // VERIFICA SE O USUÁRIO ESTÁ LOGADO
		$user = unserialize($_SESSION['user']);
	}
	else { // USUÁRIO NÃO AUTENTICADO
		header('Location: index.php');
		return false;
	}

	// DEFINE FUSO HORÁRIO E IDIOMA
	date_default_timezone_set('America/Sao_Paulo');
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese', 'pt_BR.iso-8859-1');

	// AÇÕES PERMITIDAS NO PAINEL DE GERENCIAMENTO
	$actions = array('list', 'insert', 'update', 'view');

	// SUBTÍTULOS DAS AÇÕES
	$subtitles = array('help'=> 'Ajuda', 'list'=> 'Listar', 'insert'=> 'Inserir', 'update'=> 'Atualizar', 'view'=> 'Visualizar');

	// NOME DOS ARQUIVOS DA PASTA DE MODELOS
	$pages = array_slice(scandir('models'), 2);

	// TÍTULOS DEFINIDOS PARA CADA MODELO
	$models = array_map(function($p) {
		include_once('models/' . $p);
		return $title;
	}, $pages);

	// PARÂMETROS RECEBIDOS VIA GET
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$column = filter_input(INPUT_GET, 'column', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$value = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

	// DATA ACCESS OBJECT
	require('dao.php');

	if(in_array($action, $actions) && in_array($page . '.php', $pages)) { // AÇÃO E PÁGINA VÁLIDOS
		include('models/' . $page . '.php');
		if($column && $value && !(array_key_exists(strtoupper($column), $columns))) { // INFORMOU A COLUNA E VALOR PARA VISUALIZAÇÃO, PORÉM A COLUNA ESTÁ ERRADA
			$action = 'logged';
			$title = 'Sistema de Gerenciamento de Conteúdo';
			$subtitles[$action] = 'O SGC é um sistema gestor de websites, portais e intranets que integra ferramentas necessárias para criar, gerir (editar e remover) conteúdos em tempo real, sem a necessidade de programação de código, cujo objetivo é estruturar e facilitar a criação, administração, distribuição, publicação e disponibilidade da informação. A sua maior característica é a grande quantidade de funções presentes.';
		}
		if(!$user[$table]) { // PERMISSÃO NÃO CONCEDIDA AO USUÁRIO
			$action = 'blocked';
			$subtitles[$action] = 'Você não possui permissão para acessar esta página e suas funcionalidades.';
		}
	}

	else { // PÁGINA INICIAL OU PÁGINA DE AJUDA
		$action = strcmp($action, 'help') == 0 ? 'help' : 'logged';
		$title = strcmp($action, 'help') == 0 ? 'Ajuda' : 'Sistema de Gerenciamento de Conteúdo';
		$subtitles[$action] = strcmp($action, 'help') == 0 ? 'Especificações de propriedades dos registros.' : 'O SGC é um sistema gestor de websites, portais e intranets que integra ferramentas necessárias para criar, gerir (editar e remover) conteúdos em tempo real, sem a necessidade de programação de código, cujo objetivo é estruturar e facilitar a criação, administração, distribuição, publicação e disponibilidade da informação. A sua maior característica é a grande quantidade de funções presentes.';
	}
?>

<!DOCTYPE html>
<html lang="pt-br" style="min-height: 100%; position: relative;">

<head>
	<meta charset="utf-8"/>
	<meta content="Luiz Joaquim Aderaldo Amichi" name="author"/>
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<title>Sistema de Gerenciamento de Conteúdo - <?= $title ?></title>

	<link href="../img/sgc.svg" rel="icon"/>
	<link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
<?php
	if(isset($action) && strcmp($action, 'list') == 0) { // DATATABLES
?>
	<link href="../css/datatables.min.css" rel="stylesheet" type="text/css"/>
<?php
	} //! DATATABLES
	elseif(isset($action) && in_array($action, array('insert', 'update'))) { // RICH TEXT EDITOR
?>
	<link href="../css/richtext.min.css" rel="stylesheet" type="text/css"/>
	<link href="../css/fontawesome.min.css" rel="stylesheet" type="text/css"/>
<?php
	} //! RICH TEXT EDITOR
?>
</head>

<body class="grey lighten-5">
	<!-- MENU SUPERIOR -->
	<nav class="darken-4 green nav-extended">
		<div class="nav-wrapper">
			<a class="brand-logo" href="panel.php" title="Sistema de Gerenciamento de Conteúdo">
				<img alt="Sistema de Gerenciamento de Conteúdo" src="../img/sgc.svg" style="margin-left: 10px;" width="30"/>
			</a>
			<a class="sidenav-trigger" data-target="mobile-demo" href="javascript:void(0)">&#9776;</a>
			<ul class="dropdown-content" id="dropdown-menu">
				<li class="<?= strcmp($action, 'help') == 0 ? 'active' : '' ?>">
					<a href="?action=help">Ajuda</a>
				</li>
				<li>
					<a href="../index.php" target="_blank">Website</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="index.php?renew=1">Atualizar</a>
				</li>
				<li>
					<a class="red-text" href="index.php?logout=1">Sair</a>
				</li>
			</ul>
			<ul class="hide-on-med-and-down right">
<?php
	foreach($models as $m => $model) { // CRIAÇÃO DO MENU SUPERIOR
		if($user[strtoupper(explode('.php', $pages[$m])[0])]) { // VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO
?>
				<li class="<?= $page == explode('.php', $pages[$m])[0] ? 'active' : '' ?>">
					<a href="?action=list&page=<?= explode('.php', $pages[$m])[0] ?>"><?= $model ?></a>
				</li>
<?php
		} //! VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO
	} //! CRIAÇÃO DO MENU SUPERIOR
?>
				<li class="<?= strcmp($action, 'help') == 0 ? 'active' : '' ?>">
					<a class="dropdown-trigger" data-target="dropdown-menu" href="javascript:void(0)">Opções</a>
				</li>
			</ul>
			<ul class="sidenav" id="mobile-demo">
				<li>
					<div class="user-view">
						<div class="background darken-4 green"></div>
						<a href="javascript:void(0)"><img alt="<?= $user['NOME'] ?>" class="circle" src="../img/usuario.png" style="filter: hue-rotate(300deg);"></a>
						<a href="javascript:void(0)"><span class="white-text name"><strong><?= $user['NOME'] ?></strong></span></a>
						<a href="javascript:void(0)"><span class="white-text email"><?= $user['EMAIL'] ?></span></a>
					</div>
				</li>
<?php
	foreach($models as $m => $model) { // CRIAÇÃO DO MENU SUPERIOR (MOBILE)
		if($user[strtoupper(explode('.php', $pages[$m])[0])]) { // VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO (MOBILE)
?>
				<li class="<?= $page == explode('.php', $pages[$m])[0] ? 'active' : '' ?>">
					<a href="?action=list&page=<?= explode('.php', $pages[$m])[0] ?>"><?= $model ?></a>
				</li>
<?php
		} //! CRIAÇÃO DO MENU SUPERIOR (MOBILE)
	} //! VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO (MOBILE)
?>

				<li class="divider"></li>
				<li class="<?= strcmp($action, 'help') == 0 ? 'active' : '' ?>">
					<a href="?action=help">Ajuda</a>
				</li>
				<li>
					<a href="../index.php" target="_blank">Website</a>
				</li>
				<li>
					<a href="index.php?renew=1">Atualizar</a>
				</li>
				<li>
					<a class="darken-3 red white-text" href="index.php?logout=1">Sair</a>
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
	if(isset($favicon) && file_exists('../' . $favicon)) { // EXIBIR O ÍCONE DO MODELO
?>
				<img alt="<?= $title ?>" class="image" src="../<?= $favicon ?>" width="35"/>
<?php
	} //! EXIBIR O ÍCONE DO MODELO
?>
				<?= $title ?>

			</h2>
			<h5 class="teal-text"><?= $subtitles[$action] ?></h5>
		</blockquote>
		<br/>
<?php
	if(strcmp($action, 'logged') == 0) { // EXIBIR INFORMAÇÕES DO SERVIDOR
		$rows = array_slice((array) sql_read($table='REGISTROS', $condition='USUARIO=' . $user['ID'] . ' ORDER BY ID DESC', $unique=false), 0, 20);
?>
		<h5 class="center-align">Bem-vindo, <strong><?= $user['NOME'] ?></strong>.</h5>
		<br/>

		<div class="row">
<?php
		foreach($rows as $r => $row) {
			if(intdiv(count($rows) + 1, 2) == $r || $r == 0) { // EXIBIR PRIMEIRA COLUNA DE REGISTROS
?>
			<div class="col m6 s12">
<?php
			} //! EXIBIR PRIMEIRA COLUNA DE REGISTROS
?>
				<p><strong><?= date_format(new DateTime($row['DATA']), 'd/m/Y - H:i:s') ?>:</strong> <?= $row['OPERACAO'] ?> <a class="teal-text" href="?action=view&page=<?= strtolower($row['TABELA']) ?>&column=id&value=<?= $row['REGISTRO'] ?>" title="Visualizar registro"><?= $row['TABELA'] . ' (' . $row['REGISTRO'] . ')' ?></a></p>
<?php
			if(intdiv(count($rows) - 1, 2) == $r || count($rows) == $r + 1) { // EXIBIR SEGUNDA COLUNA DE REGISTROS
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
?>
<?php
	if(strcmp($action, 'help') == 0) { // EXIBIR INFORMAÇÕES DE AJUDA
		foreach($pages as $p) { // PERCORRER MODELOS
			include('models/' . $p);
?>
		<h5 id="<?= strtolower($table) ?>"><?= $title ?></h5>
		<p><em><a class="teal-text" href="?action=list&page=<?= strtolower($table) ?>">Visualizar</a></em></p>
		<p><em><?= $help ?></em></p>
<?php
			foreach($columns as $c => $column) { // EXIBIR ATRIBUTOS DOS MODELOS
?>
		<p><strong><?= $column['label'] ?>:</strong> <?= str_replace(' ,', '', trim(implode(', ', [isset($insert[$c]['type']) ? ['checkbox'=> 'caixa de seleção', 'date'=> 'data', 'email'=> 'e-mail', 'file'=> 'arquivo', 'hidden'=> 'oculto', 'number'=> 'número', 'password'=> 'senha', 'tel'=> 'telefone', 'text'=> 'texto', 'time'=> 'horário', 'url'=> 'URL'][$insert[$c]['type']] : ['a'=> 'âncora', 'audio'=> 'áudio', 'iframe'=> 'quadro', 'img'=> 'imagem', 'input'=> 'campo', 'p'=> 'parágrafo', 'select'=> 'selecionador', 'textarea'=> 'área de texto'][$insert[$c]['tag']], isset($columns[$c]['unique']) && $columns[$c]['unique'] ? 'único' : '', isset($insert[$c]['attributes']['required']) ? 'obrigatório' : '', isset($insert[$c]['attributes']['maxlength']) ? 'tamanho máximo de ' . $insert[$c]['attributes']['maxlength'] . ' caracteres' : '', isset($insert[$c]['attributes']['minlength']) ? 'tamanho mínimo de ' . $insert[$c]['attributes']['minlength'] . ' caracteres' : '']), ' ,')) ?>.</p>
<?php
			} //! EXIBIR ATRIBUTOS DOS MODELOS
?>
		<div class="divider"></div>

<?php
		} //! PERCORRER MODELOS
	} //! EXIBIR INFORMAÇÕES DE AJUDA
	elseif(strcmp($action, 'insert') == 0) { // INSERIR REGISTRO
		// PROCURAR SE TEM O ATRIBUTO 'ID' E VERIFICAR O NÚMERO DISPONÍVEL NO BANCO DE DADOS
		$columns['ID']['default'] = isset($columns['ID']) ? sql_max($table) + 1 : $columns['ID'];
?>

		<form action="crud.php?action=insert&page=<?= $page ?>" enctype="multipart/form-data" method="post">

<?php
		foreach($insert as $i => $field) { // PERCORRER CAMPOS DE ATRIBUTOS
			if(strcmp($field['tag'], 'input') == 0) { // TAG DO TIPO INPUT
				if(strcmp($field['type'], 'file') == 0) { // INPUT DO TIPO FILE
?>
			<div class="file-field input-field">
				<div class="btn">
					<span><?= $columns[$i]['label'] ?></span>
					<input id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="file" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>/>
				</div>
				<div class="file-path-wrapper">
					<input class="file-path validate" placeholder="Enviar arquivo" type="text"/>
				</div>
			</div>
			<br/>

<?php
				} //! INPUT DO TIPO FILE
				elseif(strcmp($field['type'], 'date') == 0) { // INPUT DO TIPO DATE
?>
			<div class="input-field col s12">
				<input class="datepicker validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="date" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>
<?php
				} //! INPUT DO TIPO DATE
				elseif(strcmp($field['type'], 'hidden') == 0) { // INPUT DO TIPO HIDDEN
?>
			<div>
				<input id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="hidden" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
			</div>
<?php
				} //! INPUT DO TIPO HIDDEN
				elseif(strcmp($field['type'], 'time') == 0) { // INPUT DO TIPO TIME
?>
			<div class="input-field col s12">
				<input class="timepicker validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="time" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>
<?php
				} //! INPUT DO TIPO TIME
				elseif(strcmp($field['type'], 'checkbox') == 0) { // INPUT DO TIPO CHECKBOX
?>

			<label><?= $columns[$i]['label'] ?></label>
			<p>

<?php
					foreach($field['labels'] as $l => $label) { // PERCORRER A LISTA DE VALORES
?>

				<label style="padding-right: 35px;">
					<input class="filled-in" name="<?= $columns[$i]['name'] ?>" type="checkbox" value="<?= $field['values'][$l] ?>"/>
					<span><?= $label ?></span>
				</label>

<?php
					} //! PERCORRER A LISTA DE VALORES
?>

			</p>
			<br/>

<?php
				} //! INPUT DO TIPO CHECKBOX
				else { // INPUT DOS TIPOS NUMBER, PASSWORD, TEXT
?>

			<div class="input-field col s12">
				<input class="validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="<?= $field['type'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
				} //! INPUT DOS TIPOS NUMBER, PASSWORD, TEXT
			} //! TAG DO TIPO INPUT
			elseif(strcmp($field['tag'], 'select') == 0) { // TAG DO TIPO SELECT
?>

			<div class="input-field col s12">
				<select id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>>
					<option disabled value="">Selecione uma opção</option>
<?php
				foreach($field['options'] as $option) { // PERCORRER AS OPÇÕES DO CAMPO SELECT
?>
					<option value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
<?php
				} //! PERCORRER AS OPÇÕES DO CAMPO SELECT
?>
				</select>
				<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
			} //! TAG DO TIPO SELECT
			elseif(strcmp($field['tag'], 'textarea') == 0) { // TAG DO TIPO TEXTAREA
?>

			<div class="input-field col s12">
				<textarea class="materialize-textarea" id="<?= $insert[$i]['id'] ?? $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>> </textarea>
				<label for="<?= $insert[$i]['id'] ?? $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
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
			<a class="btn-floating btn-large pulse tooltipped" data-position="top" data-tooltip="Ajuda" id="help-button" href="?action=help#<?= strtolower($table) ?>">
				<img alt="Ajuda" src="../img/ajuda.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>

<?php
	} //! INSERIR REGISTROS
	elseif(strcmp($action, 'list') == 0) { // LISTAR REGISTROS
		// LER TODOS OS REGISTROS DO BANCO DE DADOS
		$rows = (array) sql_read($table=$table, $condition=null, $unique=false);
?>
		<div class="center">
			<a class="btn darken-3 green waves-effect" href="panel.php?action=insert&page=<?= $page ?>">Cadastrar</a>
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
					$attr = array_map(function($a) {
						global $columns, $row, $l;
						return isset($columns[$l][$a]['mask']) ? $columns[$l][$a]['mask'][$row[$a]] : $row[$a];
					}, $field);
?>
					<td><?= str_replace(' ,', '', trim(implode(', ', $attr), ' ,')) ?></td>
<?php
				} //! EXIBIR CAMPO PERSONALIZADO
				else {
?>
					<td><?= isset($columns[$l]['mask']) ? $columns[$l]['mask'][$row[$l]] : strip_tags($row[$l]) ?></td>
<?php
				}
			} //! PERCORRER ATRIBUTOS
?>

					<td>
						<a href="panel.php?action=view&page=<?= $page ?>&column=id&value=<?= $row['ID'] ?>"><span class="badge green new" data-badge-caption="Visualizar"></span></a>
						<a href="panel.php?action=update&page=<?= $page ?>&column=id&value=<?= $row['ID'] ?>"><span class="badge blue new" data-badge-caption="Alterar"></span></a>
						<a data-id="<?= $row['ID'] ?>" data-page="<?= strtolower($table) ?>" data-table="<?= strtolower($title) ?>" href="#"><span class="badge new red" data-badge-caption="Remover"></span></a>
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
	elseif(strcmp($action, 'update') == 0) { // ALTERAR REGISTRO
		$row = sql_read($table=$table, $condition=strtoupper($column) . '="' . $value . '"', $unique=true);
		if($row) { // O REGISTRO PARA ALTERAÇÃO É VÁLIDO
?>

		<form action="crud.php?action=update&page=<?= $page ?>&id=<?= $row['ID'] ?>" enctype="multipart/form-data" method="post">

<?php
			foreach($update as $i => $field) { // PERCORRER CAMPOS DE ATRIBUTOS
				if(strcmp($field['tag'], 'input') == 0) { // TAG DO TIPO INPUT
					if(strcmp($field['type'], 'file') == 0) { // INPUT DO TIPO FILE
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
					elseif(strcmp($field['type'], 'date') == 0) { // INPUT DO TIPO DATE
?>
			<div class="input-field col s12">
				<input class="datepicker validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="date" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>
<?php
					} //! INPUT DO TIPO DATE
					elseif(strcmp($field['type'], 'hidden') == 0) { // INPUT DO TIPO HIDDEN
?>
			<div>
				<input id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="hidden" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
			</div>
<?php
					} //! INPUT DO TIPO HIDDEN
					elseif(strcmp($field['type'], 'time') == 0) { // INPUT DO TIPO TIME
?>
			<div class="input-field col s12">
				<input class="timepicker validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="time" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>
<?php
					} //! INPUT DO TIPO TIME
					elseif(strcmp($field['type'], 'checkbox') == 0) { // INPUT DO TIPO CHECKBOX
?>

			<label><?= $columns[$i]['label'] ?></label>
			<p>

<?php
						foreach($field['labels'] as $l => $label) { // PERCORRER A LISTA DE VALORES
?>

				<label style="padding-right: 35px;">
					<input class="filled-in" <?= $row[$field['names'][$l]] ? 'checked' : '' ?> name="<?= $columns[$i]['name'] ?>" type="checkbox" value="<?= $field['values'][$l] ?>"/>
					<span><?= $label ?></span>
				</label>

<?php
						} //! PERCORRER A LISTA DE VALORES
?>

			</p>
			<br/>

<?php
					} //! INPUT DO TIPO CHECKBOX
					elseif(strcmp($field['type'], 'password') == 0) { // INPUT DO TIPO PASSWORD
?>
			<div class="input-field col s12">
			<input class="validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="password" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
					} //! INPUT DO TIPO PASSWORD
					else { // INPUT DOS TIPOS DATE, NUMBER, TEXT, TIME
?>

			<div class="input-field col s12">
				<input class="validate" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="<?= $field['type'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $row[$i] ?? ''?>"/>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
					} //! INPUT DOS TIPOS DATE, NUMBER, TEXT, TIME
				} //! TAG DO TIPO INPUT
				elseif(strcmp($field['tag'], 'select') == 0) { // TAG DO TIPO SELECT
?>

			<div class="input-field col s12">
				<select id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>>
					<option disabled value="">Selecione uma opção</option>
<?php
					foreach($field['options'] as $option) { // PERCORRER AS OPÇÕES DO CAMPO SELECT
?>
					<option <?= $row[$i] == $option['value'] ? 'selected' : '' ?> value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
<?php
					} //! PERCORRER AS OPÇÕES DO CAMPO SELECT
?>
				</select>
				<label for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
			</div>
			<br/>

<?php
				} //! TAG DO TIPO SELECT
				elseif(strcmp($field['tag'], 'textarea') == 0) { // TAG DO TIPO TEXTAREA
?>

			<div class="input-field col s12">
			<textarea class="materialize-textarea" id="<?= $update[$i]['id'] ?? $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map('associative', array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>><?= $row[$i] ?? ''?></textarea>
				<label for="<?= $update[$i]['id'] ?? $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
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
			<a class="btn-floating btn-large pulse tooltipped" data-position="top" data-tooltip="Ajuda" id="help-button" href="?action=help#<?= strtolower($table) ?>">
				<img alt="Ajuda" src="../img/ajuda.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>

<?php
		} //! O REGISTRO PARA ALTERAÇÃO É VÁLIDO
		else { // O REGISTRO PARA ALTERAÇÃO É INVÁLIDO
?>
		<h5 class="teal-text">Não foi encontrado nenhum registro.</h5>
<?php
		} //! O REGISTRO PARA ALTERAÇÃO É INVÁLIDO
	} //! ALTERAR REGISTRO
	elseif(strcmp($action, 'view') == 0) { // VISUALIZAR REGISTRO
		// LER O REGISTRO DO BANCO DE DADOS A PARTIR DOS PARÂMETROS INFORMADOS
		$row = sql_read($table=$table, $condition=strtoupper($column) . '="' . $value . '"', $unique=true);
		if($row) { // CASO ENCONTROU UM REGISTRO
			foreach($view as $v => $tag) { // PERCORRER CAMPOS DE ATRIBUTOS
?>
		<h5><?= $columns[$v]['label'] ?></h5>
<?php
				if(!isset($tag['tag'])) { // EXIBIR CAMPO PERSONALIZADO
					$attr = array_map(function($a) {
						global $columns, $row, $v;
						return isset($columns[$v][$a]['mask']) ? $columns[$v][$a]['mask'][$row[$a]] : $row[$a];
					}, $tag);
?>
		<p><?= str_replace(' ,', '', trim(implode(', ', $attr), ' ,')) ?></p>
<?php
				} //! EXIBIR CAMPO PERSONALIZADO
				elseif(strcmp($tag['tag'], 'p') == 0 && strlen($row[$v]) != 0) { // EXIBIR PARÁGRAFO
?>
		<p><?= isset($columns[$v]['mask']) ? $columns[$v]['mask'][$row[$v]] : $row[$v] ?></p>
<?php
				} //! EXIBIR PARÁGRAFO
				elseif(strcmp($tag['tag'], 'a') == 0 && strlen($row[$v]) != 0) { // EXIBIR ÂNCORA
?>
		<a class="teal-text" href="../<?= $row[$v] ?>"><?= $row[$v] ?></a>
		<br/><br/>
<?php
				} //! EXIBIR ÂNCORA
				elseif(strcmp($tag['tag'], 'audio') == 0 && strlen($row[$v]) != 0) { // EXIBIR ÁUDIO
?>
		<audio controls>
			<source src="../<?= $row[$v] ?>" type="audio/mp3"/>
			<?= $row[$v] ?>
		</audio>
<?php
				} //! EXIBIR ÁUDIO
				elseif(strcmp($tag['tag'], 'img') == 0 && strlen($row[$v]) != 0) { // EXIBIR IMAGEM
?>
		<img alt="Imagem" class="materialboxed" src="../<?= $row[$v] ?>" width="300"/>
		<br/>
<?php
				} //! EXIBIR IMAGEM
				elseif(strcmp($tag['tag'], 'iframe') == 0 && strlen($row[$v]) != 0) { // EXIBIR VÍDEO
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
			<source src="../<?= $row[$v] ?>" type="video/mp4"/>
			<track kind="captions" label="Português (Brasil)" src="../uploads/video.vtt" srclang="pt-br">
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
		<a class="blue btn waves-effect" href="panel.php?action=update&page=<?= $page ?>&column=id&value=<?= $row['ID'] ?>" style="z-index: 0;">Alterar</a>
		<a class="btn darken-3 red waves-effect" data-id="<?= $row['ID'] ?>" data-page="<?= strtolower($table) ?>" data-table="<?= strtolower($title) ?>" href="javascript:void(0)" style="z-index: 0;">Remover</a>
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
				<small title="Atualização: 01/12/2021">Versão 1.2.0</small>
				<a class="grey-text right text-lighten-4" href="https://luizamichi.com.br" target="_blank" title="Desenvolvido por Luiz Joaquim Aderaldo Amichi"><img alt="Luiz Joaquim Aderaldo Amichi" class="mx-2" src="../img/luizamichi.svg" width="30"/></a>
			</div>
		</div>
	</footer>

	<script src="../js/jquery.min.js"></script>
	<script src="../js/materialize.min.js"></script>
<?php
	if(isset($action) && strcmp($action, 'list') == 0) { // DATATABLES
?>
	<script src="../js/datatables.min.js"></script>
<?php
	} //! DATATABLES
	elseif(isset($action) && in_array($action, array('insert', 'update'))) { // RICH TEXT EDITOR E MASK PLUGIN
?>
	<script src="../js/jquery.mask.min.js"></script>
	<script src="../js/jquery.richtext.min.js"></script>
<?php
	} //! RICH TEXT EDITOR E MASK PLUGIN
?>
	<script>
		// JQUERY
		$(document).ready(function() {
			// DROPDOWN MENU
			$(".dropdown-trigger").dropdown();

			// MENU LATERAL
			$(".sidenav").sidenav();

			// TOOLTIP
			$(".tooltipped").tooltip();

			// ANIMAÇÃO DO CARREGAMENTO DE PÁGINAS
			$("a").click(function(evento) {
				if(!($(this).attr("href") == "#" || $(this).attr("href") == "javascript:void(0)") && !evento.ctrlKey && $(this).attr("target") != "_blank")
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

			// ANIMAÇÃO BOTÃO FLUTUANTE DE AJUDA
			setTimeout(function() {
				$("#help-button").removeClass("pulse");
			}, 3000);

<?php
	if(isset($action) && in_array($action, array('list', 'view'))) { // DATATABLES E CONFIRMAÇÃO DE REMOÇÃO
		if(strcmp($action, 'list') == 0) { // DATATABLES
?>
			// DATATABLES
			$("table").DataTable({
				"columnDefs": [{
					"targets": [1, 2, -1, -2, -3],
					"render": function(data, type, row) {
						return data.length > 500 ? data.substr(0, 497) + "..." : data;
					}
				}],
				"order": [[0, "desc"]]
			});
<?php
		} //! DATATABLES
?>

			// JANELA DE CONFIRMAÇÃO DE REMOÇÃO DE REGISTRO
			$("a").click(function() {
				if($(this).data("page")) {
					var response = confirm("Deseja remover o registro "+ $(this).data("id") + " da tabela de "+ $(this).data("table") +"?");
					if(response == true)
						window.location.href = "crud.php?action=remove&page=" + $(this).data("page") + "&id=" + $(this).data("id");
				}
			});

			// SELECT BOX
			$("select").formSelect();
<?php
	} //! DATATABLES E CONFIRMAÇÃO DE REMOÇÃO
	elseif(isset($action) && in_array($action, array('insert', 'update'))) { // ANIMAÇÃO PARA UPLOAD DE ARQUIVOS E RICH TEXT EDITOR
?>
			// RETORNAR PARA A PÁGINA ANTERIOR
			$("#cancelar").click(function() {
				window.history.back();
			});

			// EXIBIR DADOS DO(S) ARQUIVO(S) ENVIADO(S) ANTERIORMENTE
			$("input[type=file]").change(function() {
				var files = $(this)[0].files.length;
				if(files == 1)
					$(this).siblings(".file-name").html($(this).val().split("\\").pop());
				else
					$(this).siblings(".file-name").html(files + " arquivos selecionados");
			});

			// CONTADOR DE CARACTERES
			$("input[type=email], input[type=password], input[type=text], textarea").characterCounter();

			// EDITOR DE TEXTOS
			$("#richtexteditor").richText();
<?php
	} //! ANIMAÇÃO PARA UPLOAD DE ARQUIVOS E RICH TEXT EDITOR
?>
		});
	</script>

</body>

</html>