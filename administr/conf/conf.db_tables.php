<?php
require_once ("../inc/Engine.php");
$App->Context = 'AdminConfiguring';
$DB = new DatabaseManager($App->DB_settings);
?>

		<script type="text/javascript">
			FF.UI.addContentTab({
				active	: true, 
				label	: 'Manage tables',
				fn		: function() {
					$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
					new Ajax.Request('conf.db_tables.php', {
						onComplete: function(res){
							$('contentWrapper').update(res.responseText);
							eval(res.responseText.extractScripts());
						}
					});
				}
			});
			FF.UI.addContentTab({
				active	: false, 
				label	: 'Create table',
				fn		: function() {
					$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
					new Ajax.Request('conf.db_new_table.php', {
						onComplete: function(res){
							$('contentWrapper').update(res.responseText);
							eval(res.responseText.extractScripts());
						}
					});
				}
			});
		</script>

		<form name="configurator" id="configurator_form" enctype="multipart/form-data" method="post" action="?action=SAVING_DBTABLES">
		<input type="hidden" name="app_tables" id="_multiple" value="<?php
		$tables = $App->getTables();
		foreach ($tables as $table) {
			echo $table->getAttribute('name').$App->ConfigObj['multifieldseparator'];
		}
		?>" />
		<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
		<tr bgcolor="#f3f3f3"><th colspan="3" align="left"><h4>DB Tables setup</h4></th></tr>
		<tr><td colspan="3"><p>Seleziona le tabelle dal <strong>Database</strong> e spostale nel <strong>Modulo</strong> (e viceversa) per aggiornare la lista gestita</p></td></tr>
		<tr><td colspan="3">
			<table>
			<tr>
				<td valign="top">
					<strong>DATABASE</strong><br />
					<select multiple="multiple" id="_source" style="height: 250px; width: 190px">
					<?php
					$tables = $DB->getTablesName();
					foreach ($tables as $table) {
						if(!$App->hasTable($table))
							echo '<option value="'.$table.'">'.$table.'</option>';
					}
					?>
					</select>
				</td>
				<td>
					<a href="#" id="toleft" onclick="moveOptions($('_source'), $('_target'), $('_target'), $('_multiple'), '***')">&gt;&gt;</a><br>
					<br>
					<a href="#" id="toright" href="($('_target'), $('_source'), $('_target'), $('_multiple'), '***')">&lt;&lt;</a>
				</td>
				<td valign="top">
					<strong>MODULE</strong><br />
					<select multiple="multiple" id="_target" style="height: 250px; width: 190px">
					<?php
					$tables = $App->getTables();
					foreach ($tables as $table) {
						echo '<option value="'.$table->getAttribute('name').'">'.$table->getAttribute('name').'</option>';
					}
					?>
					</select>
				</td>
			</tr>
			</table>
		</td></tr>
		</table>
		
		<br />
		</form>
	
	
	<script>
		document.getElementById('toleft').onclick = function() {
			var sel1 = document.getElementById('_source');
			var sel2 = document.getElementById('_target');
			var selReal = document.getElementById('_target');
			var val = document.getElementById('_multiple');
			var Sep = '***';
			move(sel1, sel2, selReal, val, Sep);
		}
		document.getElementById('toright').onclick = function() {
			var sel1 = document.getElementById('_target');
			var sel2 = document.getElementById('_source');
			var selReal = document.getElementById('_target');
			var val = document.getElementById('_multiple');
			var Sep = '***';
			move(sel1, sel2, selReal, val, Sep);
		}
		function move(sel1, sel2, selReal, val, Sep) {
			for(var i=0; i<sel1.length; i++) {
				if(sel1.options[i].selected) {
					var exists = false;
					for(var j=0; j<sel2.length; j++) {
						if(sel2.options[j].value == sel1.options[i].value) exists = true;
					}
					if(!exists) {
						//inserisco il valore nella select bersaglio e lo levo dall'altra
						var newOpt = new Option();
						newOpt.value = sel1.options[i].value;
						newOpt.text = sel1.options[i].text;
						sel2.options[sel2.options.length] = newOpt;
						
						sel1.options[i] = null;
					}
				}
			}
			// Valorizzo il campo nascosto
			var multiVal = "";
			for(var i=0; i<selReal.length; i++) {
				if(i==0) multiVal = selReal.options[i].value;
				else multiVal += Sep + selReal.options[i].value;
			}
			val.value = multiVal;
		}
		</script>