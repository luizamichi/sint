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
		if($usuarioModelo->getPermissao()->isDiretorio() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}
		else {
			$tuplas = array();
			listarDiretorio("../");
			$tuplas = array_slice($tuplas, 1);
		}
	}

	function listarDiretorio(string $local) {
		$arquivo = explode("/", $local);
		global $tuplas;
		if(!$local) { // LOCAL NÃO EXISTE
			return FALSE;
		}
		else if(is_dir($local)) { // É UM DIRETÓRIO
			$caminho = str_repeat("&nbsp; ", pow(count($arquivo) - 2, 2));
			$caminho .= "<img src=\"../imagens/pasta.svg\" alt=\"Pasta SVG\" width=\"25\" height=\"25\"><strong>" . end($arquivo) . "</strong>";
			array_push($tuplas, $caminho);
			$diretorio = opendir($local);
			while($file = readdir($diretorio)) {
				if($file != "." && $file != "..") {
					listarDiretorio(($local . "/" . $file));
					unset($file);
				}
			}
			closedir($diretorio);
			unset($diretorio);
			return TRUE;
		}
		else { // É UM ARQUIVO
			$caminho = str_repeat("&nbsp; ", pow(count($arquivo) - 2, 2));
			if(strcmp(substr(end($arquivo), -3), "css") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/css.svg\" alt=\"Arquivo CSS\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -4), "html") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/html.svg\" alt=\"Arquivo HTML\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "ico") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/ico.svg\" alt=\"Arquivo ICO\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "jpg") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/jpg.svg\" alt=\"Arquivo JPG\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "pdf") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/pdf.svg\" alt=\"Arquivo PDF\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "php") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\" download><img class=\"cinza ml-3 mr-1\" src=\"../imagens/php.svg\" alt=\"Arquivo PHP\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "png") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/png.svg\" alt=\"Arquivo PNG\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -2), "js") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/js.svg\" alt=\"Arquivo JS\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "map") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/map.svg\" alt=\"Arquivo MAP\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "mp3") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/mp3.svg\" alt=\"Arquivo MP3\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -2), "py") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/py.svg\" alt=\"Arquivo PY\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "sql") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/sql.svg\" alt=\"Arquivo SQL\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else if(strcmp(substr(end($arquivo), -3), "svg") == 0) {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\"><img class=\"cinza ml-3 mr-1\" src=\"../imagens/svg.svg\" alt=\"Arquivo SVG\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			else {
				$caminho .= "<a class=\"text-dark\" href=\"" . $local . "\" title=\"" . end($arquivo) . "\" target=\"_blank\" download><img class=\"cinza ml-3 mr-1\" src=\"../imagens/documento.svg\" alt=\"Arquivo\" width=\"25\" height=\"25\">" . end($arquivo) . "</a>";
			}
			array_push($tuplas, $caminho);
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
	<meta name="description" content="Página de visualização de diretórios e arquivos">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "diretorios";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Diretórios</h1>
		<div class="col-sm">
			<ul class="list-group">
				<?php
					foreach($tuplas as $tupla) {
						echo "<li class=\"list-group-item\">" . $tupla . "</li>";
					}
				?>
			</ul>
		</div>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>