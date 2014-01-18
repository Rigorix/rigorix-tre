<ul class="pagination man">
	<li class="<?=$start_page == 0 ? "disabled" : ""?>"><a href="<?= get_pagination_url ($start_page-$per_page)?>">&laquo;</a></li>
	<? for ( $i=1; $i<= ceil($items_count / $per_page); $i++ ): ?>
		<li class="<?=$start_page == $per_page * ($i-1) ? "active" : "" ?>"><a href="<?= get_pagination_url ($per_page * ($i-1))?>"><?=$i?></a></li>
	<? endfor; ?>
	<li class="<?=$start_page+$per_page > $items_count ? "disabled" : ""?>"><a href="<?= get_pagination_url ($start_page+$per_page)?>">&raquo;</a></li>
</ul>     