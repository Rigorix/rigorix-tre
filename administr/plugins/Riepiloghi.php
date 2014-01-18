<?
$multipleSeparator = $App->ConfigObj['multifieldseparator'];
$_DB = new DatabaseManager($App->DB_settings);
function getPersonNameById($id) {
	global $_DB;
	if(is_numeric($id)) {
		$query = "select nome from mc_persone where id = ".$id;
		$res = $_DB->getSingleObjectQueryCustom($query);
		return $res->nome;
	} else return $id;
}

function getMultipleValue($multiple) {
	global $multipleSeparator;
	
	$res = explode($multipleSeparator, $multiple);
	return $res;
}

function getRapportoById($id) {

	$rapporti = array();
	
	$val = getMultipleValue($multiple);
	foreach ($val as $id) {
		$query = "select * from mc_rapporti where id = ".$id;
		$res = $_DB->getSingleObjectQueryCustom($query);
		array_push($rapporti, $res);
	}
	return $rapporti;
}

function existIdInMultiple($id, $multiple) {
	global $multipleSeparator;
	
	$val = explode($multipleSeparator, $multiple);
	if(in_array($id, $val)) 
		return true;
	else 
		return false;
}

function getPersonaFinaleById($id) {
	global $_DB;
	
	if(is_numeric($id)) {
		$query = "select nome from mc_personeFinali where id = ".$id;
		$res = $_DB->getSingleObjectQueryCustom($query);
		return $res;
	} else return false;
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'RIEPILOGHI_DONE' ) {
	$query = "update mc_movimenti set stato = '2' where stato = '1'";
	$res = $_DB->getArrayObjectQueryCustom($query);
	if($res !== false) {
		$msg = "Movimenti aggiornati correttamente!";
	}
}
?>
<? 
include ("../inc/config.php");
function getIdFromMultiple($multi, $sep) {
	$list = explode($sep, $multi);
	return $list;
}
?>

<script>
FF.UI.addTab({
	content	: 'Debiti / Crediti',
	active	: true,
	fn		: function() {activateDebiti();}
});
FF.UI.addTab({
	content	: 'Riepilogo',
	active	: false,
	fn		: function() {activateRiepilogo();}
});
/*
FF.UI.addTab({
	content	: 'Andamento Madcap',
	active	: false,
	fn		: function() {activateAndamento();}
});
FF.UI.addTab({
	content	: 'Aggiorna stato resoconto',
	active	: false,
	fn		: function() {activateAggiorna();}
});
*/

