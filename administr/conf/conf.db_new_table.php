<?php
require_once ("../inc/Engine.php");
$App->Context = 'TableCreator';


/*
 * 
 * CREATE TABLE `test`.`test` (
 * 		`id` TINYINT (3) UNSIGNED NOT NULL AUTO_INCREMENT, 
 * 		`tinyint` TINYINT (4) UNSIGNED DEFAULT '0', 
 * 		`varchar` VARCHAR (255) DEFAULT 'cane rognoso' NOT NULL, 
 * 		`date` DATE, 
 * 		`text` TEXT, 
 * 		`int` INT (5) UNSIGNED DEFAULT '0', 
 * 		`bigint` BIGINT (10) UNSIGNED DEFAULT '0', 
 * 		PRIMARY KEY(`id`), UNIQUE(`id`), INDEX(`id`)
 * ) TYPE = MyISAM 
 * /*!40100 DEFAULT CHARSET latin1 COLLATE latin1_swedish_ci */ 
?>
		
		<script type="text/javascript">
			FF.UI.addContentTab({
				active	: false, 
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
				active	: true, 
				label	: 'Create table BETA',
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
		
		<form action="?action=save_new_table&act=db_new_table" method="post" name="newtable_form" id="newtable_form">
			
		<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
		<tr bgcolor="#cccccc"><th colspan="4" align="left"><h4>Create new DB table</h4></th></tr>
		</table>
		
		<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
		<tr valign="top">
			<td width="170">
				<h2 class="subtitle_blue">Table settings</h2>
				<br />
				<strong>DB Name</strong><br />
				<input type="text" name="t_name" value="<?php echo $_SESSION['POST']['t_name']; ?>"><br /><br />
				<strong>Title</strong><br />
				<input type="text" name="t_title" value="<?php echo $_SESSION['POST']['t_title']; ?>"><br /><br />
				<strong>Visibility</strong><br />
				<input type="radio" name="t_visibility" value="visible" style="width: auto !important;" checked="checked" /> Yes &nbsp; <input type="radio" style="width: auto !important;" name="t_visibility" value="hidden" /> No<br /><br />
				<strong>Datas per page</strong><br />
				<input type="text" name="t_dataperpage" value="<?php echo $_SESSION['POST']['t_dataperpage']; ?>"><br /><br />
				
				<!-- Se c'è almeno un campo, allora mostro l'ordinamento -->
				<?php if(isset($_REQUEST['field1'])) { ?>
					<strong>Order field</strong><br />
					<input type="text" name="t_orderfield" value="<?php echo $_SESSION['POST']['t_orderfield']; ?>"><br /><br />				
					<strong>Direction</strong><br />
					<input type="text" name="t_orderdir" value="<?php echo $_SESSION['POST']['t_orderdir']; ?>"><br />
				<?php } ?>
			</td>
			<td width="30" valign="top">
				<br /><br /><a class="cursorPointer next_blue" onclick="$('newtable_form').submit();">&raquo;</a>
			</td>
			
			<?php if(isset($_SESSION['POST']['t_name']) && $_SESSION['POST']['t_name'] != '') { ?>
				<td>
					<h2 class="subtitle_blue">Field settings</h2>
					<br />
					<table cellpadding="0" cellspacing="0" id="field_table" class="zen borderRight padding lightTh">
					<thead>
						<tr>
							<th>&nbsp;</td>
							<th><strong>Name</strong></th>
							<th><strong>Type</strong></th>
							<th><strong>Length</strong></th>
							<th><strong>Default</strong></th>
							<th><strong>Key field</strong></th>
							<th><strong>Autoincrement</strong></th>
						</tr>
					</thead>
					<tbody>
					<tr>
						<td valign="bottom" align="right">
							<a style="color: gray">Remove</a>
						</td>
						<td>
							<input type="text" name="field0_name" value="id">
						</td>
						<td>							
							<select name="field0_type">
								<option value="tinyint">Tiny int</option>
								<option value="int" selected="selected">Int</option>
								<option value="bigint">Big int</option>
								<option value="date">Date</option>
								<option value="varchar">Varchar</option>
								<option value="text">Text</option>
							</select>
						</td>
						<td>
							<input type="text" name="field0_length" value="5" size="4">
						</td>
						<td>
							<input type="text" name="field0_default" value="5" size="4">
						</td>
						<td>
							<input type="radio" name="field0_key" value="1" style="width: auto !important;" /> Yes &nbsp; <input type="radio" style="width: auto !important;" name="field0_key" value="0" checked="checked" /> No
						</td>
						<td>
							<input type="radio" name="field0_autoincrement" value="1" style="width: auto !important;" /> Yes &nbsp; <input type="radio" style="width: auto !important;" name="field0_autoincrement" value="0" checked="checked" /> No
						</td>
					</tr>
					</tbody>
					</table>
					<a class="cursorPointer normal" onclick="FF.Configurator.addTableFieldRow(this);" style="padding: 7px 3px; line-height: 30px">Add</a>
				</td>
				<td valign="top">
					<br /><br /><a class="cursorPointer next_blue" onclick="FF.Configurator.setModuleProps($('properties').serialize());">&raquo;</a>
				</td>
			<? } ?>
			
		</tr>
		</table>
		
		</form>
		<br />
		