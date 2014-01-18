<?php
require_once ("../inc/Engine.php");
$App->Context = 'ConfiguringVirtualfield';
$Table = new Table($_SESSION['FF']['CurrentTable']);

if($_REQUEST['action'] == "save") {
	// Salvo i settaggi
	
	$Field = $Table->getFieldByName($_REQUEST['field']);
	
	if($App->query('.//virtualcross', $Field)->length > 0) 
		$Table->removeVirtualFieldsData($Field);
	else {
		$new = $App->dom->createElement('virtualcross');
		$Field->appendChild($new);
	}
	
	$vnode = $App->query('.//virtualcross', $Field)->item(0);
	
	foreach($_POST as $key => $value) {
		if(stripos($key, 'vcross') !== false && stripos($key, '_value') === false) {
			// Ok, sto controllando un virtualcross
			$newdata = $App->dom->createElement('data');
			$newdata->setAttribute('label', $value);
			$newdata->setAttribute('value', $_POST[str_replace("_label", "_value", $key)]);
			$vnode->appendChild($newdata);
		}
	}
	$App->saveConfig( 'Before saving new virtual fields' );
	
}

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
	<script>
		var VF = {
			save: false, 
			index: 0,
			
			init: function() 
			{
				this.index = $('virtualCrossConf').down(0).childElements().length;
				this.checkStatus();
			},
			
			addVCrossRow: function() 
			{
				this.index++;
				var id = this.index;
				var newRow = new Element('tr').update('<td><input type="button" value="remove" onclick="function(){VF.removeVCrossRow(this)}" /></td>' +
				'<td><input type="text" name="vcross'+id+'_label" /></td>' + 
				'<td><input type="text" name="vcross'+id+'_value" /></td>');
				if($('virtualCrossConf').down(0).nodeName == 'TBODY')
					$('virtualCrossConf').down(0).appendChild(newRow);
				else 
					$('virtualCrossConf').appendChild(newRow);
				this.addSaveButton();
				this.checkStatus();
				this.save = true;
			},
			
			addSaveButton: function() 
			{
				if($('SAVE_btn') == null) {
					var btn = new Element('input', {id: 'SAVE_btn', type: 'button', value: 'SAVE'}).setStyle({
						'background'	: 'green',
						'color'			: '#fff',
						'fontWeight'	: 'bold',
						'border'		: '0',
						'padding'		: '4px',
						'margin'		: '0 5px'
					});
					btn.onclick = function() {
						$('virtualCrossForm').submit();
					}
					$('vf_controller').appendChild(btn);
				}
			},
			
			removeVCrossRow: function(e) 
			{
				Element.remove(e.up(1));
				this.checkStatus();
				this.addSaveButton();
			},
			
			checkStatus: function() 
			{
				if($('virtualCrossConf').down(0).nodeName == 'TBODY')
					var tbody = $('virtualCrossConf').down(0);
				else 
					var tbody = $('virtualCrossConf');
				if(tbody.childElements().length > 2) {
					$('vf_headers').show();
					$('no_vf').hide();
				} else {
					$('vf_headers').hide();
					$('no_vf').show();
				}
			}
		}
	</script>
</head>
<body>
	
	<?php
	$Virtual = $Table->getVirtualFieldConf($_REQUEST['field']);
	?>
	<style>
		body {position: relative}
		h1 {margin: 0; padding: 6px}
		.vf_container {overflow: auto; height: 305px; padding: 5px; position: relative}
		.vf_controller {position: absolute; bottom: 10px; left: 4px; width: 482px; text-align: right}
	</style>
	<div class="vf_container">
		
		<form id="virtualCrossForm" action="?action=save&field=<?php echo $_REQUEST['field'];?>" method="post">
		<table id="virtualCrossConf" cellspacing="0" cellpadding="4" width="100%">
		<tr id="vf_headers" bgcolor="#f2f2f2">
			<th></th>
			<th align="left"><strong>Nome</strong></th>
			<th align="left"><strong>Valore</strong></th>
		</tr>
		<tr id="no_vf">
			<td colspan="3">Non ci sono valori virtuali impostati.</td>
		</tr>
		<?
		if($Virtual != null) {
			// Ci sono dei virtual cross values settati, li mostro
			$i=0;
			foreach($Virtual as $VirtualNode) {
				echo '<tr><td><input type="button" value="remove" onclick="VF.removeVCrossRow(this);"></td><td><input name="vcross_'.$i.'_label" type="text" value="'.$VirtualNode->getAttribute('label').'"></td><td><input name="vcross_'.$i.'_value" type="text" value="'.$VirtualNode->getAttribute('value').'"></td></tr>';
				$i++;
			}
		}
		?>
		</table>
		</form>
	</div>
	
	<div class="vf_controller" id="vf_controller">
		<?php
		if($_REQUEST['action'] == "save") 
			echo '<h3 style="float:left; background: green; padding: 4px 8px; color: #FFF; font-weight: bold">Salvato</h3>';
		?>
		<input type="button" value="R" onclick="window.location.reload();" /> &nbsp; <input type="button" name="addButton" value="AGGIUNGI" class="buttonLeft" onClick="VF.addVCrossRow();" />
	</div>
	
	<script type="text/javascript">
		VF.init();
		FF.EventManager.addEscAction(function() {
			parent.window._GLOBAL['virtual_fields_popup'].close();
			_GLOBAL['virtual_fields_popup'] = null;
		});
	</script>
</body>
</html>