function createFattura(id) {
	window.location=window.location.href+'&id='+id+'&actionFattura=create';
}
function activateDebiti(elem) {
	$('pageDebitiCrediti').style.display = 'block';
	$('pageRiepilogo').style.display = 'none';
	$('pageAndamento').style.display = 'none';
	$('pageAggiorna').style.display = 'none';
}
function activateRiepilogo(elem) {
	$('pageDebitiCrediti').style.display = 'none';
	$('pageAggiorna').style.display = 'none';
	$('pageAndamento').style.display = 'none';
	$('pageRiepilogo').style.display = 'block';
}
function activateAndamento(elem) {
	$('pageDebitiCrediti').style.display = 'none';
	$('pageAggiorna').style.display = 'none';
	$('pageAndamento').style.display = 'block';
	$('pageRiepilogo').style.display = 'none';
}
function activateAggiorna(elem) {
	$('pageDebitiCrediti').style.display = 'none';
	$('pageRiepilogo').style.display = 'none';
	$('pageAndamento').style.display = 'none';
	$('pageAggiorna').style.display = 'block';
}
</script>
<style>
.smallTable { font-size:12px; background:#ffffbe}
.smallTable TR TH { background:#CCCCCC; text-align:left}
.smallTable TR TD { border:0}
.contentsTable TH { font-size:14px; font-weight:normal}
</style>

	
	<div id="pageDebitiCrediti">
		<h1 style="font-size:16px; color:#333">Debiti / Crediti</h1>
		<span style="margin-left: 10px; background:red">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Cifra uscente
		<span style="margin-left: 10px; background:green">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Cifra entrante<span style="margin-left: 10px;"><strong>(d) </strong>Distributori</span>
		<br /><br />
		
		<form>
		<table cellpadding="4" cellspacing="0" width="100%" class="contentsTable" id="contentsTable">
		<tr><th>Persona</th><th>Entrate</th><th>Spese</th><th>Movimenti</th><th>Creditori</th><th>Debitori</th><th>Riepilogo</th></tr>
		<?
		$Riepilogo = array();
		
		$res = $_DB->getArrayObjectQueryCustom("select * from mc_persone order by nome");
		$bg = '#FFFFFF';
		foreach($res as $persona) {
			$movimenti = $_DB->getSingleObjectQueryCustom("select count(*) as tot from mc_movimenti where stato != '2' and personaIniziale = ".$persona->id);
			if($movimenti->tot > 0) {
				if($bg == '#F3F3F3') $bg = '#FFFFFF';
				else $bg = '#F3F3F3';
				$query = "select sum(costo) as somma from mc_movimenti where stato != '2' and personaIniziale = ".$persona->id." and tipoMovimento = 7";
				$entrate = $_DB->getSingleObjectQueryCustom($query);
				$query = "select sum(costo) as somma from mc_movimenti where stato != '2' and personaIniziale = ".$persona->id." and tipoMovimento = 8";
				$uscite = $_DB->getSingleObjectQueryCustom($query);
				?>
				<tr valign="top" bgcolor="<?=$bg?>">
					<td nowrap="nowrap"><strong style="font-size:14.4px"><?=$persona->nome?></strong></td>
					<td align="center">&nbsp;<?=$entrate->somma?></td>
					<td align="center">&nbsp;<?=$uscite->somma?></td>
					<td>
					<input type="button" value="Vedi dettaglio" onclick="this.next(0).style.display = 'block';this.next(0).style.width='100%';Element.remove(this)" />
					<table width="100%" class="smallTable" cellpadding="4" cellspacing="0" style="display:none">
					<tr><th>Descrizione</th><th>Tipo</th><th>Costo</th></tr>
					<?
					// Seleziono i movimenti della persona
					$movimenti = $_DB->getArrayObjectQueryCustom("select * from mc_movimenti where stato != '2' and personaIniziale = ".$persona->id);
					foreach($movimenti as $movimento) {
						echo "<tr>".
							"<td>".(($movimento->stato==3)?"<strong>(D)</strong>":"")." ".$movimento->descrizione."</td>".
							"<td>".(($movimento->tipoMovimento==7)?"Entrata":"Uscita")."</td>".
							"<td align=center><strong align=center style=\"color:".(($movimento->tipoMovimento==7)?"green":"red")."\">".$movimento->costo." &euro;</strong></td>".
						"</tr>";
					}
					?>
					</table>
					
					</td>
					<td><table bgcolor="#CCCCCC" cellpadding="5" cellspacing="0" width="100%" class="smallTable">
					<?
					// Gestisco le somme entranti
					$tipoMovimento = 7;
					$movimenti = $_DB->getArrayObjectQueryCustom("select * from mc_movimenti where stato != '2' and tipoMovimento = '".$tipoMovimento."' and personaIniziale = ".$persona->id);
					$totale = array();
					$peopleFinali = array();
					$writeHeaders = true;
					foreach($movimenti as $movimento) {
						
						$entita = $_DB->getSingleObjectQueryCustom("select * from mc_personefinali where id = ".$movimento->personaFinale);
						$rapporti = explode($multipleSeparator, $entita->rapporto);
						foreach($rapporti as $rapportoId) {
							$rapporto = $_DB->getSingleObjectQueryCustom("select * from mc_rapporti where id = ".$rapportoId);						
							if($rapporto->personePercentuale != "" && $rapporto->tipoRapporto != 1) {
								// Devo dare delle percentuali a queste persone
								$personeFinaliMovimento = getIdFromMultiple($rapporto->personePercentuale, $multipleSeparator);
								
								// Ciclo i vari debitori/creditori anche detti persone finali
								foreach($personeFinaliMovimento as $personaFinaleMovimento) {
									if($personaFinaleMovimento != $persona->id) {
										// Cio� se non sono io stesso
										$personaFinale = $_DB->getSingleObjectQueryCustom("select nome from mc_persone where id = ".$personaFinaleMovimento);
										if(!array_key_exists($personaFinale->nome, $peopleFinali)) $peopleFinali[$personaFinale->nome] = 0;
										if(!array_key_exists($personaFinale->nome, $totale)) $totale[$personaFinale->nome] = 0;
										$peopleFinali[$personaFinale->nome] += number_format($rapporto->percentuale * $movimento->costo,2);
										$totale[$personaFinale->nome] += number_format($rapporto->percentuale * $movimento->costo,2);
									}
								}
							}

						
							if($rapporto->personeFisso != "" && $rapporto->tipoRapporto != 1) {
								// Devo dare delle cifre fisse a queste persone
								$personeFinaliMovimento = getIdFromMultiple($rapporto->personeFisso, $multipleSeparator);
								// Ciclo i vari debitori/creditori anche detti persone finali
								foreach($personeFinaliMovimento as $personaFinaleMovimento) {
									if($personaFinaleMovimento != $persona->id) {
										$personaFinale = $_DB->getSingleObjectQueryCustom("select nome from mc_persone where id = ".$personaFinaleMovimento);
										if(!array_key_exists($personaFinale->nome, $peopleFinali)) $peopleFinali[$personaFinale->nome] = 0;
										if(!array_key_exists($personaFinale->nome, $totale)) $totale[$personaFinale->nome] = 0;
										$peopleFinali[$personaFinale->nome] += number_format($rapporto->fisso,2);
										$totale[$personaFinale->nome] += number_format($rapporto->fisso,2);
									}
								}
							}
							
							if($rapporto->tipoRapporto == 1) {
								// Devo rigirare il rapporto direttamente al destinatario dell'entit�.
								$personeFinali = getIdFromMultiple($entita->altraPersona, $multipleSeparator);
								foreach($personeFinali as $personaFinale) {
									if($persona->id != $personaFinale) {
										// Ok, non sono io stesso il destinatario o personaFinale
										$personaMov = $_DB->getSingleObjectQueryCustom("select nome from mc_persone where id = ".$personaFinale);
										if(!array_key_exists($personaMov->nome, $peopleFinali)) $peopleFinali[$personaMov->nome] = 0;
										if(!array_key_exists($personaMov->nome, $totale)) $totale[$personaMov->nome] = 0;
										if($rapporto->fisso != "") {
											$peopleFinali[$personaMov->nome] += number_format($rapporto->fisso,2);
											$totale[$personaMov->nome] += number_format($rapporto->fisso,2);
										}
										if($rapporto->percentuale != "") {
											$peopleFinali[$personaMov->nome] += number_format($rapporto->percentuale * $movimento->costo,2);
											$totale[$personaMov->nome] += number_format($rapporto->percentuale * $movimento->costo,2);
										}
									}
								}
							}
							
						}
						
					}
					$bgSoldi = "#FFFFFF";
					foreach($peopleFinali as $key => $cifraPersonaFinale) {
						if($writeHeaders !== false) {
							echo '<tr><th>Persona</th><th>Cifra</th></tr>';
							$writeHeaders = false;
						}
						echo "<tr><td align=center>$key</td><td align=center nowrap><strong style=\"color:red\">$cifraPersonaFinale &euro;</strong></td></tr>";
					}
					?>
					</table>&nbsp;
					</td>
					<td><table cellpadding="5" cellspacing="0" width="100%" class="smallTable">
					<?
					// Gestisco le somme uscenti
					$tipoMovimento = 8;
					$movimenti = $_DB->getArrayObjectQueryCustom("select * from mc_movimenti where stato != '2' and tipoMovimento = '".$tipoMovimento."' and personaIniziale = ".$persona->id);
					$peopleFinali = array();
					$writeHeaders = true;
					foreach($movimenti as $movimento) {
						$entita = $_DB->getSingleObjectQueryCustom("select * from mc_personefinali where id = ".$movimento->personaFinale);
						$rapporti = explode($multipleSeparator, $entita->rapporto);
						foreach($rapporti as $rapportoId) {
						
							$rapporto = $_DB->getSingleObjectQueryCustom("select * from mc_rapporti where id = ".$rapportoId);
							if($rapporto->personePercentuale != "" && $rapporto->tipoRapporto != 1) {
								// Devo dare delle percentuali a queste persone
								$personeFinaliMovimento = getIdFromMultiple($rapporto->personePercentuale, $multipleSeparator);
								
								// Ciclo i vari debitori/creditori anche detti persone finali
								foreach($personeFinaliMovimento as $personaFinaleMovimento) {
									if($personaFinaleMovimento != $persona->id) {
										// Cio� se non sono io stesso
										$personaFinale = $_DB->getSingleObjectQueryCustom("select nome from mc_persone where id = ".$personaFinaleMovimento);
										if(!array_key_exists($personaFinale->nome, $peopleFinali)) $peopleFinali[$personaFinale->nome] = 0;
										if(!array_key_exists($personaFinale->nome, $totale)) $totale[$personaFinale->nome] = 0;
										$peopleFinali[$personaFinale->nome] -= number_format($rapporto->percentuale * $movimento->costo,2);
										$totale[$personaFinale->nome] -= number_format($rapporto->percentuale * $movimento->costo,2);
									}
								}
							}
							
							if($rapporto->personeFisso != "" && $rapporto->tipoRapporto != 1) {
								// Devo dare delle cifre fisse a queste persone
								$personeFinaliMovimento = getIdFromMultiple($rapporto->personeFisso, $multipleSeparator);
								
								// Ciclo i vari debitori/creditori anche detti persone finali
								foreach($personeFinaliMovimento as $personaFinaleMovimento) {
									if($personaFinaleMovimento != $persona->id) {
										$personaFinale = $_DB->getSingleObjectQueryCustom("select nome from mc_persone where id = ".$personaFinaleMovimento);
										if(!array_key_exists($personaFinale->nome, $peopleFinali)) $peopleFinali[$personaFinale->nome] = 0;
										if(!array_key_exists($personaFinale->nome, $totale)) $totale[$personaFinale->nome] = 0;
										$peopleFinali[$personaFinale->nome] -= number_format($rapporto->fisso,2);
										$totale[$personaFinale->nome] -= number_format($rapporto->fisso,2);
									}
								}
							}
							
							if($rapporto->tipoRapporto == 1) {
								// Devo rigirare il rapporto direttamente al destinatario dell'entit�.
								$personeFinali = getIdFromMultiple($entita->altraPersona, $multipleSeparator);
								foreach($personeFinali as $personaFinale) {
									if($persona->id != $personaFinale) {
										// Ok, non sono io stesso il destinatario o personaFinale
										$personaMov = $_DB->getSingleObjectQueryCustom("select nome from mc_persone where id = ".$personaFinale);
										if(!array_key_exists($personaMov->nome, $peopleFinali)) $peopleFinali[$personaMov->nome] = 0;
										if(!array_key_exists($personaMov->nome, $totale)) $totale[$personaMov->nome] = 0;
										if($rapporto->fisso != "") {
											$peopleFinali[$personaMov->nome] -= number_format($rapporto->fisso,2);
											$totale[$personaMov->nome] -= number_format($rapporto->fisso,2);
										}
										if($rapporto->percentuale != "") {
											$peopleFinali[$personaMov->nome] -= number_format($rapporto->percentuale * $movimento->costo,2);
											$totale[$personaMov->nome] -= number_format($rapporto->percentuale * $movimento->costo,2);
										}
									}
								}
							}
						}
					}
					foreach($peopleFinali as $key => $cifraPersonaFinale) {
						if($writeHeaders) {
							echo '<tr><th>Persona</th><th>Cifra</th></tr>';
							$writeHeaders = false;
						}
						echo "<tr><td align=center>$key</td><td align=center nowrap><strong style=\"color:green\">".(-1 * $cifraPersonaFinale)." &euro;</strong></td></tr>";
					}
					?>
					</table>&nbsp;</td>
					<td><table cellpadding="5" cellspacing="0" width="100%" class="smallTable">
					<tr><th>Persona</th><th>Cifra</th><th>Tipo</th></tr>
					<?
					foreach($totale as $key => $cifraPersonaFinale) {
					  echo "<tr><td align=center>$verso $key</td><td align=center nowrap><strong style=\"color:".(($cifraPersonaFinale > 0) ? 'red' : 'green')."\">".abs($cifraPersonaFinale)." &euro;</strong></td><td>".(($cifraPersonaFinale > 0)?"Uscita":"Entrata")."</td></tr>";
						$query = "select id from mc_persone where nome = '".$key."'";
						$idPersona = $_DB->getSingleObjectQueryCustom($query);
						$Riepilogo[$persona->id][$idPersona->id] = $cifraPersonaFinale;
					}
					?></table>&nbsp;
					</td>
				</tr>
			<? } ?>
		<? } ?>
		</table>
	</div>

	<div id="pageRiepilogo">
		<h1 style="font-size:16px; color:#333">Riepilogo saldo</h1>
		<div style="margin-left:10px">
		<p>In base a tutti i movimenti descritti nel Tab "Debiti/Crediti", ecco il riepilogo di chi deve quanto a chi.<br />
		E' fatto tenendo conto che se X deve 100 a Y e Y deve 30 a X, alla fine risulti che X deve 80 a Y, senza menzionare gli altri debiti.
		<br /><br />
		<span style="background:red">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Cifra uscente
		<span style="margin-left: 10px; background:green">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> Cifra entrante<span style="margin-left: 10px;"><strong>(d) </strong>Distributori</span><br /><br />
		</p>
		<?
		
		// Aggiorno tutti i calcoli togliendone di inutili
		foreach($Riepilogo as $person => $delivery) {
			foreach($delivery as $idPerson => $money) {
//			 echo "<br>Debitore: ".getPersonNameById($idPerson)." => Money: ".$money." ";
				if(array_key_exists($idPerson, $Riepilogo) && array_key_exists($person, $Riepilogo[$idPerson]) && $Riepilogo[$idPerson][$person] != 0) {
					// Sto considerando di essere creditore/debitore di un utente che mi � debitore/creditore
					// Aggiorno la cifra.
					$Riepilogo[$idPerson][$person] = $Riepilogo[$idPerson][$person] + ($money * -1);
					$Riepilogo[$person][$idPerson] = 0;
				}
			}
		}
		echo "<hr>";
		?>
		<br /><br />
		<? if(count($Riepilogo) > 0 && $Riepilogo != null) { ?>
			<table bgcolor="#CCCCCC" cellpadding="10" cellspacing="0" class="smallTable">
			<tr><th>Persona</th><th>Verso</th><th>Debitore / Creditore</th><th>Ammontare</th></tr>
			<?
			foreach($Riepilogo as $person => $delivery) {
				foreach($delivery as $idPerson => $money) {
					if($money != null) {
						echo '<tr><td>'.getPersonNameById($person).'</td><td>'.(($money > 0)?"<img src=i/ico_dare.gif>":"<img src=i/ico_avere.gif>").'</td><td>'.getPersonNameById($idPerson).'</td><td><strong style="color: '.(($money > 0) ? "red" : "green").'">'.abs($money).' &euro;</strong></td></tr>';
					}
				}
			}
			?>
		<? } ?>
		</table>
		</div>
	</div>
	
	<div id="pageAndamento">
		<iframe width="100%" height="100%" src="plugins/FinancialChart.php"></iframe>
	</div>
	
	<div id="pageAggiorna">
		<h1 style="font-size:16px; color:#333">Aggiorna stato resoconto</h1>
		<div style="margin-left:10px">
			<p>Se il resoconto è stato fatto e tutti hanno ricevuto i propri soldi, allora premi il pulsante <strong>"Aggiorna resoconto"</strong> che ti permette di mettere in automatico tutti i movimenti relativi al resoconto in stato <strong>"Chiuso"</strong> e quindi di aggiornare la situazione ad adesso.</p>
			<br /><br />
			<input type="button" value="AGGIORNA RESOCONTO" onclick="window.location = FF.Utils.getLocationAndAppend('&action=RIEPILOGHI_DONE');" />
		</div>
	</div>

	
<script>
$('pageRiepilogo').style.display = 'none';
$('pageAndamento').style.display = 'none';
$('pageAggiorna').style.display = 'none';
</script>


