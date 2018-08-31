<?php
	if(isset($pages) && $pages > 1) {
?>
	<div class="container mb-7">
		<nav class="is-rounded pagination" aria-label="pagination">
			<a class="pagination-previous" <?= $page == 1 ? 'disabled' : 'href="' . $name . '?p=' . ($page - 1) .'"' ?>>Anterior</a>
			<a class="pagination-next" <?= $page == $pages ? 'disabled' : 'href="' . $name . '?p=' . ($page + 1) .'"' ?>>Próximo</a>
			<ul class="pagination-list">
<?php
		if($pages <= 8) {
			for($p = 1; $p <= $pages; $p++) {
?>
				<li><a aria-label="Ir para a página <?= $p ?>" class="<?= $p == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name . '?p=' . $p ?>"><?= $p ?></a></li>
<?php
			}
		}
		else {
?>
				<li><a aria-label="Ir para a página 1" class="<?= 1 == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name ?>?p=1">1</a></li>
				<li><a aria-label="Ir para a página 2" class="<?= 2 == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name ?>?p=2">2</a></li>
<?php
			if($page <= 3) {
?>
				<li><a aria-label="Ir para a página 3" class="<?= 3 == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name ?>?p=3">3</a></li>
				<li><a aria-label="Ir para a página 4" class="<?= 4 == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name ?>?p=4">4</a></li>
<?php
			}
			if($page > 4) {
?>
				<li><span class="pagination-ellipsis">&hellip;</span></li>
<?php
			}
			if($page > 3 && $page <= ($pages - 3)) {
?>
				<li><a aria-label="Ir para a página <?= ($page - 1) ?>" class="pagination-link" href="<?= $name . '?p=' . ($page - 1) ?>"><?= ($page - 1) ?></a></li>
				<li><a aria-label="Ir para a página <?= $page ?>" class="is-current pagination-link" href="<?= $name . '?p=' . $page ?>"><?= $page ?></a></li>
				<li><a aria-label="Ir para a página <?= ($page + 1) ?>" class="pagination-link" href="<?= $name . '?p=' . ($page + 1) ?>"><?= ($page + 1) ?></a></li>
<?php
			}
			else {
?>
				<li><a aria-label="Ir para a página <?= ceil($pages / 2) ?>" class="<?= ceil($pages / 2) == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name . '?p=' . ceil($pages / 2) ?>"><?= ceil($pages / 2) ?></a></li>
<?php
			}
			if($page < ($pages - 3)) {
?>
				<li><span class="pagination-ellipsis">&hellip;</span></li>
<?php
			}
			if($page > ($pages - 3)) {
?>
				<li><a aria-label="Ir para a página <?= ($pages - 3) ?>" class="<?= ($pages - 3) == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name . '?p=' . ($pages - 3) ?>"><?= $pages - 3 ?></a></li>
				<li><a aria-label="Ir para a página <?= ($pages - 2) ?>" class="<?= ($pages - 2) == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name . '?p=' . ($pages - 2) ?>"><?= $pages - 2 ?></a></li>
<?php
			}
?>
				<li><a aria-label="Ir para a página <?= ($pages - 1) ?>" class="<?= ($pages - 1) == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name . '?p=' . ($pages - 1) ?>"><?= $pages - 1 ?></a></li>
				<li><a aria-label="Ir para a página <?= $pages ?>" class="<?= $pages == $page ? 'is-current' : '' ?> pagination-link" href="<?= $name . '?p=' . $pages ?>"><?= $pages ?></a></li>
<?php
		}
?>
			</ul>
		</nav>
	</div>
<?php
	}
?>