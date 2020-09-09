<nav aria-label="Page navigation">
	<?php if($p <= $paginas && $paginas > 1) { ?>
		<ul class="pagination justify-content-center">
			<?php echo $p != 1 ? "<li class=\"page-item\">" : "<li class=\"page-item disabled\">"; ?>
				<a class="page-link" href=<?php echo "\"?p=" . ($p - 1) . "\""; ?> aria-label="Anterior">
					<span <?php echo $p != 1 ? "class=\"text-green\"" : ""; ?> aria-hidden="true">Anterior</span>
				</a>
			</li>
			<?php if($p > 1 && $p != 1) { ?>
				<li class="page-item">
					<a class="page-link text-dark" href="?p=1">1</a>
				</li>
			<?php } ?>
			<li class="page-item">
				<a class="page-link bg-green text-light" href="#"><?php echo $p; ?><span class="sr-only">(current)</span></a>
			</li>
			<?php if($paginas > 1 && $p != $paginas) { ?>
				<li class="page-item">
					<a class="page-link text-dark" href=<?php echo "\"?p=" . $paginas . "\""; ?>><?php echo $paginas; ?></a>
				</li>
			<?php } ?>
			<?php echo $p != $paginas ? "<li class=\"page-item\">" : "<li class=\"page-item disabled\">"; ?>
				<a class="page-link" href=<?php echo "\"?p=" . ($p + 1) . "\""; ?> aria-label="Próximo">
					<span <?php echo $p!= $paginas ? "class=\"text-green\"" : ""; ?> aria-hidden="true">Próximo</span>
				</a>
			</li>
		</ul>
	<?php } ?>
</nav>