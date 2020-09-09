<?php
	include_once("../controladores/usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isUsuario() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		$quantidade = 15;
		if(isset($_GET["p"]) && is_numeric($_GET["p"]) && floor($_GET["p"]) > 0) {
			$p = max($_GET["p"], 1);
		}
		else {
			$p = 1;
		}
		if(isset($_SESSION["tuplas"])) {
			$tuplas = unserialize($_SESSION["tuplas"]);
			$tamanho = count($tuplas);
			$paginas = $p;
		}
		else {
			$tamanho = $usuarioDAO->tamanhoAtivo();
			$paginas = max(ceil($tamanho / $quantidade), 1);
			$tuplas = $usuarioDAO->listarIntervaloAtivo(($p - 1) * $quantidade, $quantidade);
		}
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Sistema de Gerenciamento de Conteúdo</title>
	<link type="image/ico" rel="icon" href="../imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="../css/bootstrap.min.css" id="bootstrap-css">
	<?php if(strcmp($_SESSION["tema"], "Claro") == 0) { ?>
	<link type="text/css" rel="stylesheet" href="../css/sgc.css" id="sgc-css">
	<?php } else { ?>
	<link type="text/css" rel="stylesheet" href="../css/sgc-dark.css" id="sgc-css">
	<?php } ?>
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de gerenciamento de usuários">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "usuarios";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Usuários</h1>
		<div class="col-sm">
			<form action="../controladores/procurar_usuario.php" method="POST" id="procurar_usuario">
				<div class="form-row align-items-center">
					<div class="col-sm-3 my-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<img src="../imagens/procurar.svg" alt="Procurar" width="20">
								</div>
							</div>
							<input class="form-control" type="number" id="input" name="input" min="1" required>
							<select class="custom-select mr-sm-2" id="inputSelecao" name="inputSelecao">
								<option selected value="id">ID</option>
								<option value="nome">Nome</option>
								<option value="email">E-mail</option>
								<option value="login">Login</option>
								<option value="permissao">Permissão</option>
							</select>
						</div>
					</div>
					<div class="col-auto my-3">
						<button type="submit" class="btn btn-success">Procurar</button>
					</div>
					<div class="col-auto my-3">
						<a class="btn btn-outline-success ml-1" href="inserir_usuario.php">Adicionar</a>
					</div>
				</div>
			</form>

			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Nome</th>
						<th scope="col">Email</th>
						<th scope="col">Login</th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($tuplas as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\" onclick=\"if(document.getElementById('collapse" . $tupla->getId() . "').style.display == 'none') document.getElementById('collapse" . $tupla->getId() . "').style.display=''; else document.getElementById('collapse" . $tupla->getId() . "').style.display='none';\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getNome() . "</td>";
								echo "<td>" . $tupla->getEmail() . "</td>";
								echo "<td>" . $tupla->getLogin() . "</td>";
								echo "<td>";
								echo "<a class=\"ml-3\" href=\"alterar_usuario.php?id=" . $tupla->getId() . "\" title=\"Alterar\"><img src=\"../imagens/alterar.svg\" width=\"20\" class=\"hover-azul\" alt=\"Alterar\"></a>";
								echo "<a class=\"ml-3 remover\" id=\"" . $tupla->getId() . "\" data-toggle=\"modal\" data-target=\"#modal\" onclick=\"removerUsuario(" . $tupla->getId() . ")\" title=\"Remover\"><img src=\"../imagens/remover.svg\" width=\"20\" class=\"hover-vermelho\" alt=\"Remover\"></a>";
								echo "</td>";
								echo "</tr>";
								echo "<tr style=\"display: none;\"><td></td><td></td><td></td><td></td><td></td></tr>";
								echo "<tr>";
								echo "<td colspan=\"5\" style=\"display: none;\" id=\"collapse" . $tupla->getId() . "\">";
								echo $tupla->getPermissao()->getPermissoes();
								echo "</td>";
								echo "</tr>";
							}
						}
						if(isset($_SESSION["tuplas"])) {
							unset($_SESSION["tuplas"]);
						}
						if(isset($_SESSION["resposta"])) {
							echo "<p>" . $_SESSION["resposta"] . "</p>";
							unset($_SESSION["resposta"]);
						}
					?>
				<tbody>
			</table>

			<?php include_once("paginacao.php"); ?>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Confirmação de remoção</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					Tem certeza que deseja remover o usuário selecionado?
				</div>
				<div class="modal-footer">
					<a id="botaoRemover" class="btn btn-danger text-white">Sim</a>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>

	<!-- Script -->
	<script>
		$(document).ready(function() {
			$("#inputSelecao").on("change", function() {
				if(this.value == "id") {
					let valor = $("#input").val();
					$("#input").replaceWith("<input class=\"form-control\" type=\"number\" id=\"input\" name=\"input\" min=\"1\" required>");
					$("#input").prop("value", valor);
				}
				else if(this.value == "nome") {
					let valor = $("#input").val();
					$("#input").replaceWith("<input class=\"form-control\" type=\"text\" id=\"input\" name=\"input\" required>");
					$("#input").prop("value", valor);
				}
				else if(this.value == "email") {
					let valor = $("#input").val();
					$("#input").replaceWith("<input class=\"form-control\" type=\"text\" id=\"input\" name=\"input\" required>");
					$("#input").prop("value", valor);
				}
				else if(this.value == "login") {
					let valor = $("#input").val();
					$("#input").replaceWith("<input class=\"form-control\" type=\"text\" id=\"input\" name=\"input\" required>");
					$("#input").prop("value", valor);
				}
				else if(this.value == "permissao") {
					$("#input").replaceWith("<select class=\"custom-select mr-sm-2\" id=\"input\" name=\"input\">" +
					"<option selected value=\"1\">Banner</option>" +
					"<option value=\"2\">Boletim</option>" +
					"<option value=\"3\">Convenção</option>" +
					"<option value=\"4\">Convênio</option>" +
					"<option value=\"5\">Edital</option>" +
					"<option value=\"6\">Evento</option>" +
					"<option value=\"7\">Finança</option>" +
					"<option value=\"8\">Jornal</option>" +
					"<option value=\"9\">Jurídico</option>" +
					"<option value=\"10\">Notícia</option>" +
					"<option value=\"11\">Podcast</option>" +
					"<option value=\"12\">Registro</option>" +
					"<option value=\"13\">Usuário</option>" +
					"</select>");
				}
			});
		});

		function removerUsuario(id) {
			document.getElementById("botaoRemover").setAttribute("href", "../controladores/remover_usuario.php?id=" + id);
		}
	</script>
</body>

</html>