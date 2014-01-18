<?php
chdir("../");
require_once('classes/core.php');
require_once('boxes/dialog_start.php');
$include_path_override = true;

$search = new stdClass ();
$search->naz = isset ($_REQUEST['naz']) ? $_REQUEST['naz'] : "undefined";
$search->prov = isset ($_REQUEST['prov']) ? $_REQUEST['prov'] : "undefined";
$search->eta_minima = isset ($_REQUEST['eta_minima']) ? $_REQUEST['eta_minima'] : '';
$search->eta_massima = isset ($_REQUEST['eta_massima']) ? $_REQUEST['eta_massima'] : '';
$search->sesso = isset ($_REQUEST['sesso']) ? $_REQUEST['sesso'] : 'X';
$search->username = isset ($_REQUEST['username']) ? $_REQUEST['username'] : "";
?>

<form action="?cerca" method="post">
<fieldset>
    <legend>Filtri di ricerca</legend>
    <table width="100%">

    <tr>
        <td><label>Provincia:</label></td>
        <td><select name="prov">
        <option value="false" selected="selected">-- tutte le province --</option>
        <?php
        foreach ( $utility->get_province_list() as $prov ) {
            echo '<option value="'.$prov->sigla.'" '.($search->prov == $prov->sigla ? 'selected="selected"' : '').'>'.$prov->nome.'</option>';
        }
        ?>
        </select></td>
        <td><label>Nazione:</label></td>
        <td><select name="naz" style="width: 130px;">
        <option value="false">-- tutte le nazioni --</option>
        <?php
        foreach ( $utility->get_nazioni_list() as $naz ) {
            echo '<option value="'.$naz->sigla.'" '.($search->naz == $naz->sigla ? 'selected="selected"' : '').'>'.$naz->nome.'</option>';
        }
        ?>
        </select></td>
    </tr>
    <tr>
        <td><label>Sesso:</label></td>
        <td><input type="radio" name="sesso" value="M" <? if ($search->sesso == 'M') echo 'checked="checked"'; ?> /> M &nbsp;<input type="radio" name="sesso" value="F" <? if ($search->sesso == 'F') echo 'checked="checked"'; ?> /> F &nbsp;<input type="radio" name="sesso" value="X" checked="checked" <? if ($search->sesso == 'X') echo 'checked="checked"'; ?> /> X</td>
        <td><label>Et&agrave;:</label></td>
        <td>Da <input type="text" size="3" maxlength="2" name="eta_minima" value="<?=$search->eta_minima?>" /> a <input maxlength="2" size="3" type="text" value="<?=$search->eta_massima?>" name="eta_massima" /></td>
    </tr>
    <tr>
        <td><label>Username:</label></td>
        <td><input type="text" name="username" value="<?=$search->username?>" /></td>
        <td></td>
        <td align="center"><button class="rx-ui-button">CERCA</button></td>
    </tr>
    </table>
</fieldset>
</form>
<br />

<div style="height: 260px; overflow: auto; padding-right: 3px; ">
    <div class="ui-box ui-box-content ui-corner-all">
        <?php if ( isset ($_REQUEST['cerca']) ) {
            $user_list = $user->get_user_list_by_filter ( $_REQUEST );
            ?>

            <div class="ui-box-content-section-header">
                <strong><?php echo count ($user_list); ?></strong> Risultati
            </div>

            <?php require_once ("boxes/user_list.php"); ?>

            <?php } else { ?>

            <div class="ui-box-content-html"><p>Esegui una ricerca filtrando il tipo di utente che vuoi trovare e premi "CERCA".</p></div>

        <?php } ?>
    </div>
</div>
<?php
require_once('boxes/dialog_end.php');
?>
