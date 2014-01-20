<?php
$table = $_REQUEST["section"];
$controller = get_table_controller ($table);
$serviceQuery = "?getTable=$table&onlyIf=".$_REQUEST["onlyIf"];
$fields = json_decode( get_from_service ("?getTableFields=$table") );
$items = json_decode( get_from_service( $serviceQuery ));
$item = $items[0];
?>

<div class="row">
    <div class="col-12">
        <h3 class="man"><a href="javascript:history.go(-1);" style="font-size: 15px;" class="text-warning"><span class="glyphicon glyphicon-backward"></span></a> <?php echo ucfirst ($_SESSION["rigorix"]["section"]); ?></h3>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <form class="form-horizontal">
            <?php foreach ( $item as $field => $value ): $fieldController = get_field_controller ($field, $controller);
                if ( $fieldController->visibility == "hidden")
                    continue;
                ?>
                <div class="form-group">
                    <label for="<?=$field?>" class="col-lg-3 control-label"><?=$field?></label>
                    <?php
                    $fieldInput = '<input type="text" class="form-control" id="'.$field.'" name="'.$field.'" value="'.$value.'">';

                    if ( $fieldController->forbidden == "true") {
                        $fieldInput = '<input type="text" class="disabled form-control" disabled="disabled" id="'.$field.'" name="'.$field.'" value="'.$value.'">';
                    }

                    if ( $fieldController->type == "multivalue") {
                        $fieldInput = '<select class="form-control" name="'.$field.'">';
                        foreach ( $fieldController->multivalues as $key_value => $label ) {
                            $fieldInput .= '<option value="'.$value.'" '.($key_value == strval($value) ? "selected='selected'": "").'>'.$label->label.'</option>';
                        }
                        $fieldInput .= '</select>';
                    }

                    if ( $fieldController->type == "crossfield") {
                        $ref_values = json_decode(get_from_service ("?getTable={$fieldController->config->reference_table}&getFields={$fieldController->config->reference_field},{$fieldController->config->label_field}"));
                        $fieldInput = '<select class="form-control" name="'.$field.'"><option value="">-- Select a value --</option>';
                        foreach ( $ref_values as $ref_value ) {
                            $fieldInput .= '<option value="'.$ref_value->{$fieldController->config->reference_field}.'" '.($ref_value->{$fieldController->config->reference_field} == strval($value) ? "selected='selected'": "").'>'.$ref_value->{$fieldController->config->label_field}.'</option>';
                        }
                        $fieldInput .= '</select>';
                    }

                    switch( $fields->{$field}) {

                        case 1:     // tinyint ?>
                            <div class="col-lg-1">
                                <?=$fieldInput?>
                            </div>

                        <? break; case 3: // int ?>
                            <div class="col-lg-2">
                                <?=$fieldInput?>
                            </div>

                        <? break; case 4: // float ?>
                            <div class="col-lg-3">
                                <?=$fieldInput?>
                            </div>

                        <? break; case 8: // bigint ?>
                            <div class="col-lg-3">
                                <?=$fieldInput?>
                            </div>

                        <? break; case 10: // date ?>
                        <div class="col-lg-3">
                            <div class="input-group date-picker-group" behaviour="calendar">
                                <span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
                                <input data-format="yyyy-MM-dd"  type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" value="<?=$value?>">
                            </div>
                        </div>

                        <? break; case 12: // datetime ?>

                            <div class="col-lg-3">
                                <div class="input-group date-picker-group" behaviour="calendar">
                                    <span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar" data-time-icon="glyphicon glyphicon-time" data-date-icon="glyphicon glyphicon-calendar"></i></span>
                                    <input data-format="yyyy-MM-dd hh:mm:ss"  type="text" class="form-control" id="<?=$field?>" name="<?=$field?>" value="<?=$value?>">
                                </div>
                            </div>

                        <? break; case 252: // text ?>
                            <div class="col-lg-8">
                                <textarea class="form-control" rows="4" name="<?=$field?>"><?=$value?></textarea>
                            </div>

                        <? break; case 253: // varchar ?>
                            <div class="col-lg-8">
                                <?=$fieldInput?>
                            </div>

                        <? break; case 254: // char ?>
                            <div class="col-lg-1">
                                <?=$fieldInput?>
                            </div>

                        <? break; default: ?>
                           <?=$fields->{$field}?> IS NOT A RECOGNIZED TYPE!
                        <? break; ?>
                    <? } ?>

                </div>
            <? endforeach; ?>
            <div class="col-lg-offset-3">
                <button class="btn btn-large btn-success">Update</button>
            </div>
        </form>
    </div>
</div>
<?php


function get_text_field_size ( $value ) {
    if ( strlen($value) < 6 )
        return 1;

    if ( strlen($value) < 10 )
        return 2;

    if ( strlen($value) < 15 )
        return 3;
    if ( strlen($value) < 20 )
        return 4;
    if ( strlen($value) < 30 )
        return 5;
    if ( strlen($value) < 50 )
        return 6;
    return 9;
}

?>