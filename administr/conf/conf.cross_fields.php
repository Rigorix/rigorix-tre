<?php
require_once ("../inc/Engine.php");
$App->Context = 'ConfiguringCrossfield';
$Table = new Table($_SESSION['FF']['CurrentTable']);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Content</title>
	<style>@import '../css/common.css';</style>
	<script>var table = '<?php echo $Table->name; ?>';</script>
	<script type="text/javascript" src="../js/libs/prototype.js"></script>
	<script type="text/javascript" src="../js/libs/scriptaculous.js"></script>
	<script type="text/javascript" src="../js/FF.js"></script>
	<script type="text/javascript" src="../js/FF.Utils.js"></script>
	<script type="text/javascript" src="../js/FF.EventManager.js"></script>
	<script type="text/javascript" src="../js/FF.Contents.js"></script>
	<script type="text/javascript" src="../js/FF.Configurator.js"></script>
</head>
<body>
	
	<!-- div -->
	
	<?php
	$crossTable = false;
	$crossRef	= false;
	$crossTitle	= false;
	
	/* Se esiste già il nodo, sono in edit */
	if($Table->hasCrossField($_REQUEST['field'])) {
		$Cross = $Table->getCrossFieldConf($_REQUEST['field']);
	
		$crossTable = $Cross->getAttribute('table');
		$crossRef =  $Cross->getAttribute('ref');
		$crossTitle = $Cross->getAttribute('title');
	}
	
	/* altrimenti prendo sono in insert */
	if($_REQUEST['crossTable'])
		$crossTable = $_REQUEST['crossTable'];
	if($_REQUEST['crossTableRef']) 
		$crossRef = $_REQUEST['crossTableRef'];
	if($_REQUEST['crossTableLabel']) 
		$crossTitle = $_REQUEST['crossTableLabel'];
		
	?><table cellspacing="0" cellpadding="4" class="NormalDataTable">
		<tr valign="top">
			<td>
				<h3>Tabella a cui puntare</h3>
				<select multiple="multiple" style="width:100%; max-height:270px; height:270px" onChange="window.location.href = '?field=<?php echo $_REQUEST['field']; ?>&crossTable='+this.options[this.selectedIndex].value"><?php
					foreach($App->getTables() as $db_table) {
						echo '<option value="'.strtolower($db_table->getAttribute('name')).'" '.((strtolower($crossTable) == strtolower($db_table->getAttribute('name')))?"selected":"").'>'.$db_table->getAttribute('name').'</option>';
					}
				?></select>
			</td><?php
	
	if($crossTable != false) {
		$CrossTable = new Table($crossTable);
		?><td width="33%">
			<h3>Campo bridge</h3>
			<select multiple="multiple" style="width:100%" onChange="window.location = '?field=<?php echo $_REQUEST['field']; ?>&crossTable=<?php echo $_REQUEST['crossTable']; ?>&crossTableRef='+this.options[this.selectedIndex].value"><?php
			foreach($CrossTable->getFields() as $name => $type) {
				echo '<option value="'.$name.'" '.(($crossRef == $name)?"selected":"").'>'.$name.'</option>';		
			}
			?></select>
		</td><?php
	}
	
	if($_REQUEST['crossTableRef'] || $crossRef != false) {
		$CrossTable = new Table($crossTable);
		?><td width="33%">
			<h3>Campo da mostrare</h3>
			<select multiple="multiple" style="width:100%" onChange="window.location = '?field=<?php echo $_REQUEST['field']; ?>&crossTable=<?php echo $crossTable; ?>&crossTableRef=<?php echo $crossRef; ?>&crossTableLabel='+this.options[this.selectedIndex].value"><?php
			foreach($CrossTable->getFields() as $name => $type) {
				if($_REQUEST['crossTableLabel'] == $name) 
					$selected = "selected";
				else if($crossTitle == $name) 
					$selected = "selected";
				else 
					$selected = "";
				echo '<option value="'.$name.'" '.(($crossTitle == $name)?"selected":"").'>'.$name.'</option>';		
			}
			?></select>
		</td><?php
	}
	?>
	</tr></table>
	<!-- /div -->
	
	<? 	if($_REQUEST['crossTableLabel'] || $crossTitle != false) { ?>
		<div class="popupButtonRow">
			<?
			if($_REQUEST['action'] == 'SAVE') {
				
				$App->doBackup('CONF: Before new cross field settings for table "'.$Table->name.'", field "'.$_REQUEST['field'].'"');
				
				
				if(!$Table->hasCrossField($_REQUEST['field'])) {
					/* Sono in insert, creo il nodo */
					$Table->getFieldByName($_REQUEST['field'])->appendChild($App->dom->createElement('cross', ''));
				}
				$CrossNode = $Table->getCrossFieldConf($_REQUEST['field']);
				$CrossNode->setAttribute('table', $crossTable);
				$CrossNode->setAttribute('ref', $crossRef);
				$CrossNode->setAttribute('title', $crossTitle);
				
				$App->saveConfig();
				
				echo '<div align="right" style="color: green">Salvato correttamente!!</div>';
			} else {
			?>
			<div align="right">
				<input type="button" name="invia" value="IMPOSTA" onClick="window.location = '?field=<?php echo $_REQUEST['field']; ?>&crossTable=<?php echo $_REQUEST['crossTable']; ?>&crossTableRef=<?php echo $_REQUEST['crossTableRef']; ?>&crossTableLabel=<?php echo $_REQUEST['crossTableLabel']; ?>&action=SAVE'">
			</div>
			<?php } ?>
		</div>
	<? } 
	/*
	 * Avendo reistanziato new Table con una seconda tabella, $_SESSION['FF']['CurrentTable'] perde il suo valore iniziale.
	 * Per mantenerlo devo risettarlo qui.
	 */
	$_SESSION['FF']['CurrentTable'] = $Table->name;
	
	?>
	<script type="text/javascript">
		FF.EventManager.addEscAction(function() {
			parent.window._GLOBAL['cross_fields_popup'].close();
			_GLOBAL['cross_fields_popup'] = null;
		});
	</script>
</body>
</html>
