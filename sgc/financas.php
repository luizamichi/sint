<?php
	include_once("../controladores/financa.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isFinanca() == FALSE) {
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
			$tamanho = $financaDAO->tamanhoAtivo();
			$paginas = max(ceil($tamanho / $quantidade), 1);
			$tuplas = $financaDAO->listarIntervaloAtivo(($p - 1) * $quantidade, $quantidade);
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
	<meta name="description" content="Página de gerenciamento de finanças">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "financas";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Finanças</h1>
		<div class="col-sm">
			<form action="../controladores/procurar_financa.php" method="POST" id="procurar_financa">
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
								<option value="periodo">Período</option>
								<option value="arquivo">Arquivo</option>
								<option value="data">Data</option>
							</select>
						</div>
					</div>
					<div class="col-auto my-3">
						<button type="submit" class="btn btn-success">Procurar</button>
					</div>
					<div class="col-auto my-3">
						<a class="btn btn-outline-success ml-1" href="inserir_financa.php">Adicionar</a>
					</div>
				</div>
			</form>

			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Período</th>
						<th scope="col">Arquivo</th>
						<th scope="col">Data - Hora</th>
						<th scope="col"></th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($tuplas as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getPeriodo() . "</td>";
								echo "<td><a href=\"../" . $tupla->getArquivo() . "\" title=\"" . substr($tupla->getArquivo(), 17) . "\" target=\"_blank\"><img class=\"cinza\" src=\"../imagens/pdf.svg\" alt=\"Documento PDF\" width=\"25\" height=\"25\"></a></td>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>";
								echo "<a class=\"ml-3\" href=\"alterar_financa.php?id=" . $tupla->getId() . "\" title=\"Alterar\"><img src=\"../imagens/alterar.svg\" width=\"20\" class=\"hover-azul\" alt=\"Alterar\"></a>";
								echo "<a class=\"ml-3 remover\" id=\"" . $tupla->getId() . "\" data-toggle=\"modal\" data-target=\"#modal\" onclick=\"removerFinanca(" . $tupla->getId() . ")\" title=\"Remover\"><img src=\"../imagens/remover.svg\" width=\"20\" class=\"hover-vermelho\" alt=\"Remover\"></a>";
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
					Tem certeza que deseja remover a finança selecionada?
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
					$("#input").prop("type", "number");
					$("#input").prop("min", "1");
				}
				else if(this.value == "periodo") {
					$("#input").prop("type", "text");
				}
				else if(this.value == "arquivo") {
					$("#input").prop("type", "text");
				}
				else if(this.value == "data") {
					$("#input").prop("type", "date");
				}
			});
		});

		function removerFinanca(id) {
			document.getElementById("botaoRemover").setAttribute("href", "../controladores/remover_financa.php?id=" + id);
		}
	</script>
</body>

</html>