<?php
require_once ("../inc/Engine.php");
$App->Context = 'AdminConfiguring';
?>
		
		<form name="configurator" id="configurator_form" enctype="multipart/form-data" method="post" action="?action=USER_MANAGER">
		<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
		<tr bgcolor="#f3f3f3"><th colspan="4" align="left"><h4>User setup</h4></th></tr>
		<tr valign="top"><td><strong>Lista utenti</strong></td><td valign="top">
			<table bgcolor="#f3f3f3" cellpadding="5">
			<tr valign="top"><td>
			<?
			// Mostro gli utenti esistenti
			if($App->getTotUsers() > 0) {
				
				echo '<select name="userList" onclick="FF.Configurator.editUserForm(this);" multiple style="width: 300px" id="userListSelect">';
				foreach ($App->getUsers() as $user) {
					echo '<option value="'.$user->getAttribute('name').'">'.$user->getAttribute('name').'</option>';
				}
				echo '</select>';
				
			} else
				echo "<strong>Non ci sono Utenti impostati</strong>";
				
			?>
			<br /><br />
			<input type="button" value="Aggiungi utente" id="newUserButton" onClick="FF.Configurator.addNewUser();" > 
			<input type="hidden" name="addNewUser" value="false">
			<input type="button" style="display:none" name="EditUser" id="btnEditUser" value="Modifica utente"> 
			<input type="button" style="display:none" name="RemoveUser" id="btnRemoveUser" value="Rimuovi utente" onClick="FF.Configurator.removeUser(document.configurator.userList[document.configurator.userList.selectedIndex].value);"> 
			<div id="userActionConsole"></div>
			</td></tr>
			</table>
			
		</td></tr>
		</table>
		
		<br />
		</form>
	