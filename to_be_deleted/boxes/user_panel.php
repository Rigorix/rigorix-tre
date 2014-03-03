<?
if (!isset($position))
	$position = "Inpage";
if (!isset($target))
	$target = "";
if ( isset( $_SESSION['utente_loggato'] ) && $_SESSION['utente_loggato']->id_utente) { 
?>

<script>
var id_utente = "<?php echo $_SESSION['utente_loggato']->id_utente; ?>";
</script>

<style>
	<?php if ($position == 'popup') { ?>
	body { padding: 0 !important; margin: 0 !important; }
	#userbox { position: relative; padding: 0 !important; margin: 0 !important; top: 0; left: 0; }
	<?php } ?>
</style>

<div id="userbox">
	<div id="userbox-container">
		<div id="userbox-window">
			
			<div id="userbox-content">
				<table class="userbox-content-table" width="100%">
				<tr valign="top">
					<td>
						<!-- utenti online -->
						<div id="utenti-online-win" class="userbox-pane">
							<h2>Utenti online</h2>
							<ul>
							<?php 
							foreach($utenti_online as $utente) {
								$user = $dm_utente->getUsername ($utente->username);
								$username = "<li><a $target href='areaPersonale.php?activeTab=tab_Gioca&sfidato=".$utente->username."'>".$user."</a></li>";
								print_r($username);
							}
							?>
							</ul>
						</div>
					</td>
					<td>&nbsp;&nbsp;</td>
					<td>
						<!-- Utenti attivi -->
						<div id="utenti-attivi-win" class="userbox-pane">
							<h2>Utenti attivi</h2>
							<ul>
							<?php
							$last_date = $dm_campionato->getLastCampionatoDate();
							$count = 0;
							$utenti_attivi = $dm_utente->getArrayObjectQueryCustom("select id_utente from utente where last_login >= '".$last_date[0]->data_chiusura."' order by last_login desc");
							foreach ($utenti_attivi as $utente) {
								if ( $utente->id_utente != $_SESSION['utente_loggato']->id_utente) {
									$username = $dm_utente->getUsernameById($utente->id_utente);
									$user = $dm_utente->getUsername ($username);
									$username = "<li><a $target href='areaPersonale.php?activeTab=tab_Gioca&sfidato=$username'>$user</a></li>";
									print_r($username);
									$count++;
								}
							}
							if ( $count == 0)
								echo "<li>Nessun utente attivo!</li>";
							?>
							</ul>
						</div>
					</td>
					
				</tr>
				<? if ($position != "popup") { ?>
				<tr>
					<td colspan="3" align="right">
						<a href="#" class="userbox-popup"><img src="i/btn_apri_popup.gif" /></a> 
						<a href="#" class="userbox-closer"><img src="i/btn_chiudi.gif" /></a>
					</td>
				</tr>
				<? } ?>
				</table>
			</div>
			<? if ($position != "popup") { ?>
				<a href="#" class="userbox-opener"><img src="i/btn_stato_utenti.png" /></a>
				
				<?php if ($dm_stats->hasUserSawUserPanel($_SESSION['utente_loggato']->id_utente) === false) { ?>
					<div id="user-panel-novita">
						<img src="i/f_novita.png" />
					</div>
				<? } ?>
			<? } ?>
			
		</div>
	</div>
</div>

<script>
	UserPanel.init ();
</script>

<? } ?>