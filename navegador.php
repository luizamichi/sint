<?php
	if(count(get_included_files()) <= 1) { // DESABILITA O ACESSO A PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
		header('Location: index.php');
		return false;
	}

	if(isset($pages) && $pages > 1) {
?>
	<div class="center">
		<ul class="pagination">
			<li class="<?= $page == 1 ? 'disabled' : 'waves-effect' ?>"><a href="<?= $page != 1 ? ($website . $name . '?p=' . ($page - 1)) : '#' ?>">&lsaquo;</a></li>
<?php
		if($pages <= 8) {
			for($p = 1; $p <= $pages; $p++) {
?>
			<li class="<?= $p == $page ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name . '?p=' . $p ?>"><?= $p ?></a></li>
<?php
			}
		}
		else {
?>
			<li class="<?= $page == 1 ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name ?>?p=1">1</a></li>
			<li class="<?= $page == 2 ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name ?>?p=2">2</a></li>
<?php
			if($page <= 3) {
?>
			<li class="<?= $page == 3 ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name ?>?p=3">3</a></li>
			<li class="<?= $page == 4 ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name ?>?p=4">4</a></li>
<?php
			}
			if($page > 4) {
?>
			<li class="waves-effect">&hellip;</li>
<?php
			}
			if($page > 3 && $page <= ($pages - 3)) {
?>
			<li class="waves-effect"><a href="<?= $website . $name . '?p=' . ($page - 1) ?>"><?= ($page - 1) ?></a></li>
			<li class="active"><a href="<?= $website . $name . '?p=' . $page ?>"><?= $page ?></a></li>
			<li class="waves-effect"><a href="<?= $website . $name . '?p=' . ($page + 1) ?>"><?= ($page + 1) ?></a></li>
<?php
			}
			else {
?>
			<li class="<?= ceil($pages / 2) == $page ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name . '?p=' . ceil($pages / 2) ?>"><?= ceil($pages / 2) ?></a></li>
<?php
			}
			if($page < ($pages - 3)) {
?>
			<li class="waves-effect">&hellip;</li>
<?php
			}
			if($page > ($pages - 3)) {
?>
			<li class="<?= ($pages - 3) == $page ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name . '?p=' . ($pages - 3) ?>"><?= $pages - 3 ?></a></li>
			<li class="<?= ($pages - 2) == $page ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name . '?p=' . ($pages - 2) ?>"><?= $pages - 2 ?></a></li>

<?php
			}
?>
			<li class="<?= ($pages - 1) == $page ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name . '?p=' . ($pages - 1) ?>"><?= $pages - 1 ?></a></li>
			<li class="<?= $pages == $page ? 'active' : 'waves-effect' ?>"><a href="<?= $website . $name . '?p=' . $pages ?>"><?= $pages ?></a></li>
<?php
		}
?>
			<li class="<?= $page == $pages ? 'disabled' : 'waves-effect' ?>"><a href="<?= $page != $pages ? ($website . $name . '?p=' . ($page + 1)) : '#' ?>">&rsaquo;</a></li>
		</ul>
	</div>
<?php
	}
?>