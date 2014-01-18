<?
require_once('classes/core.php');
require_once ('boxes/page_start.php');

/*
 * 	Imposto il TAB di default dell'area personale
 */
$default_tab = 0;
if ( isset ($_REQUEST['default-tab']) && $_REQUEST['default-tab'] == "messaggi" )
	$default_tab = 2;
?>

	<div class="rx-layout-col-large">

		<!-- Colonna sinistra * corpo pagina -->
		<div class="rx-layout-col-container">

			<div class="ui-box ui-box-content ui-corner-all">
				<div class="ui-box-title important" id="test" data-toggle="popover" data-content="Contenuto della minchia">
					Area personale
				</div>

				<div class="ui-box-content-tab">
					<div class="borderless area_personale_tab rx-tab" default="<?=$default_tab?>">
					     <ul>
					         <li name="rx-tab-info"><a href="boxes/user_info.php"><div class="icon icon-utente"></div><span>Utente</span></a></li>
					         <li name="rx-tab-sfide"><a href="boxes/user_sfide.php"><div class="icon icon-sfide"></div><span>Sfide</span></a></li>
					         <?php if ($user->obj->dead != true) { ?>
								<li name="rx-tab-impostazioni"><a href="boxes/user_settings.php"><div class="icon icon-impostazioni"></div><span>Impostazioni</span></a></li>
							 <? } ?>
					         <li name="rx-tab-messaggi"><a href="boxes/user_messages.php"><div class="icon icon-messaggi"></div><span>Messaggi <? echo '&nbsp;<span '.(( count ($user->obj->messaggi) == 0 ) ? 'style="display: none"' : '').' class="ui-corner-all tornei_num_inviti tornei_num_messaggi">'.count ($user->obj->messaggi).'</span>'; ?></span></a></li>
					     </ul>
					</div>
				</div>

			</div>

		</div>
		<!-- Fine colonna sinistra * corpo pagina -->
		<div class="clr"></div>

	</div>

	<div class="rx-layout-col-right">
		<!-- Colonna destra * corpo pagina -->
		<div class="rx-layout-col-container">

			<?php $core->render_box_highlight ( "user_box.php", "Box personale" ); ?>
			<?php $core->render_box_unpadded ( "classifica_spalla.php", "Ranking utenti" ); ?>

		</div>
		<!-- Fine colonna destra * corpo pagina -->

	</div>
	<div class="rx-layout-col-extreme-right">

			<!-- Colonna destra banner * corpo pagina -->
			<div class="rx-layout-col-container">

				<?php $core->render_banner ("Middle"); ?>

			</div>
			<!-- Fine colonna destra banner * corpo pagina -->

	</div>
	<div class="clr"></div>

<?php
require_once ('boxes/page_end.php');
?>
