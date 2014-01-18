<?php 
$UserObj = $App->getUserByName($_REQUEST['userEdit']);
?>
<style>
	.config-user-tb { background: #eee; width: 100%; margin: 8px 0; }
	.config-user-tb tr td { background: #eee; }
	.config-user-tb tr td hr { box-shadow: 1px 1px 3px #dedede; border: 0; border-bottom: 1px solid #cdcdcd; height: 1px; margin: 10px 0; display: block; }
	.config-user-tb tr td label { margin: 4px 0; font-size:13px; display: block; }
</style>
<table class="config-user-tb" cellpadding="7">
	<tr valign="top">
		<td width="50%">
			<label>Nome utente</label>
			<input type="text" name="name" value="<?=$UserObj->getAttribute('name')?>" />
			<hr />
			<label>Password</label>
			<input type="text" name="pwd" value="<?=$UserObj->getAttribute('pwd')?>" />
			<hr />
			<label>Tipo utente</label>
			<select name="type">
				<option value="1" <? if ($UserObj->getAttribute('type')=="1") echo 'selected="true"'; ?>>Utente normale</option>
				<option value="2" <? if ($UserObj->getAttribute('type')=="2") echo 'selected="true"'; ?>>Utente amministratore</option>
			</select>
		</td>
		<td width="50%">
			<label>Tabelle</label>
			<select name="userTables_selection" style="width: 300px" size="14" multiple="multiple">
				<?php
				$tables = $App->getUserTablesArray($UserObj->getAttribute('name'));
				foreach($App->getTables() as $table) {
					$sel = '';
					if(in_array($table->getAttribute('name'), $tables))
						$sel = 'selected="selected"';
					echo '<option value="'.$table->getAttribute('name').'" '.$sel.'>'.$table->getAttribute('title').'</option>';
				}
				?>
			</select>
		</td>
	</tr>
</table>