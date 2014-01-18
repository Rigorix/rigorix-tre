<?php
$page_table = $_SESSION["rigorix"]["section"];
$start_page = $_REQUEST["start_page"] | 0;
$per_page = 10;
$items = json_decode( get_from_service( "?getTable=$page_table&limit=$start_page,$per_page" ));
$items_count = json_decode( get_from_service( "?getTable=$page_table&getFields=count(*)" ));
$items_count = $items_count[0]->{"count(*)"};
?>

<?php require_once("dbpage_header.php"); ?>

<div class="row">
    <div class="col-12 text-center">
        <?php require_once("dbpage_paginator.php"); ?> 
    </div>
</div>

<div class="row">
    <div class="col-12">
        <table class="table table-striped table-list table-bordered">
            <tr>
                <?php
                $id_field = false;
                foreach ( $items[0] as $key => $value ) {
                    if ( $id_field === false )
                        $id_field = $key;
                    echo "<th>";
                    echo ucfirst ( str_replace("_", " ", $key) );
                    echo "</th>";
                } ?>
                <th>Actions</th>
            </tr>
            <?php foreach ( $items as $item ): ?>
                <tr>
                    <? foreach ( $item as $field_name => $field_value ): ?>
                        <td><?php echo $field_value; ?></td>
                    <? endforeach; ?>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                Actions <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a action="edit_entry&table=$page_table&<?=$id_field?>=<?=$reward->{$id_field}?>" class="text-danger">Edit</a></li>
                                <li><a action="?deleteEntryFrom=$page_table&onlyIf=<?=$id_field?>=<?=$reward->{$id_field}?>" class="text-danger">Delete!</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
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

