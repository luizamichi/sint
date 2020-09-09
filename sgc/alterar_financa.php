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

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$financaAlterar = $financaDAO->procurarId($_GET["id"]);
			if(!$financaAlterar) {
				header("Location: financas.php");
				return FALSE;
			}
			else {
				$_SESSION["financaAlterar"] = serialize($financaAlterar);
			}
		}
		else if(isset($_SESSION["financaAlterar"])) {
			$financaAlterar = unserialize($_SESSION["financaAlterar"]);
		}
		else {
			header("Location: financas.php");
			return FALSE;
		}

		$inputPeriodo = ["01" => "", "02" => "", "03" => "", "04" => "", "05" => "", "06" => "", "07" => "", "08" => "", "09" => "", "10" => "", "11" => "", "12" => "", "03.1" => "", "06.1" => "", "09.1" => "", "12.1" => ""];
		$inputPeriodo[substr($financaAlterar->getFlag(), 4)] = "selected";
		$inputAno = [(date("Y") - 2) => "", (date("Y") - 1) => "", date("Y") => "", (date("Y") + 1) => "", (date("Y") + 2) => ""];
		$inputAno[substr($financaAlterar->getFlag(), 0, 4)] = " selected";
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
	<meta name="description" content="Página de alteração de finança">
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
		<h1 class="mt-3 col-sm">Alterar Finança</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_financa.php" method="POST" enctype="multipart/form-data" id="alterar_financa">
				<div class="row form-group">
				<div class="col-sm-6">
						<label for="inputPeriodo">Período</label>
						<select class="custom-select" id="inputPeriodo" name="inputPeriodo" required autofocus>
							<option value="" disabled>Selecione uma opção</option>
							<optgroup label="Mensal">
								<option value="Janeiro" <?php echo $inputPeriodo["01"]; ?>>Janeiro</option>
								<option value="Fevereiro" <?php echo $inputPeriodo["02"]; ?>>Fevereiro</option>
								<option value="Março" <?php echo $inputPeriodo["03"]; ?>>Março</option>
								<option value="Abril" <?php echo $inputPeriodo["04"]; ?>>Abril</option>
								<option value="Maio" <?php echo $inputPeriodo["05"]; ?>>Maio</option>
								<option value="Junho" <?php echo $inputPeriodo["06"]; ?>>Junho</option>
								<option value="Julho" <?php echo $inputPeriodo["07"]; ?>>Julho</option>
								<option value="Agosto" <?php echo $inputPeriodo["08"]; ?>>Agosto</option>
								<option value="Setembro" <?php echo $inputPeriodo["09"]; ?>>Setembro</option>
								<option value="Outubro" <?php echo $inputPeriodo["10"]; ?>>Outubro</option>
								<option value="Novembro" <?php echo $inputPeriodo["11"]; ?>>Novembro</option>
								<option value="Dezembro" <?php echo $inputPeriodo["12"]; ?>>Dezembro</option>
							</optgroup>
							<optgroup label="Trimestral">
								<option value="1º Trimestre" <?php echo $inputPeriodo["03.1"]; ?>>1º Trimestre</option>
								<option value="2º Trimestre" <?php echo $inputPeriodo["06.1"]; ?>>2º Trimestre</option>
								<option value="3º Trimestre" <?php echo $inputPeriodo["09.1"]; ?>>3º Trimestre</option>
								<option value="4º Trimestre" <?php echo $inputPeriodo["12.1"]; ?>>4º Trimestre</option>
							</optgroup>
						</select>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputAno">Ano</label>
						<select class="custom-select" id="inputAno" name="inputAno" required>
							<option value="" disabled>Selecione uma opção</option>
							<?php foreach($inputAno as $ano => $selecionado) {
								echo "<option value=\"" . $ano . "\"" . $selecionado . ">" . $ano . "</option>";
							} ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="inputArquivo">Arquivo</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputArquivo" name="inputArquivo" accept="application/pdf" data-max-size="3072" onchange="readURL(this);">
						<label class="custom-file-label" for="inputArquivo"><?php echo substr($financaAlterar->getArquivo(), 17); ?></label>
					</div>
				</div>

				<div class="embed-responsive embed-responsive-21by9">
					<iframe class="embed-responsive-item" title="PDF" src="<?php echo "../" . $financaAlterar->getArquivo(); ?>" id="pdf-preview"></iframe>
				</div>

				<button class="btn btn-success btn-block my-4" type="submit">Alterar</button>
				<?php
					if(isset($_SESSION["resposta"])) {
						echo "<p>" . $_SESSION["resposta"] . "</p>";
						unset($_SESSION["resposta"]);
					}
				?>
			</form>
		</div>
	</div>
	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>

	<!-- Script -->
	<script>
		$(".custom-file-input").on("change", function() {
			var nomeArquivo = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(nomeArquivo);
		});

		function readURL(input) {
			if(input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#pdf-preview").attr("src", e.target.result);
					$("#pdf-preview").attr("style", "display: initial;");
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
</body>

</html>