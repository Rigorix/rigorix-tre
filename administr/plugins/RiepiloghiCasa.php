<?php
include ("RiepiloghiCasa.Class.php");

$_DB = new DatabaseManager($App->DB_settings);

$R = new ReportManager('ps');
$R->usciteKey     = 12;
$R->entrateKey    = 11;
$R->nonSaldatoKey = 7;
$R->saldatoKey    = 6;
$R->rappDiretto   = 6;
$R->rappFiltrato  = 7;
$R->sep           = $App->ConfigObj['multifieldseparator'];

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'RIEPILOGHI_DONE' ) {
	$query = "update ps_movimenti set stato = ".$R->saldatoKey." where stato = ".$R->nonSaldatoKey;
	$res = $_DB->query($query);
	if($res !== false) {
		$msg = "Movimenti aggiornati correttamente!";
	}
}
?>

<script>

FF.UI.addTab({
	active		: true,
	content		: '<a href="#" onClick="FF.UI.activateTab(this);activateDebiti(this);">Debiti / Crediti</a>'
});
FF.UI.addTab({
	active		: false,
	content		: '<a href="#" onClick="FF.UI.activateTab(this);activateAggiorna(this);">Aggiorna stato resoconto</a>'
});

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

	<div id="pageDebitiCrediti" style="padding: 0 8px">
		<h1 style="font-size:16px; color:#333">Debiti / Crediti</h1>
		<br />
		<table cellpadding="5" cellspacing="1" bgcolor="#e6e6e6">
			<tr bgcolor="#999999" align="left">
				<th>Chiavi</th>
				<th>Valore</th>
				<th>Valore da DB</th>
			</tr>
			<tr>
				<td><strong>Chiave uscite: </strong></td>
				<td><?php echo $R->usciteKey; ?></td>
				<td><em><?php echo $_DB->getSingleObjectQueryCustom("select * from ".$R->pre."_tipomovimenti where id = '".$R->usciteKey."'")->tipo; ?></em></td>
			</tr>
			<tr>
				<td><strong>Chiave entrate: </strong></td>
				<td><?php echo $R->entrateKey; ?></td>
				<td><em><?php echo $_DB->getSingleObjectQueryCustom("select * from ".$R->pre."_tipomovimenti where id = '".$R->entrateKey."'")->tipo; ?></em></td>
			</tr>
			<tr>
				<td><strong>Chiave non saldato: </strong></td>
				<td><?php echo $R->nonSaldatoKey; ?></td>
				<td><em><?php echo $_DB->getSingleObjectQueryCustom("select * from ".$R->pre."_stato where id = '".$R->nonSaldatoKey."'")->stato; ?></em></td>
			</tr>
			<tr>
				<td><strong>Chiave saldato: </strong></td>
				<td><?php echo $R->saldatoKey; ?></td>
				<td><em><?php echo $_DB->getSingleObjectQueryCustom("select * from ".$R->pre."_stato where id = '".$R->saldatoKey."'")->stato; ?></em></td>
			</tr>
			<tr>
				<td><strong>Chiave rapporto diretto: </strong></td>
				<td><?php echo $R->rappDiretto; ?></td>
				<td><em><?php echo $_DB->getSingleObjectQueryCustom("select * from ".$R->pre."_rapporti where id = '".$R->rappDiretto."'")->nome; ?></em></td>
			</tr>
			<tr>
				<td><strong>Chiave rapporto filtrato: </strong></td>
				<td><?php echo $R->rappFiltrato; ?></td>
				<td><em><?php echo $_DB->getSingleObjectQueryCustom("select * from ".$R->pre."_rapporti where id = '".$R->rappFiltrato."'")->nome; ?></em></td>
			</tr>
		</table>
		<br />
		<strong>ATTENZIONE!!!</strong> Se ci sono spazi vuoti nella tabella sopra ci son problemi di configurazione.
		<br /><br />
		 <!-- form>
		 <table cellpadding="4" cellspacing="0" width="100%" class="contentsTable" id="contentsTable">
		 <tr><th>Persona</th><th>Entrate</th><th>Spese</th><th>Movimenti</th><th>Creditori</th><th>Debitori</th><th>Riepilogo</th></tr -->
		 
		 <?php
		 
		 $StatoPersone = $R->createPersoneObj();
		 $Movimenti = $R->getMovimenti();
		 $Uscite = array();
		 $Entrate = array();
		 
		 foreach($Movimenti as $Movimento) {
			
			/* Ciclo movimenti */
			$PersonaFinale = $R->getPersonaFinaleById($Movimento['personaFinale']);
			$Rapporto = $R->getRapportoById($PersonaFinale['rapporto']);
			if($Movimento['tipoMovimento'] == $R->usciteKey) {
			   
			   /* USCITE */
			   
			   // Tolgo i soldi a chi li ha spesi
			   $StatoPersone[$Movimento['personaIniziale']]['Saldo'] -= $Movimento['costo'];
			   if($Rapporto['tipoRapporto'] == 1) {
					// Soldi diretti alla persona
					
					if(!array_key_exists($PersonaFinale['altraPersona'], $StatoPersone[$Movimento['personaIniziale']]['Debiti'])) {
						$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$PersonaFinale['altraPersona']] = 0;
					}
					$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$PersonaFinale['altraPersona']] += $Movimento['costo'];
			   }
			   else {
					// Devo applicare il filtro del rapporto
				  
					if($Rapporto['percentuale'] != '' && $Rapporto['personePercentuale'] != '') {
						// Applico il filtro percentuale
						$PersonePercentuale = $R->getMultipleValue($Rapporto['personePercentuale']);
						foreach($PersonePercentuale as $Single) {
							if($Single != $Movimento['personaIniziale']) {
								if(!array_key_exists($Single, $StatoPersone[$Movimento['personaIniziale']]['Debiti']))
									$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$Single] = 0;
								$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$Single] += ($Movimento['costo'] * $Rapporto['percentuale']);
						}
					 }
				  }
			   }
			   
			} else if ($Movimento['tipoMovimento'] == $R->entrateKey) {
			   
			   /* ENTRATE */
			  
			   // Aggiungo Crediti a chi di dovere
			   $StatoPersone[$Movimento['personaIniziale']]['Saldo'] += $Movimento['costo'];
			   if($Rapporto['tipoRapporto'] == $R->rappDiretto) {
					// Soldi diretti alla persona
					if(!array_key_exists($PersonaFinale['altraPersona'], $StatoPersone[$Movimento['personaIniziale']]['Debiti'])) {
						$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$PersonaFinale['altraPersona']] = 0;
					}
					$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$PersonaFinale['altraPersona']] -= $Movimento['costo'];
			   }
			   else {
					// Devo applicare il filtro del rapporto
				  
					if($Rapporto['percentuale'] != '' && $Rapporto['personePercentuale'] != '') {
						// Applico il filtro percentuale
						$PersonePercentuale = $R->getMultipleValue($Rapporto['personePercentuale']);
						foreach($PersonePercentuale as $Single) {
							if($Single != $Movimento['personaIniziale']) {
								if(!array_key_exists($Single, $StatoPersone[$Movimento['personaIniziale']]['Debiti']))
									$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$Single] = 0;
								$StatoPersone[$Movimento['personaIniziale']]['Debiti'][$Single] -= ($Movimento['costo'] * $Rapporto['percentuale']);
						}
					 }
				  }
			   }
			   
			}
			
		 }
		 
		?>
		<style>
		.Riepilogo { background: #999; margin: 10px}
		.Riepilogo th {text-align: left; color: #fff; padding: 3px 10px}
		.Riepilogo tr td { background: #f3f3f3; padding: 4px 10px} 
		</style>
		
		<table width="500" class="Riepilogo">
		<tr><th>Chi</th><th>A / Da chi</th><th>Quanto</th></tr>
		<?
		
		foreach($StatoPersone as $StatoPersona) {
			// Ciclo le persone
			echo "<tr><td><strong>" . $R->getNomePersonaById($StatoPersona['ID']) . "</strong></td>";
			
			// Sistemo i Debiti
			$firstLoop = true;
			foreach($StatoPersona['Debiti'] as $IdDebitore => $Debito) {
				// Ciclo i debitori di questa persona
				
				if(array_key_exists($IdDebitore, $StatoPersone) && array_key_exists($StatoPersona['ID'], $StatoPersone[$IdDebitore]['Debiti'])) {
					// Il debitore dell'utente $StatoPersona è anche suo creditore. Appiano le differenze
					if($Debito > $StatoPersone[$IdDebitore]['Debiti'][$StatoPersona['ID']]) {
						// Il debito del debitore è più grande
						$StatoPersone[$StatoPersona['ID']]['Debiti'][$IdDebitore] -= $StatoPersone[$IdDebitore]['Debiti'][$StatoPersona['ID']];
						$StatoPersone[$IdDebitore]['Debiti'][$StatoPersona['ID']] = 0;
					} else {
						// Il debito del creditore è più alto del mio credito
						$StatoPersone[$IdDebitore]['Debiti'][$StatoPersona['ID']] -= $Debito;
						$StatoPersone[$StatoPersona['ID']]['Debiti'][$IdDebitore] = 0;
					}
				}
				$somma = $StatoPersone[$StatoPersona['ID']]['Debiti'][$IdDebitore];
				if($somma < 0) {
					$somma *= -1;
					$dir = "A";
					echo (($firstLoop == false) ? '<tr><td></td>' : '') . "<td>" . $dir . " " . $R->getNomePersonaById($IdDebitore) . "</td>".
					"<td>" . $somma . " &euro;</td>";
				} else {
					$dir = "Da";
					// MA non scrivo nulla se devo ricevere... sarà scritto su chi mi deve dare.
					echo "<td>--</td><td>--</td>";
				}
				
				$firstLoop = false;
			}
			
			echo "</tr>";
		}
		
		?>
		</table>
	</div>
		
		
	<div id="pageRiepilogo" style="display:none">
		<iframe width="100%" height="100%" src="plugins/FinancialChart.php"></iframe>
	</div>
	
	<div id="pageAndamento" style="display:none">
		<iframe width="100%" height="100%" src="plugins/FinancialChart.php"></iframe>
	</div>
	
	
	
	
	
	
	<!-- AGGIORNA RESOCONTO -->
	
	<div id="pageAggiorna" style="display:none">
		<h1 style="font-size:16px; color:#333">Aggiorna stato resoconto</h1>
		<div style="margin-left:10px">
			<p>Se il resoconto &egrave; stato fatto e tutti hanno ricevuto i propri soldi, allora premi il pulsante <strong>"Aggiorna resoconto"</strong> che ti permette di mettere in automatico tutti i movimenti relativi al resoconto in stato <strong>"Chiuso"</strong> e quindi di aggiornare la situazione ad adesso.</p>
			<br /><br />
			<input type="button" value="AGGIORNA RESOCONTO" onclick="window.location = FF.Utils.getLocationAndAppend('&action=RIEPILOGHI_DONE');" />
		</div>
	</div>
	
	<!-- FINE AGGIORNA RESOCONTO -->
</div>

<? if(isset($msg) && $msg != null && $msg != "") { ?>
<script>
FF.visual.report('<?=$msg?>')
</script>
<? } ?>