<?php
require_once ("../inc/Engine.php");
$App->Context = 'CreatingUser';

/*
 * Salvo il nuovo utente
 */
if($_REQUEST['action'] == 'SAVE') {
	
	if(!$App->userExists($_REQUEST['username'])) {
		
		$App->doBackup('Creating new user ('.$_REQUEST['username'].')');
		
		// L'utente non esiste, lo creo
		$App->addUser($_REQUEST['username'], $_REQUEST['userType'], $_REQUEST['pwd']);
		$usertables = $Utils->getMultipleValue($_REQUEST['userTables']);
		foreach ($usertables as $table) {
			$App->addUserTable($_REQUEST['username'], $table);
		}
		$RESULT = '<br />Utente '.$_REQUEST['username'].' creato correttamente!';

	} else 
		$RESULT = "<br />L'utente esiste. Operazione annullata";
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
	
<h4>Aggiungi utente</h4>
<?php
if($_REQUEST['action'] == 'SAVE') {
	echo '<p style="color: red">' . $RESULT . '</p>';
	exit;
}
?>
<br />
<form action="?action=SAVE" method="post">
<table>
	<tr valign="top">
		<td>Nome utente: </td>
		<td><input type="text" name="username" /></td>
		<td rowspan="3">Tabelle: </td>
		<td rowspan="3">
			<input type="hidden" name="userTables" id="userTablesId" value="" />
			<select name="userTables_selection" size="10" multiple="multiple" onChange="FF.Configurator.memSelectedTables(this, 'userTablesId', '<?php echo $App->ConfigObj['multifieldseparator']; ?>');">
			<?
			foreach($App->getTables() as $table) {
				echo '<option value="'.$table->getAttribute('name').'">'.$table->getAttribute('title').'</option>';
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Password: </td>
		<td><input type="password" name="pwd" value="" /></td>
	</tr>
	<tr>
		<td>Tipo utente: </td>
		<td><select name="userType"><option value="0">Utente semplice</option><option value="1">Utente amministratore</option><option value="2">God</option></select></td>
	</tr>
	<tr>
		<td colspan="4" align="center"><input type="submit" value="Crea utente" /></td>
	</tr>
</table>
</form>