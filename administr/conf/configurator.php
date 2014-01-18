<?php
require_once ("../inc/Engine.php");
$App->Context = 'Configuring';
$Table = new Table($App->CurrentTable);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Content</title>
	<style>@import '../css/common.css';</style>
	<?php echo $App->setGlobalJsVars(); ?>
	<script type="text/javascript" src="../js/libs/prototype.js"></script>
	<script type="text/javascript" src="../js/libs/scriptaculous.js"></script>
	<script type="text/javascript" src="../js/FF.js"></script>
	<script type="text/javascript" src="../js/FF.Utils.js"></script>
	<script type="text/javascript" src="../js/FF.UI.js"></script>
	<script type="text/javascript" src="../js/FF.EventManager.js"></script>
	<script type="text/javascript" src="../js/FF.Contents.js"></script>
	<script type="text/javascript" src="../js/FF.HtmlEditor.js?root=../"></script>
	<script type="text/javascript" src="../js/FF.Configurator.js"></script>
	<script type="text/javascript" src="../js/FF.Console.js"></script>
	<?php echo $App->setGlobalAppVars(); ?>
</head>
<body>
	
<div id="Configurator">

<? if($_REQUEST['action'] == "SAVING_PREFERENCES") {
	
	/*
	 * Salvo le preferenze normali della tabella
	 */
	
	?><!--div id="conf_messager"><h3><a href="#" onClick="$('conf_messager_debug').style.display = 'block';"><strong>+</strong></a> Salvato</h3><div id="conf_messager_debug"--><?
	
	
	/*
	 * Setting table attributes
	 */
	$Table->DOM->setAttribute('name', 		$_REQUEST['table']);
	$Table->DOM->setAttribute('title', 		$_REQUEST['title']);
	$Table->DOM->setAttribute('visible', 	$_REQUEST['visible']);
	$Table->DOM->setAttribute('dataperpage',$_REQUEST['dataperpage']);
	$Table->DOM->setAttribute('orderdir', 	$_REQUEST['orderdir']);
	$Table->DOM->setAttribute('orderfield', $_REQUEST['orderfield']);
	
	/*
	 * Setting table description
	 */
	if($App->query('.//table/description', $Table->DOM)->length == 0) 
		$Table->DOM->appendChild($App->dom->createElement('description', ''));
	$App->query('.//table/description', $Table->DOM)->item(0)->nodeValue = $_REQUEST['description'];
	
	/*
	 * Gestione dei campi
	 */
	foreach($Table->getFields() as $field => $type) {
		
		if($App->query('./fields', $Table->DOM)->length == 0) {
			// Non c'è il nodo fields, lo aggiunto
			$newField = $App->dom->createElement('fields');
			$Table->DOM->appendChild($newField);
			_log("CONF: Creato il campo fields per la tabella " . $Table->name);
		}
		
		$FieldDOM = $App->query('.//field[@name = "'.$field.'"]', $Table->DOM);
		if($FieldDOM->length == 0) {
			// Non esiste nella configurazione, la creo
			$Fields = $App->query('.//fields', $Table->DOM)->item(0);
			$newField = $App->dom->createElement('field');
			$newField->setAttribute('name', $field);
			$Fields->appendChild($newField);
			$FieldDOM = $newField;
		} else 
			$FieldDOM = $FieldDOM->item(0);			
		
		// Setting field attributes
		$defines = explode(",", $_REQUEST[$field.'_define']);
		$FieldDOM->setAttribute("title", 		$_REQUEST[$field.'_title'] != '' ? $_REQUEST[$field.'_title'] : $_REQUEST[$field.'_name']);
		$FieldDOM->setAttribute("visible", 		((in_array('notvisible', $defines)) ? 'false' : 'true'));
		$FieldDOM->setAttribute("adminfield", 	$_REQUEST[$field.'_adminfield']);
		$FieldDOM->setAttribute("multiple", 	((in_array('multiple', $defines)) ? 'true' : 'false'));
		$FieldDOM->setAttribute("autocomplete",	((in_array('autocomplete', $defines)) ? 'true' : 'false'));
		$FieldDOM->setAttribute("restriction", 	$_REQUEST[$field.'_restrict']);
		$FieldDOM->setAttribute("type", 		$_REQUEST[$field.'_type']);
		
		// Setting field description
		if($App->query('.//description', $FieldDOM)->length == 0) 
			$FieldDOM->appendChild($App->dom->createElement('description', ''));
		$App->query('.//description', $FieldDOM)->item(0)->nodeValue = $_REQUEST[$field . '_description'];
		
		// Setting field default
		if($App->query('.//default', $FieldDOM)->length == 0) 
			$FieldDOM->appendChild($App->dom->createElement('default', ''));
		$App->query('.//default', $FieldDOM)->item(0)->nodeValue = $_REQUEST[$field . '_default'];
	
	}
	
	/*
	 * Gestione plugins
	 */
	foreach($_REQUEST as $key => $value) {
		if(strpos($key, 'plugin') !== false && strpos($key, 'ref') === false) {
			
			list($pluginIndex, $var, $remove) = explode("_", $key);
			if($remove) {
				// Cancello il plugin
				$Plugins = $Table->getPlugins($Table->name);
				foreach ($Plugins as $Plugin) {
					if(md5($Plugin->getAttribute('name')) == $var) 
						$Plugin->parentNode->removeChild($Plugin);
				}
			} else {
				// Lo aggiungo se non esiste
				if($App->query('.//plugin[@name = "'.$_REQUEST[$pluginIndex . '_name'].'"]', $Table->DOM)->length == 0) {
					$NewPlugin = $App->dom->createElement('plugin');
					$NewPlugin->setAttribute('name', $_REQUEST[$pluginIndex . '_name']);
					$NewPlugin->setAttribute('ref', $_REQUEST[$pluginIndex . '_ref']);
					$Plugins = $App->query('.//plugins', $Table->DOM);
					if($Plugins->length == 0) {						
						// Creo il nodo plugins per la tabella
						$PluginsNode = $App->dom->createElement('plugins');
						$Table->DOM->appendChild($PluginsNode);
						$Plugins = $App->query('.//plugins', $Table->DOM);
					}
					$Plugins->item(0)->appendChild($NewPlugin);
				}
				// Lo valorizzo
				$App->query('.//plugin[@name = "'.$_REQUEST[$pluginIndex . '_name'].'"]', $Table->DOM)->item(0)->setAttribute('ref', $_REQUEST[$pluginIndex . '_ref']);
			}
		}
	}

	/*
	 * Salvo i settaggi
	 */
	//$App->doBackup("Configurator: Salvata nuova configurazione per ".$_REQUEST['table']);
	$App->dom->save( $App->file );
	
	echo '<div id="contentHeader_internal"><h1>Configurazione salvata correttamente</h1></div>';
	
}

