<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	// DESABILITA O ACESSO À PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
	if(count(get_included_files()) <= 1) {
		header('Location: index.php');
		exit;
	}

	if(isset($pages) && $pages > 1) {
		$page = (int) $page;
		$pages = (int) $pages;
?>
	<div class="center">
		<ul class="pagination">
			<li class="<?= $page === 1 ? 'disabled' : 'waves-effect' ?>"><a href="<?= $page !== 1 ? (BASE_URL . $name . '?p=' . ($page - 1)) : 'javascript:void(0)' ?>">&lsaquo;</a></li>
<?php
		if($pages <= 8) {
			for($p = 1; $p <= $pages; $p++) {
?>
			<li class="<?= $p === $page ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name . '?p=' . $p ?>"><?= $p ?></a></li>
<?php
			}
		}
		else {
?>
			<li class="<?= $page === 1 ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name ?>?p=1">1</a></li>
			<li class="<?= $page === 2 ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name ?>?p=2">2</a></li>
<?php
			if($page <= 3) {
?>
			<li class="<?= $page === 3 ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name ?>?p=3">3</a></li>
			<li class="<?= $page === 4 ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name ?>?p=4">4</a></li>
<?php
			}
			if($page > 4 && $page !== 5) {
?>
			<li class="waves-effect">&hellip;</li>
<?php
			}
			elseif($page === 5) {
?>
			<li class="waves-effect"><a href="<?= BASE_URL . $name ?>?p=3">3</a></li>
<?php
			}
			if($page > 3 && $page <= ($pages - 3)) {
?>
			<li class="waves-effect"><a href="<?= BASE_URL . $name . '?p=' . ($page - 1) ?>"><?= ($page - 1) ?></a></li>
			<li class="active"><a href="<?= BASE_URL . $name . '?p=' . $page ?>"><?= $page ?></a></li>
			<li class="waves-effect"><a href="<?= BASE_URL . $name . '?p=' . ($page + 1) ?>"><?= ($page + 1) ?></a></li>
<?php
			}
			else {
?>
			<li class="<?= ceil($pages / 2) === (float) $page ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name . '?p=' . ceil($pages / 2) ?>"><?= ceil($pages / 2) ?></a></li>
<?php
			}
			if($page < ($pages - 3)) {
				if($page === 5 && $pages === 9) {
?>
			<li class="waves-effect"><a href="<?= BASE_URL . $name ?>?p=7">7</a></li>
<?php
				}
				else {
?>
			<li class="waves-effect">&hellip;</li>
<?php
				}
?>
<?php
			}
			if($page > ($pages - 3)) {
?>
			<li class="<?= ($pages - 3) === $page ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name . '?p=' . ($pages - 3) ?>"><?= $pages - 3 ?></a></li>
			<li class="<?= ($pages - 2) === $page ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name . '?p=' . ($pages - 2) ?>"><?= $pages - 2 ?></a></li>

<?php
			}
?>
			<li class="<?= ($pages - 1) === $page ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name . '?p=' . ($pages - 1) ?>"><?= $pages - 1 ?></a></li>
			<li class="<?= $pages === $page ? 'active' : 'waves-effect' ?>"><a href="<?= BASE_URL . $name . '?p=' . $pages ?>"><?= $pages ?></a></li>
<?php
		}
?>
			<li class="<?= $page === $pages ? 'disabled' : 'waves-effect' ?>"><a href="<?= $page !== $pages ? (BASE_URL . $name . '?p=' . ($page + 1)) : 'javascript:void(0)' ?>">&rsaquo;</a></li>
		</ul>
	</div>
<?php
	}
