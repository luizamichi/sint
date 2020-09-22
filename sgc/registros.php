<?php
	include_once("../controladores/registro.php");
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
		if($usuarioModelo->getPermissao()->isRegistro() == FALSE) {
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
			$tamanho = $registroDAO->tamanho();
			$paginas = max(ceil($tamanho / $quantidade), 1);
			$tuplas = $p <= $paginas ? $registroDAO->listarIntervalo(($p - 1) * $quantidade, $quantidade) : array();
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
	<meta name="description" content="Página de gerenciamento de registros">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "registros";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Registros</h1>
		<div class="col-sm">
			<form action="../controladores/procurar_registro.php" method="POST" id="procurar_registro">
				<div class="form-row align-items-center">
					<div class="col-sm-3 my-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<img src="../imagens/procurar.svg" alt="Procurar" width="20">
								</div>
							</div>
							<input class="form-control" type="number" id="input" name="input" min="1" required>
							<select class="custom-select mr-sm-2" id="inputSelecao" name="inputSelecao" onblur="tipoPesquisa()">
								<option selected value="id">ID</option>
								<option value="descricao">Descrição</option>
								<option value="data">Data</option>
							</select>
						</div>
					</div>
					<div class="col-auto my-3">
						<button type="submit" class="btn btn-success">Procurar</button>
					</div>
				</div>
			</form>

			<table class="table table-striped mt-3">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Data - Hora</th>
						<th scope="col">Descrição</th>
						<th scope="col">Endereço</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($tuplas as $tupla) {
							if($tupla != NULL) {
								echo "<tr>";
								echo "<th scope=\"row\">" . $tupla->getId() . "</th>";
								echo "<td>" . $tupla->getData()->format("d/m/Y - H:i:s") . "</td>";
								echo "<td>" . $tupla->getDescricao() . "</td>";
								echo "<td>" . $tupla->getIp() . "</td>";
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
				else if(this.value == "descricao") {
					$("#input").prop("type", "text");
				}
				else if(this.value == "data") {
					$("#input").prop("type", "date");
				}
			});
		});
	</script>
</body>

</html>