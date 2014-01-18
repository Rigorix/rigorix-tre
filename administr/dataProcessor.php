<?php
require_once ("inc/Engine.php");
$App->Context = 'ProcessingContent';
$Table = new Table($_POST['processTABLE']);
?>
<style>
	h2, p {font-size:18.4px; font-family:System; font-weight: normal; }
	p {font-size: 14px}
</style>
<script>var Dashboard = top.window.dashboard;</script>
<div style="padding: 20px">
	<h2>Data processing...</h2>
	<?
	if($_POST['processACTION'] == 'EDIT') {
		
		// *** EDIT ***
		?><p>Editing...<?
		$query = "UPDATE ".$_POST['processTABLE']." SET";
		$first = true;
		foreach($Table->getFields() as $name => $type) {
			?>.<?
			if($name != $_POST['processIDFIELD']) {
				if($_POST['loadingType_' . $name] == 'BROWSE') {
					$value = $App->uploadFile($name . '_filesystem', $Table->name);
				} else {
					$value = $Utils->addSlashes($_POST[$name]);
				}
				if(!$first) 
					$query .= ',';
				$first = false;
				$query .= " $name = '".mysql_real_escape_string($value)."'";
			}
			
		}
		$query .= " WHERE " . $_POST['processIDFIELD'] . " = " . $_POST['processID'];
		
		if($Table->executeQuery($query)) {
			_log("EDIT: query OK. ($query)");
			if(isset($_GET['action']) && $_GET['action'] == 'doItAgain') {?>
	      		<script type="text/javascript">
	      			Dashboard.reportsToShow.push({text: 'Content successfully edited!'});
	      			window.location.href = 'edit.php?table=<?php echo $Table->name; ?>&action=EDIT';
				</script>
	    	<? } else { ?>
	      		<script type="text/javascript">
	      			Dashboard.reportsToShow.push({text: 'Content successfully edited!'});
	      			window.location.href = 'content.php?table=<?php echo $Table->name; ?>';
				</script>
	    	<? }
		} else {
			_log("EDIT: query KO. ($query)");
			echo "La modifica non e' andata a buon fine";
		}
		
		echo '<hr><input type="button" value="VAI ALLA LISTA" onclick="window.location = \'content.php?table='.$Table->name.'\'"> <input type="button" value="MODIFICA NUOVAMENTE" onclick="history.go(-1);"><hr>';
		
	} else if ($_POST['processACTION'] == 'INSERT') {
		
			// *** EDIT ***
		$query = "INSERT INTO ".$_POST['processTABLE']."(";
		
		$first = true;
		$second = false;
		foreach($Table->getFields() as $name => $type) {
			
			if($name != $_POST['processIDFIELD']) {
				$value = $Utils->addSlashes($_POST[$name]);
				if(!$first) {
					if($second) $query .= ', ';
					else $second = true;
					$query .= " $name";
				} $first = false;
			}
			
		}
		$query .= ") VALUES (";
		
		$first = true;
		$second = false;
		foreach($Table->getFields($_POST['processTABLE']) as $name => $type) {
			
			if($name != $_POST['processIDFIELD']) {
				if($_POST['loadingType_' . $name] == 'BROWSE') {
					$value = $App->uploadFile($name . '_filesystem', $_POST['processTABLE']);
				} else {
					$value = $Utils->addSlashes($_POST[$name]);
				}
				if(!$first) {
					if($second) $query .= ', ';
					else $second = true;
					$query .= "'".mysql_real_escape_string($value)."'";
				} 
				$first = false;
			}
			
		}
		$query .= ")";
		
		if($Table->executeQuery($query)) {
			_log("INSERT: query OK. ($query)");
		  if(isset($_GET['action']) && $_GET['action'] == 'doItAgain') {?>
	      		<script type="text/javascript">
	      			Dashboard.reportsToShow.push({text: 'Content successfully inserted!'});
	      			window.location.href = 'edit.php?table=<?php echo $Table->name; ?>&action=INSERT';
				</script>
	    	<? } else { ?>
	      		<script type="text/javascript">
	      			Dashboard.reportsToShow.push({text: 'Content successfully inserted!'});
	      			window.location.href = 'content.php?table=<?php echo $Table->name; ?>';
				</script>
	    	<? }
		} else {
			_log("INSERT: query KO. ($query)");
			echo "L'inserimento non e' andato a buon fine";
		}
		
		echo '<hr><input type="button" value="VAI ALLA LISTA" onclick="window.location = \'content.php?table='.$Table->name.'\'"> <input type="button" value="INSERISCI NUOVAMENTE" onclick="history.go(-1);"><hr>';
		
	} else if($_GET['processACTION'] == 'DEL') {
		
		$query = "delete from ".$_GET['table']." where ".$_GET['idField']." = ".$_GET['id'];
		
		if($Table->executeQuery($query)) {
			_log("DELETE: query OK. ($query)");
			echo "<script>window.location.href = 'content.php?table=".$_GET['table']."&showMessage=delete_data_ok';</script>";
		} else {
			_log("DELETE: query KO. ($query)");
			echo "<br /><br />Errore nella cancellazione del dato!!<br><br>";
		}
		
	}
	
	
	?></p>
</div>
