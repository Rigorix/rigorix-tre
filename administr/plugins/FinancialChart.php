<?php

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

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/xhtml; charset=iso-8859-1">
<title>Contenuti</title>
<style type="text/css">@import '../css/common.css';</style>
<style>
.smallTable { font-size:12px; background:#ffffbe}
.smallTable TR TH { background:#CCCCCC; text-align:left}
.smallTable TR TD { border:0}
.contentsTable TH { font-size:14px; font-weight:normal}
</style>
<script type="text/javascript" src="../js/common.js"></script>
</head>
<body>

	<?
	$res = $_DB->getArrayObjectQueryCustom("select data from mc_movimenti order by data asc limit 1");
	$startDate = $Utils->dbDateToItalian($res->data, "/");
	?>
	
	<div>
		
		<h1 style="font-size:16px; color:#333">Andamento finanziario Madcap</h1>
		<p style="margin-left:10px">A partire da: <?=$startDate?><br /></p>
		<div style="margin-left:10px">
			<br /><br />
			<h3>Uscite</h3>
			<p>Senza includere le commissioni paypal.<br /></p>
			
			<?
			$query = "select sum(costo) as tot, data from mc_movimenti where tipoMovimento = 8 group by month(data)";
			$res = $_DB->getArrayObjectQueryCustom($query);
			echo '<table style="margin: 10px 0; border: 2px solid #999; background: #f3f3f3"><tr valign="bottom">';
			foreach($res as $obj) {
				echo '<td style="width: 40px;font-size: 10px;" align="center"><div style=" background: red; height: '.$obj->tot.'px">'.$obj->tot.'</div>'.$Utils->getDateMonthName($obj->data, "/").' \''.$Utils->getYearTruncated($res[$k]['data'], 2, 2).'</td>';				
			}
			echo '</tr></table>';
			
			$query = "select sum(costo) as tot from mc_movimenti where tipoMovimento = 8";
			$res = $_DB->getSingleObjectQueryCustom($query);
			$totaleSpeso = $res->tot;
			?>
			
			<p style="color: red"><?=$totaleSpeso?> &euro;</p>
			<br />
			<h3>Entrate</h3>
			<p>Senza includere i soldi dai CD di Beatrice del periodo che erano divisi per 3.<br /></p>
			
			<?
			$query = "select sum(costo) as tot, data from mc_movimenti where tipoMovimento = 7 and personaFinale = 4 group by month(data)";
			$res = $_DB->getArrayObjectQueryCustom($query);
			echo '<table style="margin: 10px 0; border: 2px solid #999; background: #f3f3f3"><tr valign="bottom">';
			foreach($res as $v) {
				echo '<td style="width: 40px;font-size: 10px;" align="center"><div style=" background: green; height: '.$v->tot.'px">'.$v->tot.'</div>'.$Utils->getDateMonthName($v->data, "/").' \''.$Utils->getYearTruncated($v->data, 2, 2).'</td>';				
			}
			echo '</tr></table>';			
			
			$query = "select sum(costo) as tot from mc_movimenti where tipoMovimento = 7 and personaFinale = 4";
			$res = $_DB->getSingleObjectQueryCustom($query);
			$totaleEntrato = $res->tot;
			
			$query = "select sum(costo) as tot from mc_movimenti where tipoMovimento = 7 and personaFinale = 4";
			$res = $_DB->getSingleObjectQueryCustom($query);
			$totaleEntrato = $res->tot;
			?>
			
			<p style="color: green"><?=$totaleEntrato?> &euro;</p>
			<br /><br />
			<h3>Stato conto</h3>
			<p>
			<?
			if($totaleSpeso > $totaleEntrato)
				echo '<strong style="color: red">In passivo di: '.($totaleSpeso - $totaleEntrato).' &euro;</strong>';
			else 
				echo '<strong style="color: green">In attivo di: '.($totaleEntrato - $totaleSpeso).' &euro;</strong>';
			?>
			</p>
			<br /><br />
		</div>

	</div>
	
</div>



