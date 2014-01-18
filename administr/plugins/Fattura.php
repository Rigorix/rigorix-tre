<? 
include ("../inc/Engine.php");
$_DB = new DatabaseManager($App->DB_settings);

function getStatoPdfFattura($id, $textual = true) 
{
	global $_DB;
	
	$Mov = $_DB->getSingleObjectQueryCustom("select * from mc_movimenti where id = '$id'");
	if($textual == true) {
		if($Mov->fatturata == 1)
			return "<span style=color:green>Fattura creata</span>";
		else 
			return "<span style=color:red>Fattura da creare!</span>";
	} else 
		return $Mov->fatturata;
}

function getIdFatturaFromMovimento($id)
{
	global $_DB;
	return $_DB->getSingleObjectQueryCustom("select * from mc_fatture where id_movimento = '$id'");
}

function getMovimentoById($id)
{
	global $_DB;
	return $_DB->getSingleObjectQueryCustom("select * from mc_movimenti where id = '$id'");
}

function getFatturaFromId($id)
{
	global $_DB;
	$Fattura = $_DB->getSingleObjectQueryCustom("select * from mc_fatture where id = $id");
	return $Fattura;
}


if($_GET['action'] == 'Save') {
	
	$query = "insert into mc_fatture values (NULL, '".$_GET['id']."', '".$_GET['tipoMovimento']."','".$_GET['data']."','".$_GET['anno']."','".$_GET['index']."','','".$_GET['descrizione']."','".$_GET['costo']."','','".$_GET['indirizzo']."','".$_GET['intestatario']."','".$_GET['piva']."','".$_GET['imponibile']."','".$_GET['storno']."')";
	_log("FATTURAZIONE: " . $query);
	$_DB->executeQuery($query);
	$fatturataQuery = "update mc_movimenti set fatturata = 1 where id = '".$_GET['id']."'";
	_log("PDT FATTURAZIONE: " . $fatturataQuery);
	$_DB->executeQuery($fatturataQuery);
	echo $query;
	
} else { ?>
	
	<script>
	function createFattura(id) {
		var redirect = window.location.href+'&id='+id+'&actionFattura=create&loadData=false';
		redirect = redirect.gsub('#', '');
		window.location.href = redirect;
	}
	
	function recreateFattura(id) {
		var redirect = window.location.href+'&id='+id+'&actionFattura=recreate';
		redirect = redirect.gsub('#', '');
		window.location.href = redirect;
	}
	
	var popupBox = '';
	
	function SaveFatturaAndView(f) {
		new Ajax.Request('plugins/Fattura.php?action=Save&'+$('FatturaForm').serialize(), {
			method: 'get',
			onComplete: SaveFatturaAndView_handler
		});
	}
	
	function SaveFatturaAndView_handler(ajax) {
		$('FatturaForm').submit();
	}
	
	function vediFattura(id)
	{
		var redirect = window.location.href+'&id='+id+'&actionFattura=view';
		redirect = redirect.gsub('#', '');
		window.location.href = redirect;
	}
	
	function openFattura(id) {
		alert("open fattura")
		Animations.Popup.setFadeLayerDims();
		Animations.Popup.appearFadeLayer();
		/*new Ajax.Request('plugins/fatturaResponder.php?idMovimento=' + id, {
			method: 'get',
			onComplete: finishOpenFattura
		});
		*/
		var content = Builder.node('DIV', {id: 'ttt', style: 'padding: 10px'}, 'Sto creando la fattura...');
		popupBox = Animations.Popup.create(400, 300, 'plugins/datiFattura.php?idMovimento='+id, 'iframe');
		//FF.popup('plugins/pdfCreator/pdfCreator.php', 800, 600);
	}
	
	function finishOpenFattura(ajax) {
		var d = setInterval(function(){
			clearInterval(d);
			$('ttt').innerHTML = "Creazione completata.<br /><br /><a href=\"#\" onclick=\"FF.popup('pdfCreator/pdfCreator.php?idMovimento=');\">Clicca qui per aprirla</a> ("+ajax.responseText+")";
		}, 2000);
	}
	
	</script>
	
	<? if($_GET['actionFattura'] == 'create') { ?>
		
		<h1 style="font-size:16px; color:#333">Inserisci dati fatturazione</h1>
		<form target="_blank" id="FatturaForm" action="plugins/pdfCreator/pdfCreator.php" method="post">
		<?
		$Fattura = $_DB->getSingleObjectQueryCustom("select * from mc_movimenti where id = ".$_GET['id']);
		
		list($a, $m, $g) = explode("-", $Fattura->data);
		$res2 = $_DB->getSingleObjectQueryCustom("select count(*) as tot from mc_fatture where anno = '".$a."'");
		echo '<input type="hidden" name="anno" value="'.$a.'" />';
		
		foreach($Fattura as $key => $value) {
			if(!is_numeric($key) && $key != 'descrizione') 
				echo '<input type="hidden" name="'.$key.'" value="'.str_replace('"', "'", $value).'" />';
		}
		?>
		<table cellpadding="8" cellspacing="1" width="60%" style="border: 2px solid #666">
		<tr><td bgcolor="#f3f3f3" nowrap="nowrap"><strong>Fattura numero:</strong></td><td><input type="text" size="2" name="index" value="<?=($res2->tot+1);?>" /> &nbsp; Anno: <strong><?=$a?></strong></td></tr>
		<tr><td bgcolor="#f3f3f3" nowrap="nowrap"><strong>Descrizione fattura:</strong></td><td><input type="text" size="45" name="descrizione" value="<?=addslashes($Fattura->descrizione);?>" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Tipo fattura:</strong></td><td><?=(($Fattura->fattura == 1)?"Italiana":"Estera")?></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Cifra:</strong></td><td><?=$Fattura->costo?>&euro;</td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Data:</strong></td><td><?=$Fattura->data?></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Intestato a:</strong></td><td><input type="text" name="intestatario" size="45" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Indirizzo:</strong></td><td><input type="text" name="indirizzo" size="45" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Partita IVA:</strong></td><td><input type="text" name="piva" size="45" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Imponibile IVA:</strong></td><td><select name="imponibile"><option value="si">SI</option><option value="no">NO</option></select></td></tr>
		<tr valign="top"><td bgcolor="#f3f3f3"><strong>Storno:</strong></td><td><select name="storno"><option value="1">SI</option><option value="0">NO</option></select>
			<br />Se si imposta Storno = si, la cifra di cui sopra (<?=$Fattura->costo?>&euro;) viene ricalcolata in modo che risulti essere la cifra finale comprensiva di iva al 20%.<br />
			In questo caso: <strong>Cifra</strong> (<?php echo round($Fattura->costo/1.2, 2); ?>&euro;) + <strong>20% IVA</strong> (<?php echo round(round($Fattura->costo/1.2, 2) * .2, 2); ?>&euro;) = <strong>Totale:</strong> <?php echo ($Fattura->costo); ?>&euro;
		</td></tr>
		<tr><td colspan="2" align="center"><input type="button" value="CREA FATTURA" onclick="SaveFatturaAndView();" /></td></tr>
		</table>
		</form>
	
	<? } else if ($_REQUEST['actionFattura'] == 'recreate') { ?>
		
		<h1 style="font-size:16px; color:#333">Inserisci dati fatturazione</h1>
		<form target="_blank" id="FatturaForm" action="plugins/pdfCreator/pdfCreator.php" method="post">
		<?
		$Fattura = $_DB->getSingleObjectQueryCustom("select * from mc_movimenti where id = ".$_GET['id']);
		$Data = getFatturaFromId(getIdFatturaFromMovimento($Fattura->id));
		
		echo '<input type="hidden" name="anno" value="'.$Data->anno.'" />';
		
		foreach($Fattura as $key => $value) {
			if(!is_numeric($key) && $key != 'descrizione') 
				echo '<input type="hidden" name="'.$key.'" value="'.str_replace('"', "'", $value).'" />';
		}
		?>
		<table cellpadding="8" cellspacing="1" width="60%" style="border: 2px solid #666">
		<tr><td bgcolor="#f3f3f3" nowrap="nowrap"><strong>Fattura numero:</strong></td><td><input type="text" size="2" name="index" value="<?=$Data->indice?>" /> &nbsp; Anno: <strong><?=$Data->anno?></strong></td></tr>
		<tr><td bgcolor="#f3f3f3" nowrap="nowrap"><strong>Descrizione fattura:</strong></td><td><input type="text" size="45" name="descrizione" value="<?=addslashes($Data->testo);?>" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Tipo fattura:</strong></td><td><?=(($Fattura->fattura == 1)?"Italiana":"Estera")?></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Cifra:</strong></td><td><?=$Fattura->costo?>&euro;</td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Data:</strong></td><td><?=$Data->data?></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Intestato a:</strong></td><td><input type="text" name="intestatario" size="45" value="$Data->intestatario" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Indirizzo:</strong></td><td><input type="text" name="indirizzo" size="45" value="$Data->indirizzo" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Partita IVA:</strong></td><td><input type="text" name="piva" size="45" value="$Data->partita_iva" /></td></tr>
		<tr><td bgcolor="#f3f3f3"><strong>Imponibile IVA:</strong></td><td><select name="imponibile"><option value="si" <?=(($Data->imponibileIva == 'si') ? " selected" : "")?>>SI</option><option value="no"<?=(($Data->imponibileIva == 'no') ? " selected" : "")?>>NO</option></select></td></tr>
		<tr valign="top"><td bgcolor="#f3f3f3"><strong>Storno:</strong></td><td><select name="storno"><option value="1" <?=(($Data->storno == '1') ? "selected" : "")?>>SI</option><option value="0" <?=(($Data->storno == '0') ? "selected" : "")?>>NO</option></select>
			<br />Se si imposta Storno = si, la cifra di cui sopra (<?=$Fattura->costo?>&euro;) viene ricalcolata in modo che risulti essere la cifra finale comprensiva di iva al 20%.<br />
			In questo caso: <strong>Cifra</strong> (<?php echo round($Fattura->costo/1.2, 2); ?>&euro;) + <strong>20% IVA</strong> (<?php echo round(round($Fattura->costo/1.2, 2) * .2, 2); ?>&euro;) = <strong>Totale:</strong> <?php echo ($Fattura->costo); ?>&euro;
		</td></tr>
		<tr><td colspan="2" align="center"><input type="button" value="CREA FATTURA" onclick="SaveFatturaAndView();" /></td></tr>
		</table>
		</form>
		
	<? } else if ($_REQUEST['actionFattura'] == 'view') { ?>
		
		<?php
		$Fattura = getFatturaFromId($_REQUEST['id']);
		$Movimento = getMovimentoById($Fattura->id_movimento);
		?>

		
		<form target="_blank" id="FatturaForm" action="plugins/pdfCreator/pdfCreator.php" method="post">
		<input type="text" size="2" name="index" value="<?=$Fattura->indice?>" />
		<input type="text" size="45" name="descrizione" value="<?=addslashes($Fattura->testo);?>" />
		<input type="text" size="45" name="testo" value="<?=addslashes($Fattura->testo);?>" />
		<input type="text" name="intestatario" size="45" value="<?=$Fattura->intestatario?>" />
		<input type="text" name="indirizzo" size="45" value="<?=$Fattura->indirizzo?>" />
		<input type="text" name="piva" size="45" value="<?=$Fattura->partita_iva?>" />
		<input type="text" name="imponibile" size="45" value="<?=$Fattura->imponibileIva?>" />
		<input type="text" name="fattura" size="45" value="<?=$Movimento->fattura?>" />
		<input type="text" name="anno" size="45" value="<?=$Fattura->anno?>" />
		<input type="text" name="data" size="45" value="<?=$Fattura->data?>" />
		<input type="text" name="costo" size="45" value="<?=$Fattura->valore?>" />
		<input type="text" name="storno" size="45" value="<?=$Fattura->storno?>" />
		</form>
		
		<script>
			$('FatturaForm').submit();
			history.go(-1);
		</script>
		
	<? } else { ?>
	
		<h1 style="font-size:16px; color:#333">Movimenti da fatturare</h1>
		<table cellpadding="4" cellspacing="0" width="100%" class="contentsTable" id="contentsTable">
		<tr><th></th><th>Movimento</th><th>Descrizione</th><th>Data</th><th>Tipo fattura</th><th>PDF fattura</th></tr>
		<?
		$res = $_DB->getArrayObjectQueryCustom("select * from mc_movimenti where fattura != '0' order by data desc");
		$bg = '#ffffff';
		foreach($res as $value) {
			if($bg == '#ffffff') $bg = '#f3f3f3';
			else $bg = '#ffffff';
			?><tr bgcolor="<?=$bg?>">
				<td>
					<?php if(getStatoPdfFattura($value->id, false) == 0) { ?></td>
						<input type="button" onclick="createFattura('<?=$value->id;?>');" value="Crea fattura" style="font-size:16px" />
					<?php } else { ?>
						<input type="button" onclick="recreateFattura('<?=$value->id;?>');" value="Ricrea fattura" style="font-size:16px" />
						<input type="button" onclick="vediFattura('<?=getIdFatturaFromMovimento($value->id)->id;?>');" value="Vedi fattura" style="font-size:16px" />
					<?php } ?>
				</td>
				<td><a href="edit.php?action=EDIT&table=mc_movimenti&editField=id&editId=<?=$value->id?>">Vedi / Modifica</a></td>
				<td><?=$value->descrizione;?></td>
				<td><?=$value->data;?></td>
				<td><?=(($value->fattura == 1)? "Italiana" : "Estera")?></td>
				<td><?=getStatoPdfFattura($value->id);?></td>
			</tr><?
		}
		?>
		</table>
	
	<? } ?>

<? } ?>