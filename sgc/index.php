<?php
	date_default_timezone_set("America/Sao_Paulo");
	include_once("../controladores/acesso.php");
	include_once("../modelos/Usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../sgc.php");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
	}
	$acessoAnual = $acessoDAO->tamanhoData(date("Y"));
	$acessoMensal = $acessoDAO->tamanhoData(date("Y-m"));
	$acessoDiario = $acessoDAO->tamanhoData(date("Y-m-d"));

	function converter(int $memoria) {
		$unidade = array("B", "KB", "MB", "GB", "TB", "PB");
		return round($memoria / pow(1024, ($i = floor(log($memoria, 1024)))), 2) . " " . $unidade[$i];
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
	<meta name="description" content="Página de gerenciamento de conteúdo">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "index";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid mb-3">
		<h1 class="mt-3 col-sm">Sistema de Gerenciamento de Conteúdo</h1>
		<p class="mt-3 col-sm"><strong>Acessos:</strong><?php echo " Anual (" . $acessoAnual . "), mensal (" . $acessoMensal . "), diário (" . $acessoDiario . ")"; ?></p>
		<p class="mt-3 col-sm"><strong>Endereço:</strong><?php echo " " . $_SERVER["REMOTE_ADDR"]; ?></p>
		<p class="mt-3 col-sm"><strong>Hospedagem:</strong><?php echo " " . get_current_user(); ?></p>
		<p class="mt-3 col-sm"><strong>Memória Utilizada:</strong><?php echo " " . converter(memory_get_usage(TRUE)); ?></p>
		<p class="mt-3 col-sm"><strong>Protocolo:</strong><?php echo " " . (isset($_SERVER["REQUEST_SCHEME"]) ? $_SERVER["REQUEST_SCHEME"] : $_SERVER["SERVER_PROTOCOL"]); ?></p>
		<p class="mt-3 col-sm"><a class="text-dark" href="../controladores/renovar_sessao.php" title="Renovar sessão"><strong>Sessão:</strong></a><?php echo " " . (isset($_SERVER["HTTPS"]) && strcmp(strtolower($_SERVER["HTTPS"]), "on") == 0 ? "Conexão segura" : "Conexão insegura"); ?></p>
		<p class="mt-3 col-sm"><a class="text-dark" href="alterar_senha.php" title="Alterar senha"><strong>Senha:</strong></a> <?php echo $_SESSION["senha"]; ?></p>
		<p class="mt-3 col-sm"><strong>Servidor:</strong><?php echo " " . $_SERVER["SERVER_NAME"]; ?></p>
		<p class="mt-3 col-sm"><strong>Sistema Operacional:</strong><?php echo " " . php_uname(); ?></p>
		<p class="mt-3 col-sm"><strong>Software:</strong><?php echo " " . $_SERVER["SERVER_SOFTWARE"]; ?></p>
		<p class="mt-3 col-sm"><a class="text-dark" href="../controladores/alterar_tema.php" title="Alterar tema"><strong>Tema</strong></a>: <?php echo $_SESSION["tema"]; ?></p>
		<p class="mt-3 col-sm"><strong>Tempo de Resposta:</strong><?php echo " " . (microtime(TRUE) - $_SERVER["REQUEST_TIME_FLOAT"]) . " segundos"; ?></p>
		<p class="mt-3 col-sm"><strong>Última Modificação:</strong><?php echo " " . date("d/m/Y - H:i:s", getlastmod()); ?></p>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>