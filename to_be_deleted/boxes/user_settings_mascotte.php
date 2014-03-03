<?php
chdir("../");
require_once('classes/core.php');
?>
<div class="ui-box-content-html main-pane">

    <div class="callout callout-warning mtn">
        <h4 class="mbm">Rigorix mascotte</h4>
        <p class="mbm">Da questa pagina puoi aggiornare l'aspetto del tuo giocatore.<br />Inoltre, puoi impostare le tue frasi di guerra o il tuo giocatore preferito!</p>
        <ol>
            <li>Seleziona maglietta, pantaloncini o calzini e clicca il colore che preferisci</li>
            <li>Seleziona il tipo di maglia desiderato</li>
            <li>Scrivi il tuo numero</li>
            <li>Descrivi il tuo personaggio</li>
            <li><strong>Premi "Aggiorna dati"</strong></li>
        </ol>
    </div>

	<form name="aggiorna_mascotte" class="skinned" onsubmit="return false;">
	<input type="hidden" name="indb_tipo_maglietta" value="<?=$user->obj->tipo_maglietta?>" />

    <div class="panel panel-info">
        <div class="panel-heading"><h5>Personalizza la tua divisa</h5></div>

        <div class="row-fluid">
            <div class="span5">
                <div id="mascotteScreen">
                    <div id="mascottePreloader">
                        <img src="i/maschera_maglia_1.png" width="227" height="136" style="width:227px; height:136px" class="png" />
                        <img src="i/maschera_maglia_2.png" width="227" height="136" style="width:227px; height:136px" class="png" />
                        <img src="i/maschera_maglia_3.png" width="227" height="136" style="width:227px; height:136px" class="png" />
                        <img src="i/maschera_maglia_4.png" width="227" height="136" style="width:227px; height:136px" class="png" />
                    </div>

                    <div id="setup_maglietta" style="background-color: <?=$user->obj->colore_maglietta?>">
                        <img src="i/maschera_maglia_<?=$user->obj->tipo_maglietta?>.png" width="227" height="136" style="width:227px; height:136px" class="png" />
                    </div>
                    <div id="setup_pantaloncini" style="background-color: <?=$user->obj->colore_pantaloncini?>">
                        <img src="i/maschera_pantaloni.png" width="227" height="39" style="width: 227px; height:39px" class="png" />
                    </div>
                    <div id="setup_calzini" style="background-color: <?=$user->obj->colore_calzini?>">
                        <img src="i/maschera_gambe.png" width="227" height="81" style="width:227px; height:81px" class="png" />
                    </div>
                    <div id="setup_numero"><?=$user->obj->numero_maglietta?></div>
                    <div id="setup_numero_shadow"><?=$user->obj->numero_maglietta?></div>
                </div>
                <div class="text-center mam">
                    <label>Numero maglia</label>
                    <input type="text" name="indb_numero_maglietta" maxlength="2" class="input-micro text-center" placeholder="00" value="<?=$user->obj->numero_maglietta?>" />
                </div>
            </div>
            <div class="span7">
                <div class="well well-small mbs">

                    <div class="btn-group mbm" id="cloth_selector">
                        <button id="maglietta" class="btn active" name="cloth_setup_radio">Maglietta</button>
                        <button id="pantaloncini" class="btn" name="cloth_setup_radio">Pantaloncini</button>
                        <button id="calzini" class="btn" name="cloth_setup_radio">Calzini</button>
                    </div>

                    <div id="picker" class="man"></div>

                    <input type="hidden" id="setup_cloth_color" name="setup_cloth_color" value="#" />
                    <input type="hidden" name="indb_colore_maglietta" value="<?=$user->obj->colore_maglietta?>" />
                    <input type="hidden" name="indb_colore_pantaloncini" value="<?=$user->obj->colore_pantaloncini?>" />
                    <input type="hidden" name="indb_colore_calzini" value="<?=$user->obj->colore_calzini?>" />
                </div>
            </div>
            <div class="btn-group pls mll" id="shirt_selector">
                <button id="type1" name="type1" class="btn <? if ($user->obj->tipo_maglietta == 1) echo "active"; ?>"><img src="i/maglia_thumb_1.gif" /></button>
                <button id="type2" name="type2" class="btn <? if ($user->obj->tipo_maglietta == 2) echo "active"; ?>"><img src="i/maglia_thumb_2.gif" /></button>
                <button id="type3" name="type3" class="btn <? if ($user->obj->tipo_maglietta == 3) echo "active"; ?>"><img src="i/maglia_thumb_3.gif" /></button>
                <button id="type4" name="type4" class="btn <? if ($user->obj->tipo_maglietta == 4) echo "active"; ?>"><img src="i/maglia_thumb_4.gif" /></button>
            </div>
        </div>

    </div>

    <div class="panel panel-info">
        <div class="panel-heading"><h5>Descrivi la tua mascotte</h5></div>
        <div class="row-fluid">
            <div class="span6">
                <input type="text" class="span12" name="indb_hobby" placeholder="I tuoi hobby" value="<?=$user->obj->hobby?>" />
                <input type="text" class="span12" name="indb_frase" placeholder="La tua frase di guerra" value="<?=$user->obj->frase?>" />
            </div>
            <div class="span6">
                <input type="text" class="span12" name="indb_giocatore" placeholder="Giocatore del cuore" value="<?=$user->obj->giocatore?>" />
                <input type="text" class="span12" name="indb_squadra" placeholder="La tua quadra del cuore" value="<?=$user->obj->squadra?>" />
            </div>
        </div>
    </div>

    <div class="text-center">
        <button name="aggiornamento-mascotte" class="btn btn-success"><i class="icon-refresh"></i> AGGIORNA DATI</button>

    </div>

	</form>
</div>
<script>
activity.settings.init_mascotte_form ();
</script>