<?php
chdir("../");
require_once('classes/core.php');
require_once('boxes/dialog_start.php');

$include_path_override = true;
if ( !$user->is_logged ):
	echo "<big class='error message'>Devi essere loggato per poter vedere i tuoi nuovi badges...<br />Tra 3 secondi sarai rediretto all'homepage</big>";
	echo '<script>setTimeout ( function () {top.window.location.href = "/index.php"; }, 3000);</script>';
	exit;
endif;

$NewBadges = $dm_rewards->getUnotifiedBadgeByIdUtente ( $user->obj->id_utente );
?>

<div class="new-badges-container">

	<?php if ( count ( $NewBadges ) > 0 ): ?>

		<?php foreach ( $NewBadges as $NewBadge ):
			$sfidante = $dm_sfide->getAvversarioByIdSfidaAndIdUtente ( $NewBadge->id_sfida, $NewBadge->id_utente );
			$date_timestamp = strtotime( $NewBadge->timestamp );
			$date = date ("d.m.Y", $date_timestamp);
			?>
            <div style="margin: 0 0 20px 0;">
                <h2>Hai vinto la coppa <strong><?php echo $NewBadge->nome; ?></strong></h2>
                <div align="center">
                    <img width="260" src="<?php echo $user->get_reward_picture ( $NewBadge->key_id ); ?>" />
                </div>
                <h3><?php echo $NewBadge->descrizione; ?></h3>
                <div class="details">
                    <p>
                        <strong>Data: </strong><small><em><?php echo $date; ?></em></small> &nbsp; <strong>Avversario: </strong> <?php echo $user->get_smart_username ( $sfidante ); ?><br />
                        La partita si &egrave; conclusa, e ti ha fatto vincere questa coppa!!
                    </p>
                </div>
            </div>

		<?php endforeach; ?>
		<div class="clr"></div>

	<?php endif; ?>

</div>

<?php
require_once('boxes/dialog_end.php');

$updateNotifica = array( "indb_notifica" => 1 );
$updateNotifica_indb = $dm_sfide->makeInDbObject($updateNotifica);
$ret = $dm_sfide->updateObject('sfide_rewards', $updateNotifica_indb, array ( "id_utente" => $NewBadge->id_utente ));
?>
