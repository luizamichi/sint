<nav aria-label="Page navigation">
	<?php if($p <= $paginas && $paginas > 1) { ?>
		<ul class="pagination justify-content-center">
			<?php echo $p != 1 ? "<li class=\"page-item\">" : "<li class=\"page-item disabled\">"; ?>
				<a <?php echo $p != 1 ? "class=\"page-link text-success\"" : "class=\"page-link\""; ?> <?php echo "href=\"?p=" . ($p - 1) . "\""; ?> aria-label="Anterior">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only">Anterior</span>
				</a>
			</li>
			<?php if($p > 1 && $p != 1) { ?>
				<li class="page-item">
					<a class="page-link text-dark" href="?p=1">1</a>
				</li>
			<?php } ?>
			<li class="page-item">
				<a class="page-link bg-success text-light" href="#"><?php echo $p; ?><span class="sr-only">(current)</span></a>
			</li>
			<?php if($paginas > 1 && $p != $paginas) { ?>
				<li class="page-item">
				<a class="page-link text-dark" href=<?php echo "\"?p=" . $paginas . "\""; ?>><?php echo $paginas; ?></a>
				</li>
			<?php } ?>
			<?php echo $p != $paginas ? "<li class=\"page-item\">" : "<li class=\"page-item disabled\">"; ?>
				<a <?php echo ($p != $paginas) ? "class=\"page-link text-success\"" : "class=\"page-link\""; ?> <?php echo "href=\"?p=" . ($p + 1) . "\""; ?> aria-label="Próximo">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Próximo</span>
				</a>
			</li>
		</ul>
	<?php } ?>
</nav>