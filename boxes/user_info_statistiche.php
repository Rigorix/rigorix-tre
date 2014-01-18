<?php
chdir("../");
require_once ( "classes/core.php");
$badgeRewards = $dm_rewards->getBadgeRewards ();
?>
<div class="ui-box-content-html main-pane">

	<h3>Punti</h3>
	<label>Punteggio totale: </label> <strong class="punteggio-totale"><?php echo $user->obj->punteggio_totale; ?></strong><br />
	<label>Punti da partite: </label> <strong class="punteggio-da-partite"><?php echo $user->obj->punteggio_totale; ?></strong> <span>(X vittorie, y pareggi, z perse)</span>
	<label>Punti speciali: </label> <strong class="punteggio-speciale"><?php echo $user->obj->punteggio_totale; ?></strong>

	<hr />


</div>