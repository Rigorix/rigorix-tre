<thead>
<tr>
    <?php
    $id_field = false;
    foreach ( $items[0] as $key => $value ) {
        if ( $id_field === false )
            $id_field = $key;
        $fieldController = get_field_controller ($key, $controller);
        $fieldTh = "";


        if ( $fieldController->visibility == "visible" ) {

            $fieldTh = ucfirst($fieldController->display_name );

            if ( $fieldController->type == "crossfield")
                $fieldTh = "<span class='glyphicon glyphicon-random'></span> $fieldTh";

            echo "<th><a href='".get_order_field_url ($key)."'>$fieldTh".get_order_field_arrow ($key)."</a></th>";
        }
    } ?>
</tr>
</thead>
