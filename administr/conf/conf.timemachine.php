<?php
require_once ("../inc/Engine.php");
$App->Context = 'AdminConfiguring';
?>
		
		<form name="configurator" id="configurator_form" enctype="multipart/form-data" method="post" action="?action=TIMEMACHINE">
		<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
		<tr bgcolor="#f3f3f3">
			<th align="left"><h4>Available backups</h4></th>
		</tr>
		<tr>
			<td>
				<table cellpadding="10" cellspacing="0" style="border: 2px solid #eee">
				<tr>
					<td><strong>DATA</strong></td>
					<td><strong>ORA</strong></td>
					<td><strong>AZIONE</strong></td>
					<td><strong>Descrizione</strong></td>
				</tr>
				<?php
				$App->getRestoreFile();
				foreach ($App->getBackups() as $Backup) {
					$BackupFile = $App->getModuleDir() . "/" . $Backup;
					$Backup = str_ireplace("BACKUP_", "", $Backup);
					$Backup = str_ireplace(".xml", "", $Backup);
					list($date, $time) = explode("_", $Backup);
					echo "<tr";
					if($App->isRestorationFile($BackupFile))
						echo ' class="restoredFromThis"';
					echo "><td>".$Utils->dbDateToItalian($date)."</td>";
					echo "<td>".date('H:i:s', $time)."</td>";
					echo '<td><a onclick="FF.Configurator.restoreBackup(this, \''.$BackupFile.'\');" style="cursor: pointer; color: blue">Ripristina</a> | <a href="javascript:FF.Configurator.showBackupFile(\''.$BackupFile.'\');" style="color: blue">Vedi</a> | '.((!$App->isRestorationFile($BackupFile)) ? '<a onclick="FF.Configurator.showDiff(this, \''.$BackupFile.'\')" style="color: blue; cursor: pointer;">Diff</a> |':'').' <a style="cursor: pointer; color: blue" onclick="if(confirm(\'Sicuro di volerlo cancellare?\')) FF.Configurator.deleteBackupFile(this, \''.$BackupFile.'\')">Cancella</a></td>';
					$BackupDom = $App->getXmlDom( $BackupFile );
					echo '<td>'.$BackupDom->query('/config/backup')->item(0)->nodeValue.'</td></tr>
					';
				}
				?>
				</table>
				<br />
				<br />
				<span style="background: #c1fa9f; padding: 0px 4px; margin-right: 10px">&nbsp; </span> Actual config.xml had restored from this file
			</td>
		</tr>
		</table>
		
		
		<br />
		</form>
	