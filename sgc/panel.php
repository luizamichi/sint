<?php
	if(session_status() !== PHP_SESSION_ACTIVE) // ACESSO SOMENTE COM UMA SESSÃO CRIADA COM OS DADOS DO USUÁRIO
		session_start();
	if(isset($_SESSION['user'])) { // USUÁRIO AUTENTICADO
		$user = unserialize($_SESSION['user']);
		date_default_timezone_set('America/Sao_Paulo');
	}
	else { // USUÁRIO NÃO AUTENTICADO
		header('Location: index.php');
		return false;
	}

	// AÇÕES PERMITIDAS NO PAINEL DE GERENCIAMENTO
	$actions = array('list', 'insert', 'update', 'view');

	// SUBTÍTULOS DAS AÇÕES
	$subtitles = array('help'=> 'Ajuda', 'list'=> 'Listar', 'insert'=> 'Inserir', 'update'=> 'Atualizar', 'view'=> 'Visualizar');

	// NOME DOS ARQUIVOS DA PASTA DE MODELOS
	$pages = array_slice(scandir('models'), 2);

	// TÍTULOS DEFINIDOS PARA CADA MODELO
	$models = array_map(function($p) { include_once('models/' . $p); return $title; }, $pages);

	// PARÂMETROS RECEBIDOS VIA GET
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$column = filter_input(INPUT_GET, 'column', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$value = filter_input(INPUT_GET, 'value', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

	// DATA ACCESS OBJECT
	require('dao.php');

	if(in_array($action, $actions) && in_array($page . '.php', $pages)) { // AÇÃO E PÁGINA VÁLIDOS
		include('models/' . $page . '.php');
		if($column && $value && !(array_key_exists(strtoupper($column), $columns))) { // INFORMOU A COLUNA E VALOR PARA VISUALIZAÇÃO, PORÉM ERRADOS
			$action = 'logged';
			$title = 'Sistema de Gerenciamento de Conteúdo';
			$subtitles[$action] = 'O SGC é um sistema gestor de websites, portais e intranets que integra ferramentas necessárias para criar, gerir (editar e inserir) conteúdos em tempo real, sem a necessidade de programação de código, cujo objetivo é estruturar e facilitar a criação, administração, distribuição, publicação e disponibilidade da informação. A sua maior característica é a grande quantidade de funções presentes.';
		}
		if(!$user[$table]) { // PERMISSÃO NÃO CONCEDIDA AO USUÁRIO
			$action = 'blocked';
			$subtitles[$action] = 'Você não possui permissão para acessar esta página e suas funcionalidades.';
		}
	}
	else { // PÁGINA INICIAL OU PÁGINA DE AJUDA
		$action = strcmp($action, 'help') == 0 ? 'help' : 'logged';
		$title = strcmp($action, 'help') == 0 ? 'Ajuda' : 'Sistema de Gerenciamento de Conteúdo';
		$subtitles[$action] = strcmp($action, 'help') == 0 ? 'Especificações de propriedades dos registros.' : 'O SGC é um sistema gestor de websites, portais e intranets que integra ferramentas necessárias para criar, gerir (editar e inserir) conteúdos em tempo real, sem a necessidade de programação de código, cujo objetivo é estruturar e facilitar a criação, administração, distribuição, publicação e disponibilidade da informação. A sua maior característica é a grande quantidade de funções presentes.';
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<link rel="icon" href="../img/sgc.svg"/>
	<link rel="stylesheet" type="text/css" href="../css/bulma.min.css"/>
<?php
	if(isset($action) && strcmp($action, 'list') == 0) { // DATATABLES
?>
	<link rel="stylesheet" type="text/css" href="../css/datatables.min.css"/>
<?php
	}
	elseif(isset($action) && in_array($action, array('insert', 'update'))) { // RICH TEXT EDITOR
?>
	<link rel="stylesheet" type="text/css" href="../css/richtext.min.css"/>
	<link rel="stylesheet" href="../css/fontawesome.min.css"/>
<?php
	}
?>
	<meta charset="utf-8"/>
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Sistema de Gerenciamento de Conteúdo - <?= $title ?></title>
</head>

<body>
	<!-- MENU SUPERIOR -->
	<nav class="is-primary is-fixed-top navbar">
		<div class="navbar-brand">
			<a class="navbar-item" href="panel.php" title="Sistema de Gerenciamento de Conteúdo">
				<img alt="Sistema de Gerenciamento de Conteúdo" height="40" src="../img/sgc.svg" width="40"/>
			</a>
			<div class="navbar-burger burger" data-target="header">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>

		<div class="navbar-menu" id="header">
			<div class="navbar-end">
<?php
	foreach($models as $m => $model) { // CRIAÇÃO DO MENU SUPERIOR
		if($user[strtoupper(explode('.php', $pages[$m])[0])]) { // VERIFICAÇÃO DE PERMISSÕES DO USUÁRIO
?>
				<a class="<?= $page == explode('.php', $pages[$m])[0] ? 'is-active' : '' ?> navbar-item" href="?action=list&page=<?= explode('.php', $pages[$m])[0] ?>"><?= $model ?></a>
<?php
		}
	}
?>
				<div class="has-dropdown is-hoverable navbar-item">
					<a class="<?= strcmp($action, 'help') == 0 ? 'is-active' : '' ?> navbar-link" href="#">Opções</a>
					<div class="is-boxed navbar-dropdown">
						<a class="navbar-item" href="?action=help">Ajuda</a>
						<a class="navbar-item" href="../index.php">Website</a>
						<hr class="navbar-divider"/>
						<a class="navbar-item" href="index.php?logout=1">Sair</a>
					</div>
				</div>
			</div>
		</div>
	</nav>
	<!--/ MENU SUPERIOR -->

	<div class="container has-background-light is-fluid mb-3 pb-3">
		<section class="section">
			<div class="container has-text-centered mt-5">
				<h1 class="is-1 title"><?= $title ?></h1>
				<h2 class="is-4 subtitle"><?= $subtitles[$action] ?></h2>
			</div>
<?php
	if(strcmp($action, 'help') == 0) { // EXIBIR INFORMAÇÕES DE AJUDA
?>
			<div class="container content">
<?php
		foreach($pages as $p) { // PERCORRER MODELOS
			include('models/' . $p);
?>
				<h3><?= $title ?></h3>
<?php
			foreach($columns as $c => $column) { // EXIBIR ATRIBUTOS DOS MODELOS
?>
				<p><strong><?= $column['label'] ?></strong>: <?= str_replace(' ,', '', trim(implode(', ', [isset($insert[$c]['type']) ? 'tipo ' . $insert[$c]['type'] : 'tipo ' . $insert[$c]['tag'], isset($insert[$c]['attributes']['required']) ? 'obrigatório' : '', isset($insert[$c]['attributes']['maxlength']) ? 'tamanho máximo de ' . $insert[$c]['attributes']['maxlength'] . ' caracteres' : '', isset($insert[$c]['attributes']['minlength']) ? 'tamanho mínimo de ' . $insert[$c]['attributes']['minlength'] . ' caracteres' : '']), ' ,')) ?>.</p>
<?php
			}
?>
				<br/>

<?php
		}
?>
			</div>

<?php
	} //! EXIBIR INFORMAÇÕES DE AJUDA
	elseif(strcmp($action, 'insert') == 0) { // INSERIR REGISTRO
		// PROCURAR SE TEM O ATRIBUTO 'ID' E VERIFICAR O NÚMERO DISPONÍVEL NO BANCO DE DADOS
		$columns['ID']['default'] = isset($columns['ID']) ? sql_length($table) + 1 : $columns['ID'];
?>

			<form action="crud.php?action=insert&page=<?= $page ?>" enctype="multipart/form-data" method="post">

<?php
		foreach($insert as $i => $field) { // PERCORRER CAMPOS DE ATRIBUTOS
			if(strcmp($field['tag'], 'input') == 0) { // TAG DO TIPO INPUT
				if(strcmp($field['type'], 'file') == 0) { // INPUT DO TIPO FILE
?>
				<div class="field">
					<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="file has-name is-fullwidth">
						<label class="file-label">
							<input class="file-input" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="file" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>/>
							<span class="file-cta">
								<span class="file-icon">
									<img alt="Upload" height="35" src="../img/upload.svg" width="35"/>
								</span>
								<span class="file-label">
									Enviar arquivo
								</span>
							</span>
							<span class="file-name has-background-white">
								Nenhum arquivo enviado
							</span>
						</label>
					</div>
				</div>

<?php
				} //! INPUT DO TIPO FILE
				elseif(strcmp($field['type'], 'checkbox') == 0) { // INPUT DO TIPO CHECKBOX
?>

				<div class="field">
					<label class="label"><?= $columns[$i]['label'] ?></label>

<?php
					foreach($field['labels'] as $l => $label) {
?>

					<label class="checkbox mr-2">
						<input type="checkbox" name="<?= $columns[$i]['name'] ?>" value="<?= $field['values'][$l] ?>"/>
						<?= $label ?>

					</label>

<?php
					}
?>

				</div>

<?php
				} //! INPUT DO TIPO CHECKBOX
				else { // INPUT DOS TIPOS DATE, NUMBER, PASSWORD, TEXT, TIME
?>

				<div class="field">
					<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="control">
						<input class="input" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="<?= $field['type'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $columns[$i]['default']?>"/>
					</div>
				</div>

<?php
				} //! INPUT DOS TIPOS DATE, NUMBER, PASSWORD, TEXT, TIME
			} //! TAG DO TIPO INPUT
			elseif(strcmp($field['tag'], 'select') == 0) { // TAG DO TIPO SELECT
?>

				<div class="field">
					<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="control">
						<div class="select">
							<select id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>>
								<option disabled value="">Selecione uma opção</option>
<?php
				foreach($field['options'] as $option) { // PERCORRER AS OPÇÕES DO CAMPO SELECT
?>
								<option value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
<?php
				}
?>
							</select>
						</div>
					</div>
				</div>

<?php
			} //! TAG DO TIPO SELECT
			elseif(strcmp($field['tag'], 'textarea') == 0) { // TAG DO TIPO TEXTAREA
?>

				<div class="field">
					<label class="label" for="<?= $insert[$i]['id'] ?? $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="control">
						<textarea class="textarea" id="<?= $insert[$i]['id'] ?? $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>></textarea>
					</div>
				</div>

<?php
			} //! TAG DO TIPO TEXTAREA
		} //! PERCORRER CAMPOS DE ATRIBUTOS
?>

				<div class="field is-grouped">
					<div class="control">
						<button class="button is-primary" type="submit">Enviar</button>
					</div>
					<div class="control">
						<button class="button is-outlined is-primary" onclick="goBack()" type="button">Cancelar</button>
					</div>
				</div>
			</form>

<?php
	} //! INSERIR REGISTROS
	elseif(strcmp($action, 'list') == 0) { // LISTAR REGISTROS
		// LER TODOS OS REGISTROS DO BANCO DE DADOS
		$rows = sql_read($table=$table, $condition=null, $unique=false);
?>
			<div class="container has-text-centered my-3">
				<div>
				<a class="button is-outlined is-primary" href="panel.php?action=insert&page=<?= $page ?>">Cadastrar</a>
				</div>
			</div>

			<table class="display table">
				<thead>
					<tr>

<?php
		foreach($list as $l => $field) { // PERCORRER CAMPOS DE ATRIBUTOS
?>

						<th><?= $columns[$l]['label'] ?></th>

<?php
		}
?>

						<th>GERENCIAR</th>
					</tr>
				</thead>
				<tbody>

<?php
		foreach($rows as $row) { // PERCORRER OS REGISTROS RECUPERADOS DO BANCO DE DADOS
?>
					<tr>
<?php
			foreach($list as $l => $field) { // PERCORRER ATRIBUTOS
?>
						<td><?= isset($columns[$l]['mask']) ? $columns[$l]['mask'][$row[$l]] : strip_tags($row[$l]) ?></td>
<?php
			}
?>

						<td>
							<a class="mx-1" href="panel.php?action=view&page=<?= $page ?>&column=id&value=<?= $row['ID'] ?>" title="Visualizar"><img alt="Visualizar" class="green is-hovered" src="../img/eye.svg" width="20"/></a>
							<a class="mx-1" href="panel.php?action=update&page=<?= $page ?>&column=id&value=<?= $row['ID'] ?>" title="Alterar"><img alt="Alterar" class="blue is-hovered" src="../img/pencil.svg" width="20"/></a>
							<a class="mx-1" href="#" onclick="confirmRemove(<?= $row['ID'] ?>, '<?= strtolower($table) ?>', '<?= strtolower($title) ?>')" title="Remover"><img alt="Remover" class="is-hovered red" src="../img/trash.svg" width="20"/></a>
						</td>

					</tr>

<?php
		}
?>

				</tbody>
			</table>

<?php
	}
	elseif(strcmp($action, 'update') == 0) { // ALTERAR REGISTRO
		$row = sql_read($table=$table, $condition=strtoupper($column) . '="' . $value . '"', $unique=true);
		if($row) {
?>

			<form action="crud.php?action=update&page=<?= $page ?>&id=<?= $row['ID'] ?>" enctype="multipart/form-data" method="post">

<?php
			foreach($update as $i => $field) { // PERCORRER CAMPOS DE ATRIBUTOS
				if(strcmp($field['tag'], 'input') == 0) { // TAG DO TIPO INPUT
					if(strcmp($field['type'], 'file') == 0) { // INPUT DO TIPO FILE
?>

				<div class="field">
					<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="file has-name is-fullwidth">
						<label class="file-label">
							<input class="file-input" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="file" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>/>
							<span class="file-cta">
								<span class="file-icon">
									<img alt="Upload" height="35" src="../img/upload.svg" width="35"/>
								</span>
								<span class="file-label">
									Enviar arquivo
								</span>
							</span>
							<span class="file-name has-background-white">
								<?= ($row[$i] ?? 'Nenhum arquivo enviado') . PHP_EOL ?>
							</span>
						</label>
					</div>
				</div>

<?php
					} //! INPUT DO TIPO FILE
					elseif(strcmp($field['type'], 'checkbox') == 0) { // INPUT DO TIPO CHECKBOX
?>

				<div class="field">
					<label class="label"><?= $columns[$i]['label'] ?></label>

<?php
						foreach($field['labels'] as $l => $label) {
?>

					<label class="checkbox mr-2">
						<input type="checkbox" name="<?= $columns[$i]['name'] ?>" <?= $row[$field['names'][$l]] ? 'checked' : '' ?> value="<?= $field['values'][$l] ?>"/>
						<?= $label ?>

					</label>

<?php
						}
?>

				</div>

<?php
					} //! INPUT DO TIPO CHECKBOX
					elseif(strcmp($field['type'], 'password') == 0) { // INPUT DO TIPO PASSWORD
?>
				<div class="field">
					<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="control">
						<input class="input" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="password" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>/>
					</div>
				</div>

<?php
					} //! INPUT DO TIPO PASSWORD
					else { // INPUT DOS TIPOS DATE, NUMBER, TEXT, TIME
?>

				<div class="field">
					<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="control">
						<input class="input" id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" type="<?= $field['type'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?> value="<?= $row[$i] ?? ''?>"/>
					</div>
				</div>

<?php
					} //! INPUT DOS TIPOS DATE, NUMBER, TEXT, TIME
				} //! TAG DO TIPO INPUT
				elseif(strcmp($field['tag'], 'select') == 0) { // TAG DO TIPO SELECT
?>

				<div class="field">
					<label class="label" for="<?= $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="control">
						<div class="select">
							<select id="<?= $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>>
								<option disabled value="">Selecione uma opção</option>
<?php
					foreach($field['options'] as $option) {
?>
								<option <?= $row[$i] == $option['value'] ? 'selected' : '' ?> value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
<?php
					}
?>
							</select>
						</div>
					</div>
				</div>

<?php
				} //! TAG DO TIPO SELECT
				elseif(strcmp($field['tag'], 'textarea') == 0) { // TAG DO TIPO TEXTAREA
?>

				<div class="field">
					<label class="label" for="<?= $update[$i]['id'] ?? $columns[$i]['name'] ?>"><?= $columns[$i]['label'] ?></label>
					<div class="control">
						<textarea class="textarea" id="<?= $update[$i]['id'] ?? $columns[$i]['name'] ?>" name="<?= $columns[$i]['name'] ?>" <?= isset($field['attributes']) ? implode(' ', array_map(function($x, $y) { return $x . '="' . $y . '"'; }, array_keys($field['attributes']), array_values($field['attributes']))) : '' ?>><?= $row[$i] ?? ''?></textarea>
					</div>
				</div>

<?php
				} //! TAG DO TIPO TEXTAREA
			} //! PERCORRER CAMPOS DE ATRIBUTOS
?>

				<div class="field is-grouped">
					<div class="control">
						<button class="button is-primary" type="submit">Enviar</button>
					</div>
					<div class="control">
						<button class="button is-outlined is-primary" onclick="goBack()" type="button">Cancelar</button>
					</div>
				</div>
			</form>

<?php
		}
		else {
?>
		<h4 class="has-text-centered my-3">Não foi encontrado nenhum registro.</h4>
<?php
		}
	} //! ALTERAR REGISTRO
	elseif(strcmp($action, 'view') == 0) { // VISUALIZAR REGISTRO
		// LER O REGISTRO DO BANCO DE DADOS A PARTIR DOS PARÂMETROS INFORMADOS
		$row = sql_read($table=$table, $condition=strtoupper($column) . '="' . $value . '"', $unique=true);
?>

			<div class="container content">
<?php
		if($row) { // CASO ENCONTROU UM REGISTRO
			foreach($view as $v => $tag) { // PERCORRER CAMPOS DE ATRIBUTOS
?>
				<h4><?= $columns[$v]['label'] ?></h4>

<?php
				if(!isset($tag['tag'])) {
					$attr = array_map(function($a) { global $columns, $row, $v; return isset($columns[$v][$a]['mask']) ? $columns[$v][$a]['mask'][$row[$a]] : $row[$a]; }, $tag);
?>
				<p><?= str_replace(' ,', '', trim(implode(', ', $attr), ' ,')) ?></p>
<?php
				}
				elseif(strcmp($tag['tag'], 'p') == 0) {
?>
				<p><?= isset($columns[$v]['mask']) ? $columns[$v]['mask'][$row[$v]] : $row[$v] ?></p>
<?php
				}
				elseif(strcmp($tag['tag'], 'a') == 0) {
?>
				<a href="../<?= $row[$v] ?>"><?= $row[$v] ?></a>
				<br/><br/>
<?php
				}
				elseif(strcmp($tag['tag'], 'img') == 0 && $row[$v]) {
?>
				<img alt="Imagem" class="image" src="../<?= $row[$v] ?>" width="300"/>
				<br/>
<?php
				}
			}
		}
		else {
?>
				<h4 class="has-text-centered my-3">Não foi encontrado nenhum registro.</h4>
<?php
		}
?>
			</div>
<?php
	} //! VISUALIZAR REGISTRO
?>
		</section>
	</div>

	<footer class="footer has-background-primary py-3">
		<div class="has-text-centered">
			<p class="has-text-primary-light">Copyright &copy; 2018 <a href="https://luizamichi.com.br" target="_blank"><strong>Luiz Joaquim Aderaldo Amichi</strong></a>. Todos os direitos reservados.</p>
		</div>
	</footer>

	<script src="../js/jquery.min.js"></script>
<?php
	if(isset($action) && strcmp($action, 'list') == 0) { // DATATABLES
?>
	<script src="../js/datatables.min.js"></script>
<?php
	}
	elseif(isset($action) && in_array($action, array('insert', 'update'))) { // RICH TEXT EDITOR
?>
	<script src="../js/jquery.richtext.min.js"></script>
<?php
	}
?>
	<script>
<?php
	if(isset($action) && in_array($action, array('insert', 'update'))) { // RETORNAR PARA A PÁGINA ANTERIOR
?>
		function goBack() {
			window.history.back();
		}
<?php
	}
	elseif(isset($action) && strcmp($action, 'list') == 0) { // JANELA DE CONFIRMAÇÃO DE REMOÇÃO DE REGISTRO
?>
		function confirmRemove(id, page, table) {
			var response = confirm("Deseja remover o registro "+ id + " da tabela de "+ table +"?");
			if(response == true)
				window.location.href = "crud.php?action=remove&page=" + page + "&id=" + id;
		}
<?php
	}
?>

		// JQUERY
		$(document).ready(function() {
			// DROPDOWN MENU
			$(".navbar-burger").click(function() {
				$(".navbar-burger").toggleClass("is-active");
				$(".navbar-menu").toggleClass("is-active");
			});

<?php
	if(isset($action) && strcmp($action, 'list') == 0) { // DATATABLES
?>
			$("table").DataTable({"order": [[0, "desc"]]});
<?php
	}
	elseif(isset($action) && in_array($action, array('insert', 'update'))) { // ANIMAÇÃO PARA UPLOAD DE ARQUIVOS E RICH TEXT EDITOR
?>
			$("input[type=file]").change(function() {
				var files = $(this)[0].files.length;
				if(files == 1)
					$(this).siblings(".file-name").html($(this).val().split("\\").pop());
				else
					$(this).siblings(".file-name").html(files + " arquivos selecionados");
			});

			$("#richtexteditor").richText();
<?php
	}
?>
		});
	</script>

</body>

</html>