if (!isset($_REQUEST['action'])) { ?>
		
		<form name="configurator" id="configurator_form" method="post" action="configurator.php?action=SAVING_PREFERENCES&table=<?php echo $Table->name; ?>">
		<input type="hidden" name="TABLE" value="<?=$_REQUEST['configuring']?>" />
		<table width="100%" cellpadding="6" cellspacing="0" bgcolor="#eeeeee">
		<tr bgcolor="c2c2c2">
			<td colspan="2">Tabella: <span style="font-size: 22px"><?php echo $Table->name; ?></span></td>
		</tr>
		<tr bgcolor="#f8f8f8">
			<td>
				<table cellspacing="0" cellpadding="3">
					<tr>
						<td align="right"><strong>TITOLO</strong></td><td><input name="title" class="FF_textfield" type="text" value="<?php echo $Table->title; ?>"></td>
						<td></td>
					</tr>
					<tr bgcolor="#ffffff">
						<td align="right"><strong>SETUP</strong></td>
						<td align="right" nowrap="nowrap"><strong>Visibile</strong></td><td><select class="FF_combobox" name="visible"><option value="true" <?php echo $Table->visible == "true" ? "selected" : ""; ?>>SI</option><option value="false" <?php echo $Table->visible == "false" ? "selected" : ""; ?>>NO</option></select></td>
						<td align="right" nowrap="nowrap"><strong>Dati per pagina</strong></td><td><input class="FF_textfield" restriction="numbers" name="dataperpage" size="3" maxlength="2" type="text" value="<?php echo $Table->dataperpage; ?>"></td>
						<td align="right" nowrap="nowrap"><strong>Ordina per campo</strong></td><td><select class="FF_combobox" name="orderfield" id="fieldsSelect">
						<option value="">--</option>
						<?php
						foreach($Table->getFields() as $name => $type) {
							echo '<option value="'.$name.'" '.(($Table->orderfield == $name)?'selected':'').'>'.$name.'</option>';
						}
						?>
						</select>
						</td>
						<td align="right" nowrap="nowrap"><strong>Verso d'ordinamento</strong></td><td><select class="FF_combobox" name="orderdir"><option value="">--</option><option value="ASC" <?php echo (($Table->orderdir == "ASC")? "selected" : ""); ?>>Crescente</option><option value="DESC" <?php echo (($Table->orderdir == "DESC")? "selected" : ""); ?>>Decrescente</option></select></td>
					</tr>
					<tr bgcolor="#f2f2f2" valign="top">
						<td align="right"><strong>DESCRIZIONE</strong></td>
						<td colspan="9"><textarea class="FF_textarea" style="width: 100%" name="description"><?php echo $Table->description; ?></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
			
		<tr>
			<td colspan="7">
			<div>
			<table cellpadding="7" width="100%" cellspacing="0" id="newFieldsRowTable" bgcolor="#FFFFFF">
			<tr>
				<th>Key</th>
				<th>Nome</th>
				<!-- th>Visible</th -->
				<th>Default</th>
				<!-- th>Multiplo</th -->
				<th>Define</th>
				<th>Restrict</th>
				<th>Tipo</th>
				<th>Cross</th>
				<th>Virtual</th>
			</tr>
			<?php
			$Fields = $Table->getFields();
			foreach($Fields as $field => $type) {
				
				$Field = $Table->getFieldConfig($field);
				$FieldAttr = $Table->getFieldAttributes($Field);
				$Restrictions = explode(",", $FieldAttr['restriction']);
				
				echo '<tr>
					<td><strong>'.(($FieldAttr['title'] != '') ? $FieldAttr['title'] : $FieldAttr['name']).'</strong></td>
					<td><strong><input class="FF_textfield" type="text" namNe="'.$FieldAttr['name'].'_title" value="'.stripslashes($FieldAttr['title']).'" /></strong></td>
					<!-- td><select name="'.$FieldAttr['name'].'_visible"><option '.(($FieldAttr['visible'] != 'false') ? '' : 'selected').' value="true">SI</option><option value="false" '.(($FieldAttr['visible'] == 'false') ? 'selected' : '').'>NO</option></select></td -->
					<td>';
					if($Table->isFieldVirtual($Field) || $Table->isFieldCross($Field)) 
						$Table->printFieldInput($Field, $Table->getFieldDefault($Field), array('appendCustomName' => '_default'));
					else
						echo '<input class="FF_textfield" type="text" name="'.$field.'_default" value="'.stripslashes($Table->getFieldDefault($Field)).'" />';
				echo '</td>
					<td><select multiple="multiple" class="FF_combobox" name="'.$FieldAttr['name'].'_define">' .
						'<option value="notvisible" '.(($FieldAttr['visible'] == 'true') ? '' : 'selected').'>Not visible</option>' . 
						'<option value="multiple" '.(($FieldAttr['multiple'] == 'true') ? 'selected' : '').'>Multiple</option>' . 
						'<option value="autocomplete" '.(($FieldAttr['autocomplete'] == 'true') ? 'selected' : '').'>Autocomplete</option>' . 
					'</select></td>
					<td><select multiple="multiple" class="FF_combobox" name="'.$FieldAttr['name'].'_restrict">' .
						'<option value="numbers" '.((in_array('numbers', $Restrictions)) ? 'selected' : '').'><em>only</em> Number</option>' . 
						'<option value="string" '.((in_array('string', $Restrictions)) ? 'selected' : '').'><em>only</em> String</option>' . 
						'<option value="email" '.((in_array('email', $Restrictions)) ? 'selected' : '').'>Email</option>' . 
						'<option value="notnull" '.((in_array('notnull', $Restrictions)) ? 'selected' : '').'>Not null</option>' . 
					'</select></td>
					<td><select class="FF_combobox" name="'.$FieldAttr['name'].'_type"><option '.(($FieldAttr['type'] == 'db') ? 'selected="selected"' : '').' value="db">Database</option><option value="filesystem" '.(($FieldAttr['type'] == 'filesystem') ? 'selected="selected"' : '').'>Filesystem</option></select></td>
					<td align="center">'.(($Table->isFieldCross($Field)) ? '<a class="cursorPointer" onclick="FF.Configurator.removeCrossfield(\''.$FieldAttr['name'].'\');"><img src="../i/rem.gif"></a> <a class="cursorPointer" onclick="FF.Configurator.openCrossfieldSetup(\''.$FieldAttr['name'].'\');" class="cursorPointer"><img src="../i/edit.gif"></a>' : '<a class="cursorPointer" onclick="FF.Configurator.openCrossfieldSetup(\''.$FieldAttr['name'].'\');"><img src="../i/add.gif"></a>').'</td>
					<td align="center">'.(($Table->isFieldVirtual($Field)) ? '<a class="cursorPointer" onclick="FF.Configurator.removeVirtualfield(\''.$FieldAttr['name'].'\');"><img src="../i/rem.gif"></a> <a class="cursorPointer" onclick="FF.Configurator.openVirtualfieldSetup(\''.$FieldAttr['name'].'\');"><img src="../i/edit.gif"></a>' : '<a class="cursorPointer" onclick="FF.Configurator.openVirtualfieldSetup(\''.$FieldAttr['name'].'\');"><img src="../i/add.gif"></a>').'</td>
				</tr>';
				
			}
			
			?></table><!-- input type="button" value="Aggiungi campo da configurare" onClick="FF.configurator.addFieldSettingRow();"><br--><?php
			
			// Metto la riga per i campi provvisoria e nascosta
			?>
			</div>
			
		<br></td></tr>
		<tr bgcolor="lightblue">
			<td colspan="2">Plugins: <span style="font-size: 22px"><?=$_REQUEST['configuring']?></span></td>
		</tr>
		<tr><td>
			<table cellpadding="5" cellspacing="1" bgcolor="#f3f3f3" id="pluginTable">
			<?
			$Plugins = $Table->getPlugins();
			if($Plugins !== false) {
				echo '<tr><td>&nbsp;</td><td><strong>Nome</strong></td><td><strong>Filename</strong></td></tr>';
				$i=0;
				foreach($Plugins as $Plugin) {
					echo '<tr>
						<td><input type="button" value="remove" onclick="FF.Configurator.removePlugin(this, \''.md5($Plugin->getAttribute('name')).'\');"></td>
						<td><input type="text" value="'.$Plugin->getAttribute('name').'" name="plugin'.$i.'_name" /></td>
						<td><input type="text" value="'.$Plugin->getAttribute('ref').'" name="plugin'.$i.'_ref" /></td>
					</tr>';
					$i++;
				}
			} else {
				echo '<tr><td colspan="3">Non ci sono plugin per questa tabella</td></tr>';
			}
			?>
			</table>
			<input type="button" name="addPlugin" value="Aggiungi plugin" onClick="FF.Configurator.addPluginRow();"><br>
			<br>
		</td></tr>
		</table>
		<br>
		<input type="submit" value="SALVA" />
		</form>
	
	<!-- fine contentWrapper -->
	<? } ?>

</div>

</body>
</html>




