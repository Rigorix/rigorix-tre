<tr>
    <?php
    $firstField = true;
    foreach ( $item as $field_name => $field_value ):
        $fieldController = get_field_controller ($field_name, $controller);
        $fieldTdAttrs = "";
        $fieldTdContent = $field_value;
        if ( $fieldController->visibility == "visible") {

            if ( $fieldController->customcontent == true )
                $fieldTdContent = $fieldController->content;

            if ( $fieldController->contentReplaceFind == true )
                $fieldTdContent = str_replace($fieldController->contentReplaceFind, $fieldController->contentReplaceWith, $fieldTdContent);

            if ( $fieldController->type == "url" )
                $fieldTdContent = "<a href='$field_value' target='_blank'>$field_value</a>";

            if ( $fieldController->type == "multivalue" )
                $fieldTdContent = $fieldController->multivalues->{$field_value}->label;

            if ( $fieldController->type == "crossfield" )
                $fieldTdContent = "<a title='$field_value'>" . get_crossfield_value ( $field_value, $fieldController->config ) . "</a>";

            if ( $fieldController->type == "color" )
                $fieldTdContent = "<div style='background-color: $fieldTdContent' class='pas'>$fieldTdContent</div>";

            if ( $fieldController->cssClass != "" )
                $fieldTdContent = "<span class='{$fieldController->cssClass}'>$fieldTdContent</span>";

            if ( $fieldController->align != "" )
                $fieldTdAttrs .= " align='$fieldController->align'";

            if ( $firstField === true ): $firstField = false; ?>

                <td <?=$fieldTdAttrs?>>
                    <div class="btn-group text-left">
                        <button type="button" class="btn btn-sm dropdown-toggle btn-info" data-toggle="dropdown">
                            <?=$fieldTdContent?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="?section_page=dbpage_edit.php&section=<?=$page_table?>&editEntryFrom=<?=$page_table?>&onlyIf=<?=$id_field?>=<?=$item->{$id_field}?>" class="text-danger"><span class="glyphicon glyphicon-edit"></span> Edit</a></li>
                            <li><a action="?deleteEntryFrom=<?=$page_table?>&onlyIf=<?=$id_field?>=<?=$item->{$id_field}?>" class="text-danger"><span class="glyphicon glyphicon-trash"></span> Delete!</a></li>
                        </ul>
                    </div>
                </td>
            <? else:
                echo "<td $fieldTdAttrs>$fieldTdContent</td>";
            endif;
        }
    endforeach;
    ?>
</tr>