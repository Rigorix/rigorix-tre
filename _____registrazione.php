<?
require_once('classes/core.php');
require_once ('boxes/page_start.php');

if ( !isset ($_POST) || count ($_POST) == 0) {
	$_POST = array();
	$_POST["nome"] = $_POST["cognome"] = $_POST["email"] = $_POST["conf_email"] = $_POST["mobile"] = $_POST["nickname"] = "";
}
?>

	<div class="rx-layout-col-large">

		<!-- Colonna sinistra * corpo pagina -->
		<div class="rx-layout-col-container">

			<div class="ui-box ui-box-content ui-corner-all">
				<div class="ui-box-title">
					Registrazione
				</div>
				<div class="ui-box-content-html">
					<?php if ( $activity->check_success(400) ) { ?>

						<br /><br />
						<h3 class="rx-page-success"><?php echo $activity->print_success_text (400); ?></h3>
						<br /><br />
						<p>TI ABBIAMO INVIATO UNA MAIL PER CONFERMARE L'ISCRIZIONE.<br />
Clicca nel link che contiene per accedere al gioco.<br /><br />
Se non ricevi la mail non esitare a contattarci.<br /><br /><em>Rigorix Staff</em><br /><br /></p>

					<?php } else {

						if (count ($activity->get_error_range( 410, 428 )) > 0) {
							echo '<div class="ui-state-highlight ui-state-error ui-corner-all ui-box-content-html">';
							foreach ($activity->get_error_range( 410, 428) as $error) {
								echo $activity->errors[$error].'<br />';
							}
							echo '</div><br />';
						}
						?>
						<form action="registrazione.php?activity=subscribe" method="POST">
						<input type="hidden" name="posted" value="1" />
						<table cellspacing="0" cellpadding="4" width="557">
						<tr>
							<td width="80">Nome <sup>*</sup></td>
							<td width="170"><input type="text" name="nome" value="<? echo $_POST['nome']; ?>" <? if ($activity->has_error ( 411 )) echo 'class="ui-state-error"'?> /></td>
							<td align="right">Cognome <sup>*</sup></td>
							<td><input type="text" name="cognome" value="<? echo $_POST['cognome']; ?>" <? if ($activity->has_error ( 412 )) echo 'class="ui-state-error"'?> /></td>
						</tr>
						<tr>
							<td>Indirizzo E-mail <sup>*</sup></td>
							<td><input type="text" name="email" value="<? echo $_POST['email']; ?>" <? if ($activity->has_error_range ( 413, 416 )) echo 'class="ui-state-error"'?> /></td>
							<td nowrap="nowrap" align="right">Conferma indirizzo E-mail <sup>*</sup></td>
							<td><input type="text" name="conf_email" value="<? echo $_POST['conf_email']; ?>" <? if ($activity->has_error ( 415 )) echo 'class="ui-state-error"'?> /></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
						    <td></td>
							<td colspan="3"><strong>NB</strong>: Riceverai una mail a questo indirizzo per confermare la registrazione. Assicurati che sia corretto.</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Cellulare <sup>*</sup></td>
							<td><input type="text" name="mobile" value="<? echo $_POST['mobile']; ?>" <? if ($activity->has_error_range ( 421, 425 )) echo 'class="ui-state-error"'?> /></td>
							<td nowrap="nowrap" align="right">Conferma cellulare <sup>*</sup></td>
							<td><input type="text" name="conferma_mobile" value="<? echo $_POST['mobile']; ?>" <? if ($activity->has_error ( 422 )) echo 'class="ui-state-error"'?> /></td>
						</tr>
						<tr>
						    <td></td>
						    <td colspan="3"><strong>NB</strong>: Il numero di cellulare non sar&agrave; reso pubblico, non si potr&agrave; modificare!</td>
						</tr>
						<tr>
						    <td>&nbsp;</td>
						</tr>
						<tr>
							<td><strong>Nickname</strong><sup>*</sup></td>
							<td><input type="text" name="nickname" maxlength="20" value="<? echo $_POST['nickname']; ?>" <? if ($activity->has_error_range ( 417, 420 )) echo 'class="ui-state-error"'?> /></td>
							<td colspan="2"><strong>IMPORTANTE: </strong>Il nickname non sar&agrave; pi&ugrave; modificabile.</td>
						</tr>
						<tr>
							<td><strong>Password</strong><sup>*</sup></td>
							<td><input type="password" name="password" maxlength="20" <? if ($activity->has_error_range ( 426, 427 )) echo 'class="ui-state-error"'?> /></td>
							<td align="right"><strong>Conferma Password</strong><sup>*</sup></td>
							<td><input type="password" name="confpassword" maxlength="20" <? if ($activity->has_error_range ( 426, 427 )) echo 'class="ui-state-error"'?> /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3"><textarea cols="66" <? if ($activity->has_error ( 428 )) echo 'class="ui-state-error"'?>>
	INFORMATIVA.
	1. TITOLARITA' DEL SERVIZIO.
	Titolarita' del servizio. il Sito www.rigorix.com e' di proprieta' di Internetting di Massimo Cuomo, in persona del legale rappresentante, con sede a San Dona' di Piave (Ve), Via Don Orione 16.
	2. TITOLARITA' DEL TRATTAMENTO.
	Il Titolare del sito internet www.rigorix.com, nonche' del trattamento dei dati personali dell'utente, ai sensi per gli effetti del D.Lgs. 196/2003 (informativa sulla privacy), e' il legale rappresentante pro tempore di Internetting di Massimo Cuomo, con sede a San Dona' di Piave (Ve), in Via Don Orione 16.
	3. MODALITA' DEL TRATTAMENTO
	I dati personali raccolti dal sito e quindi da Internetting di Massimo Cuomo in persona del legale rappresentante pro tempore sono trattati con strumenti automatizzati per il tempo strettamente necessario a conseguire gli scopi per i quali sono stati raccolti. Specifiche misure di sicurezza sono osservate per prevenire la perdita dei dati, usi illeciti o non corretti ed accessi non autorizzati. In ogni caso, tutti i dati sensibili raccolti saranno usati solo ed esclusivamente ai fini ludici di cui al regolamento ufficiale del sito www.rigorix.com, nonch� per inviare i premi vinti dai giocatori e per emettere le fatture di avvenuto pagamento.
	4. RICHIESTE DI RISARCIMENTO. L'Utente proscioglie il Titolare del sito www.rigorix.com da qualsiasi risarcimento e da querele o richieste, anche dai costi di tali risarcimenti etc. creati da terzi o da testi, immagini, suoni o altro nei Servizi, nell'uso dei Servizi o in collegamento con i Servizi; a causa di violazioni dei Termini o dei diritti nei confronti di terzi.
	5. FORO COMPETENTE. I Termini e il rapporto tra l'Utente e  sono regolati dalla legge italiana. Il foro competente per tutte le controversie tra l'Utente e il Titolare di www.rigorix.com e' quello di Venezia.
						</textarea>
							</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="3"><input type="radio" name="privacy" value="si" align="absmiddle"  />Accetto &nbsp; <input checked type="radio" name="privacy" value="no" align="absmiddle" /> Non accetto</td>
						</tr>
						</table>
						<br/>
						Dopo la registrazione, potrai impostare tutte le caratteristiche del tuo giocatore e inserire i tuoi dati completi.
						<br /><br />
						<div align="center">
							<input type="submit" class="rx-ui-button" value="Registrati" />
						</div>
						</form>
					<?php } ?>
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
			<?php $core->render_box ( "classifica_spalla.php", "Classifica serie A" ); ?>

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