<?
require_once('classes/core.php');
require_once ('boxes/page_start.php');

$badgeRewards = $dm_rewards->getBadgeRewards ();
$puntiRewards = $dm_rewards->getPuntiRewards ();
?>

	<div class="rx-layout-col-large">

		<!-- Colonna sinistra * corpo pagina -->
		<div class="rx-layout-col-container">

			<div class="ui-box ui-box-content ui-corner-all">
				<div class="ui-box-title">Riconoscimenti Rigorix</div>
				<div class="ui-box-content-html">

					<br />
					<p>
						Ogni volta che giochi una partita, stai attento al risultato, potresti ricevere dei punti extra o, per premiare la tua abilit&agrave;, delle coppe.<br />
						I punti extra (vedi sotto il dettaglio) sono applicati in specifiche situazioni e sono ricorrenti per ogni partita.<br /><br />
						Le Coppe sono dei riconoscimenti da vincere una sola volta, alzano il valore del giocatore e ti aiuteranno a essere temuto dai tuoi avversari.
						</p>
					<br />
					<h3 class="title">COPPE</h3>
					<ul class="lista-riconoscimenti">
					<?php foreach ( $badgeRewards as $reward ): ?>
						<li>
							<table cellpadding="4">
								<tr valign="middle">
									<td width="80"><img src="<?php echo $user->get_reward_picture ( $reward->key_id, "small"); ?>" alt="<?php echo $reward->nome; ?>" width="75" /></td>
									<td>
										<h4 class="title"><?php echo $reward->nome; ?></h4>
										<p>
											<?php echo $reward->descrizione; ?>
										</p>
									</td>
								</tr>
							</table>
						</li>
					<?php endforeach; ?>
					</ul>

					<br />
					<br />
					<br />
					<h3 class="title">PUNTI EXTRA</h3>
					<ul class="lista-riconoscimenti">
					<?php foreach ( $puntiRewards as $reward ): ?>
						<li>
							<table cellpadding="4">
								<tr valign="top">
									<td>
										<div class="punti <?php if ( $reward->score < 0 ) echo "rosso"; ?>">
											<big><?php echo $reward->score; ?></big>
											<h5>PUNTI</h5>
										</div>
									</td>
									<td>
										<h4 class="title"><?php echo $reward->nome; ?></h4>
										<p>
											<?php echo $reward->descrizione; ?>
										</p>
									</td>
								</tr>
							</table>
						</li>
					<?php endforeach; ?>
					</ul>


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
