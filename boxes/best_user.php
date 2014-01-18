<?php
global $core, $user, $activity, $facebook;

$best_user_obj = json_decode ( $user->get_best_week_user () );
$best = $user->get_user_by_id($best_user_obj->id);
if ( $best !== false ):
    $bestCoppe = $user->get_badges ( $best_user_obj->id );
    ?>

	<div class="best-user-box">
		<div class="best-user-detail">
			<h4><?php echo $best->username; ?></h4>
            <h4><span class="label label-warning"><?php echo $best_user_obj->punteggio; ?></span> <small>(punti ultimi 7 giorni)</small></h4>
		</div>
		<div class="best-user-score">
			<span>
                <small><?php echo $best->punteggio_totale; ?> punti totali</small>
				<small> &nbsp;<?php echo count($user->get_badges ( $best->id_utente )); ?> copp<?php echo ( count($bestCoppe) != 1 ) ? "e" : "a"; ?></small>
				<small> &nbsp;<?php echo count($user->get_won_matches ( $best->id_utente )); ?> vittorie</small>
			</span>
		</div>
        <div id="mascotteScreen">
            <div id="mascottePreloader">
                <img src="/i/maschera_maglia_1_f.png" width="289" height="185" style="width:289px; height:185px" class="png" />
                <img src="/i/maschera_maglia_2_f.png" width="289" height="185" style="width:289px; height:185px" class="png" />
                <img src="/i/maschera_maglia_3_f.png" width="289" height="185" style="width:289px; height:185px" class="png" />
                <img src="/i/maschera_maglia_4_f.png" width="289" height="185" style="width:289px; height:185px" class="png" />
            </div>
            <div id="setup_maglietta" style="background-color: <?=$best->colore_maglietta?>">
                <img src="/i/maschera_maglia_<?=$best->tipo_maglietta?>_f.png" width="289" height="185" style="width:289px; height:185px" class="png" />
            </div>
            <div id="setup_pantaloncini" style="background-color: <?=$best->colore_pantaloncini?>">
                <img src="/i/maschera_pantaloni_f.png" width="289" height="39" style="width: 289px; height:39px" class="png" />
            </div>
            <div id="setup_calzini" style="background-color: <?=$best->colore_calzini?>">
                <img src="/i/maschera_gambe_f.png" width="289" height="81" style="width:289px; height:81px" class="png" />
            </div>
            <div id="setup_numero"><?=$best->numero_maglietta?></div>
            <div id="setup_numero_shadow"><?=$best->numero_maglietta?></div>
        </div>
	</div>

<?php else: ?>

    <div class="mam">
        <p>
            Non &egrave; possibile stabilire il campione dell'ultima settimana.
        </p>
    </div>

<?php endif; ?>

