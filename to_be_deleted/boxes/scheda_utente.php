<?php
global $id_avversario, $include_path_override, $skin, $user;
if (!isset ($include_path_override) && $include_path_override != true)
	chdir("../");
require_once('classes/core.php');
if ( isset ($id_avversario) && $id_avversario != '' && $id_avversario != false) {
	$utente = $user->createUserObject ($id_avversario);
}
?>

<table width="100%" <?php echo ( !isset ($skin) || $skin != "TAB" ) ? 'class="scheda-utente"' : 'class="scheda-utente rx-scheda-utente-normal" width="100%"'; ?>  >
	<tr valign="top">
		<td>
			<div align="center" class="pas">
				<h3 style="width: 97%">
                    <span text-fit-horizontal="true" text-max-size="30"><?php echo $utente->username; ?></span>
                </h3>
				<div class="user-image-bg" style="background-image: url(<?php echo $user->get_user_picture_uri ($utente); ?>)">&nbsp;</div>
			</div>

			<table class="plain-table" style="border: 2px solid #fff; border-top: 0;">
				<tr>
					<td>
						<div align="right">
							<button name="open-write-message-dialog" id_destinatario="<?php echo $utente->id_utente ; ?>" class="btn btn-small btn-info"><span class="icon-envelope"></span> Nuovo messaggio</button>
						</div>
					</td>
				</tr>
			<?php
			$alt = -1;
			if ( $utente->nome != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>NOME</strong>: '.$utente->nome.'</td></tr>';
			}
			if ( $utente->cognome != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>COGNOME</strong>: '.$utente->cognome.'</td></tr>';
			}
			$alt++;
			echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>SCORE</strong>: ' . $user->print_user_score ($utente).'</td></tr>';
			if ( $utente->sesso != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>SESSO</strong>: '.$utente->sesso.'</td></tr>';
			}
			if ( $utente->citta != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>CITT&Agrave;</strong>: '.$utente->citta.'</td></tr>';
			}
			if ( $utente->prov != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>PROVINCIA</strong>: '.$utente->prov.'</td></tr>';
			}
			if ( $utente->hobby != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>HOBBY</strong>: '.$utente->hobby.'</td></tr>';
			}
			if ( $utente->frase != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>FRASE</strong>: '.$utente->frase.'</td></tr>';
			}
			if ( $utente->giocatore != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>GIOCATORE</strong>: '.$utente->giocatore.'</td></tr>';
			}
			if ( $utente->squadra != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>SQUADRA</strong>: '.$utente->squadra.'</td></tr>';
			}
			if ( $utente->dta_reg != "00/00/0000" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>ATTIVO DAL</strong>: ' . $utility->normalize_db_datetime ($utente->dta_reg).'</td></tr>';
			}
			if ( $utente->badges != "" ) {
				$alt++;
				echo '<tr class="'.($alt%2 == 0 ? "alt" : "").'"><td><strong>COPPE</strong>:';
				if ( count ( $utente->badges ) == 0)
					echo " Nessuna";
				else {
					echo "<br />";
					foreach ($utente->badges as $b ): ?>
						<img src="<?php echo $user->get_reward_picture ( $b->key_id, "small" ); ?>" alt="<?php echo $b->nome; ?>" width="27" height="27" hspace="3" vspace="2" />
					<?php endforeach;
				}
				echo '</td></tr>';
			}
			?>
			</table>
		</td>
	</tr>
</table>

