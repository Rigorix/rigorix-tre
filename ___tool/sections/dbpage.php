<?php
$page_table = $_SESSION["rigorix"]["section"];
$start_page = $_REQUEST["start_page"] | 0;
$controller = get_table_controller ($page_table);
$per_page = 10;
$items = json_decode( get_from_service( "?getTable=$page_table&limit=$start_page,$per_page&" . http_build_query($HTTP_GET_VARS) ));
$items_count = json_decode( get_from_service( "?getTable=$page_table&getFields=count(*)" ));
$items_count = $items_count[0]->{"count(*)"};
?>

<?php require_once("dbpage_header.php"); ?>

<!--<div class="row">-->
<!--    <div class="col-12 text-center">-->
<!--        --><?php //require_once("dbpage_paginator.php"); ?><!-- -->
<!--    </div>-->
<!--</div>-->

<div class="row">
    <div class="col-12">
        <table class="table table-striped table-list table-bordered table-condensed">
            <?php require_once("dbpage_table_header.php"); ?>
            <?php foreach ( $items as $item ): ?>
                <?php include ("dbpage_table_row.php"); ?>
            <? endforeach; ?>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12 text-center">
        <ul class="pagination">
            <li class="<?=$start_page == 0 ? "disabled" : ""?>"><a href="?start_page=<?=$start_page-$per_page?>">&laquo;</a></li>
            <? for ( $i=1; $i<= ceil($items_count / $per_page); $i++ ): ?>
                <li class="<?=$start_page == $per_page * ($i-1) ? "active" : "" ?>"><a href="?start_page=<?=$per_page * ($i-1)?>"><?=$i?></a></li>
            <? endfor; ?>
            <li class="<?=$start_page+$per_page > $items_count ? "disabled" : ""?>"><a href="?start_page=<?=$start_page+$per_page?>">&raquo;</a></li>
        </ul>
    </div>
</div>

