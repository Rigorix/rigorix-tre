<?php
chdir("../");
require_once('classes/core.php');
require_once('boxes/dialog_start.php');

$include_path_override = true;
if ( !$user->is_logged ):
	echo "<big class='error message'>Devi essere loggato per poter lanciare o ricevere una sfida...<br />Tra 3 secondi sarai rediretto all'homepage</big>";
	echo '<script>setTimeout ( function () {top.window.location.href = "/index.php"; }, 3000);</script>';
	exit;
endif;

if ( isset ( $_REQUEST['id_sfida'] )) {
	// E' una risposta alla sfida
	$sfida = $activity->create_sfida_obj ( $_REQUEST['id_sfida'] );
	$id_avversario = $sfida->id_sfidante;
} else {
	// Sto lanciando una sfida
	$_REQUEST['id_sfida'] = 0;
	$id_avversario = $_REQUEST['id_avversario'];
	$sfida = new stdClass ();
	$sfida->stato = 0;
}
?>

<div class="gioca-sfida-box">
    <div class="row-fluid">

        <div class="span3">
            <div id="scheda_utente">
                <?php require_once ('boxes/scheda_utente.php'); ?>
            </div>
        </div>

        <div class="span9" style="height: 487px; overflow: auto;">
            <div class="prl">
                <h5 class="text-info mvm">
<!--                    Imposta tiri e parate-->
                    <button class="btn" name="set-shuffle-sequence" title="Imposta casualmente la sequenza">Mi sento fortunato!</button>
                    <button class="btn btn-danger" name="reset-sequence" title="Svuota la sequenza">Reset</button>

                    <button class="btn btn-warning pull-right" name="submit-set-colpi-form" sfida_action="<?php echo ($sfida->stato == 1 ? "r" : "l"); ?>"><?php echo ($sfida->stato == 1 ? "Rispondi alla sfida" : "Lancia sfida")?></button>
                </h5>

                <form name="set-game-form" onsubmit="return false;">
                    <input type="hidden" name="id_sfida" value="<?php echo $_REQUEST['id_sfida']; ?>" />
                    <input type="hidden" name="id_avversario" value="<?php echo $id_avversario; ?>" />

                    <div class="well mbn">

                        <div id="gameSetPanel" class="mtm" style="height: 370px; overflow: auto"></div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
<script>
	activity.ui.init_gameset_panel ();
</script>

<?php
require_once('boxes/dialog_end.php');
?>
