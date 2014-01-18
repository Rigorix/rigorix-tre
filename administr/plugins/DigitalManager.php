<?php

$_DB = new DatabaseManager($App->DB_settings);
function getFileTitleById($id) 
{
	global $_DB;
	$result = $_DB->getSingleObjectQueryCustom("select id,title from digital_storage where id = '".$id."'");
	return $result->title;
}

?>


<div style="padding: 10px">

	<?php
	
	if($_GET['pluginAction'] == "generaKey") {
		
		$key = mt_rand();
		?>
		La chiave generata per l'utente <?=$_POST['user']?> (<?=$_POST['email']?>) &egrave;: <strong><?=md5($key)?><br /><br/></strong>
		Tipo utente: <strong><?=(($_REQUEST['tipo'] == 'press') ? "Giornalista / press" : "Compratore digitale")?></strong><br />
		Potr&agrave; scaricare il file: <strong><?=getFileTitleById($_POST['storageFile'])?></strong>.
		<br /><br />
		<form action="pluginManager.php?action=runPlugin&table=digital_accounts&name=Genera%20account&file=DigitalManager.php&pluginAction=save_key" method="post">
			<input type="hidden" name="user" value="<?=$_POST['user']?>" />
			<input type="hidden" name="email" value="<?=$_POST['email']?>" />
			<input type="hidden" name="storageFile" value="<?=$_POST['storageFile']?>" />
			<input type="hidden" name="key" value="<?=$key?>" />
			<input type="submit" value="CREA CONDIVISIONE" /> <input type="button" value="ANNULLA" onclick="window.location.href = 'pluginManager.php?action=runPlugin&table=digital_accounts&name=Create%20account&file=DigitalManager.php'" /> <!-- (Premendo conferma partirà una mail all'utente con il link per scaricare il file) -->
		</form>
		<?
		
	}
	
	
	if($_GET['pluginAction'] == "save_key") {
		
		$query = "insert into digital_accounts values (null, '".$_POST['storageFile']."', '".$_POST['user']."', '".md5($_POST['key'])."', '".date('Y-m-d')."', '', '0')";
		if($_DB->executeQuery($query)) {
			echo '<strong style="color: green">Completato correttamente</strong><br /><br /><strong>Link per il download: </strong>http://www.maledetto.it/DigitalDownload.php?key='.md5($_POST['key']);
			
			$file = $_DB->getSingleObjectQueryCustom("select title from digital_storage where id = '".$_POST['storageFile']."'");
			
			$eol="\r\n";
			$fromname = "Madcap Collective";
			$fromaddress = "info@maledetto.it";
			# Common Headers
			$headers = "From: ".$fromname."<".$fromaddress.">".$eol;
			$headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;
			$text = 'Ciao ' . $_POST['user'] . ',' . $eol;
			$text .= 'grazie per aver comprato la nostra musica.' . $eol . $eol;
			$text .= 'Eccoti il link da dove scaricare ' . $file->title . ': '. $eol;
			$text .= 'http://www.maledetto.it/DigitalDownload.php?key='.md5($_POST['key']) . $eol . $eol;
			$text .= 'Ti aspettiamo per le ultime novita\'.' . $eol . $eol;
			$text .= '** ' . $eol;
			$text .= 'MADCAP COLLECTIVE' . $eol;
			$text .= '** ' . $eol;
			
			//mail($_POST['email'], 'Madcap Collective: link per scaricare ' . $file->title, $text, $headers);
			mail('info@maledetto.it', 'Madcap Collective: link per scaricare ' . $file->title, $text, $headers);
			
			echo '<br /><br /><a href="pluginManager.php?action=runPlugin&table=digital_accounts&name=Create%20account&file=DigitalManager.php">&raquo; Crea un altro account</a>';
		} else {
			echo $query;
			echo '<strong style="color: red">Errore di inserimento</strong>';
		}
		
	}
	
	
	if(!isset($_GET['pluginAction'])) {
	?>


	<table cellpadding="8" bgcolor="#e3e3e3">
	<tr>
		<td>
			<form action="pluginManager.php?action=runPlugin&table=digital_accounts&name=Genera%20account&file=DigitalManager.php&pluginAction=generaKey" method="post">
			<strong>Nome utente</strong>
		</td>
		<td>
			<input type="text" name="user" />
		<td>
	</tr>
	<tr>
		<td>
			<form action="pluginManager.php?action=runPlugin&table=digital_accounts&name=Genera%20account&file=DigitalManager.php&pluginAction=generaKey" method="post">
			<strong>Email utente</strong>
		</td>
		<td>
			<input type="text" name="email" />
		<td>
	</tr>
	<tr>
		<td>
			<strong>Tipologia</strong>
		</td>
		<td>
			<select name="tipo">
			<option value="press">Stampa</option>
			<option value="buy" selected="selected">Acquisto digitale</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<strong>File da condividere</strong>
		</td>
		<td>
			<select name="storageFile">
				<?
				$result = $_DB->getArrayObjectQueryCustom("select * from digital_storage");
				foreach($result as $row) {
					echo '<option value="'.$row->id.'">'.$row->title.'</option>';
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="Genera account" class="Size20" />
		</td>
	</tr>
	</form>
	</table>
	
	<? } ?>
</div>