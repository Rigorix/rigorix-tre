<?php
require_once ("../inc/Engine.php");
$App->Context = 'EditUser';
$User_Obj = $User;
$User = $App->getUserByName($_REQUEST['user']);

/*
 * Salvo le modifiche all'utente
 */
 
if($_REQUEST['action'] == 'SAVE') {
	
	if(!$App->userExists($_REQUEST['userEdit'])) {

		// L'utente non esiste, lo creo
		$App->addUser($_REQUEST['username'], $_REQUEST['userType'], $_REQUEST['pwd']);
		$usertables = $Utils->getMultipleValue($_REQUEST['userTables']);
		foreach ($usertables as $table) {
			$App->addUserTable($_REQUEST['username'], $table);
		}
		$App->doBackup("Creating new user: " . $_REQUEST['username']);
		$App->dom->save( $App->file );
		$RESULT = "Utente creato correttamente!";
		$User = $App->getUserByName($_REQUEST['username']);

	} else {
		
		// L'utente esiste, lo modifico
		$User = $App->getUserByName($_REQUEST['userEdit']);
		$User->setAttribute('name', $_REQUEST['username']);
		$User->setAttribute('pwd', $_REQUEST['pwd']);
		$User->setAttribute('type', $_REQUEST['userType']);
				
		$usertables = $Utils->getMultipleValue($_REQUEST['userTables']);
		foreach ($usertables as $table) {
			$App->addUserTable($_REQUEST['username'], $table);
		}
		$App->doBackup("Updating user settings for " . $_REQUEST['username']);
		$App->dom->save( $App->file );
		$RESULT = "Utente modificato correttamente!";
		$User = $App->getUserByName($_REQUEST['username']);
	}
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
	<script type="text/javascript" src="../js/FF.Contents.js"></script>
	<script type="text/javascript" src="../js/FF.Configurator.js"></script>
</head>
<body>
	
<h4>Modifica/Mostra utente</h4>
<?php 
/*
 * Messaggi sulle action (EDIT, DELETE, INSERT)
 */
if($_REQUEST['action'] == 'SAVE') 
	echo '<p style="color: red"><br />' . $RESULT . '</p><script>parent.window.FF.Configurator.refreshTab();</script>';
if($_REQUEST['action'] == 'DELETED') {
	echo '<p style="color: red"><br />Utente ' . $_REQUEST['user'] . ' cancellato correttamente!</p>'; 
	exit;
}
if($_REQUEST['action'] == 'NOT_DELETED') 
	echo '<p style="color: red"><br />Impossibile cancellare l\'utente ' . $_REQUEST['user'] . '!</p>';
?>
<br />
<form action="?action=SAVE" method="post">
<input type="hidden" name="userEdit" value="<?php echo $_REQUEST['user']; ?>" />
<table>
	<tr valign="top">
		<td>Nome utente: </td>
		<td><input type="text" name="username" value="<?php echo $User->getAttribute('name'); ?>" /></td>
		<td rowspan="3">Tabelle: </td>
		<td rowspan="4">
			<input type="hidden" name="userTables" id="userTablesId" value="" />
			<select name="userTables_selection" size="10" multiple="multiple" onChange="FF.Configurator.memSelectedTables(this, 'userTablesId', '<?php echo $App->ConfigObj['multifieldseparator']; ?>');">
			<?php
			$tables = $App->getUserTablesArray($User->getAttribute('name'));
			
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
	<tr>
		<td>Password: </td>
		<td><input type="text" name="pwd" value="<?php echo $User->getAttribute('pwd'); ?>" /></td>
	</tr>
	<tr>
		<td>Moduli: </td>
		<td>
			<?php
			$userModules = $User_Obj->getUserModules();
			$userModulesList = $Utils->serializeArray($userModules, ', ');
			?>
			<input readonly="readonly" value="<?=$userModulesList?>" /> <strong>BETA</strong>
		</td>
	</tr>
	<tr>
		<td>Tipo utente: </td>
		<td><select name="userType">
			<option value="0" <?php echo $User->getAttribute('type') == 0 ? 'selected="selected"' : ''; ?>>Utente semplice</option>
			<option value="1" <?php echo $User->getAttribute('type') == 1 ? 'selected="selected"' : ''; ?>>Utente amministratore</option>
			<option value="2" <?php echo $User->getAttribute('type') == 2 ? 'selected="selected"' : ''; ?>>God</option>
		</select></td>
	</tr>
	<tr>
		<td colspan="4" align="center"><input type="submit" value="Modifica utente" /> <input type="button" value="Cancella utente" onclick="FF.Configurator.removeUser('<?php echo $_REQUEST['user']; ?>')" /> </td>
	</tr>
</table>
</form>