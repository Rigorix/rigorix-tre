<?php
require_once ("../inc/Engine.php");
$App->Context = 'AdminConfiguring';

if(isset($_GET['tab'])) 
	$tab = $_GET['tab'];
else
	$tab = 1;
?>
		
		<script type="text/javascript">
			FF.UI.addContentTab({
				active	: <?php echo ($tab == 1) ? 'true' : 'false'; ?>, 
				label	: 'General infos',
				fn		: function() {
					$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
					new Ajax.Request('conf.general_preferences.php?tab=1', {
						onComplete: function(res){
							$('contentWrapper').update(res.responseText);
							eval(res.responseText.extractScripts());
						}
					});
				}
			});
			FF.UI.addContentTab({
				active	: <?php echo ($tab == 2) ? 'true' : 'false'; ?>, 
				label	: 'Resources path',
				fn		: function() {
					$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
					new Ajax.Request('conf.general_preferences.php?tab=2', {
						onComplete: function(res){
							$('contentWrapper').update(res.responseText);
							eval(res.responseText.extractScripts());
						}
					});
				}
			});
			FF.UI.addContentTab({
				active	: <?php echo ($tab == 3) ? 'true' : 'false'; ?>, 
				label	: 'Connections',
				fn		: function() {
					$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
					new Ajax.Request('conf.general_preferences.php?tab=3', {
						onComplete: function(res){
							$('contentWrapper').update(res.responseText);
							eval(res.responseText.extractScripts());
						}
					});
				}
			});
		</script>
		
		<form name="configurator" id="configurator_form" enctype="multipart/form-data" method="post" action="adm_configurator.php?action=SAVING_PREFERENCES&tab=<?=$tab?>&title=<?php echo $App->ConfigObj['title']; ?>">
		<?php if($tab == 1)	{ ?>
			
			
			<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
			<tr bgcolor="#cccccc"><th colspan="4" align="left"><h4>General infos</h4></th></tr>
			<tr>
				<td width="70"><strong>Title</strong></td><td width="40%"><input type="text" name="title" value="<?php echo $App->ConfigObj['title']; ?>"></td>
				<td width="100"><strong>Subtitle</strong></td><td><input type="text" name="subtitle" value="<?php echo $App->ConfigObj['subtitle']; ?>"></td>
			</tr>
			<tr>
				<td><strong>Logo</strong></td><td>
					<? if($App->ConfigObj['logo'] != null || $App->ConfigObj['logo'] != "") { ?>
						<img src="../i/<?php echo $App->ConfigObj['logo']; ?>" height="70" align="left" /><div style="float: left"><strong> <?php echo $App->ConfigObj['logo']; ?></strong><br />
					<? } ?>
					<input type="hidden" name="logo" value="<?php echo $App->ConfigObj['logo'];?>">
					<input type="file" name="logo_load" />
					</div>
					<br clear="all" />
				</td>
				<td><strong>Version</strong></td><td><input type="text" name="version" value="<?php echo $App->ConfigObj['version'];?>"></td>
			</tr>
			<tr>
				<td><strong>Language</strong></td><td width="40%"><select name="adminlanguage">
					<option value="--">--</option>
					<?php
					$langs = $App->getAvailableLanguages();
					foreach ($langs as $lang) {
						if($lang != '') {
							$langName = explode('.', $lang);
							echo '<option value="'.$lang.'" '.(($App->ConfigObj['adminlanguage'] == $lang) ? 'selected="selected"' : '').'>'.strToUpper($langName[0]).'</option>';
						}
					}
					?>
				</select></td>
			</tr>
			</table>
			
			<br />
			
		<?php } else if($tab == 2) { ?>
			
			<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
			<tr bgcolor="#cccccc"><th colspan="4" align="left"><h4>Resources path</h4></th></tr>
			<tr>
				<td width="100"><strong>Admin path</strong></td><td><input style="width: 65% !important" type="text" id="field_adminpath" name="adminpath" value="<?php echo $App->ConfigObj['adminpath'];?>"> <a class="cursorPointer" onclick="FF.Contents.getDirectoryUrl('field_adminpath', '../')"><img src="../i/terminal.gif" /></a>
				<?php
				// Controllo che la directory esista
				if(is_dir($_SERVER['DOCUMENT_ROOT'] . $App->ConfigObj['adminpath']))
					echo "<span style=color:green;white-spaces:nowrap>Exists!</span>";
				else 
					echo "<span style=color:red>Not found!</span>";
				?>
				</td>
				<td width="100" nowrap="nowrap"><strong>Admin loading path</strong></td><td><input style="width: 65% !important" type="text" id="field_adminloadingpath" name="adminloadingpath" value="<?php echo $App->ConfigObj['adminloadingpath'];?>"> <a class="cursorPointer" onclick="FF.Contents.getDirectoryUrl('field_adminloadingpath')"><img src="../i/terminal.gif" /></a>
				<?php
				// Controllo che la directory esista
				if(is_dir($_SERVER['DOCUMENT_ROOT'] . $App->ConfigObj['adminloadingpath']))
					echo "<span style=color:green;white-spaces:nowrap>Exists!</span>";
				else 
					echo "<span style=color:red>Not found!</span>";
				?>
				</td>
			</tr>
			<tr>
				<td><strong>Admin mail</strong></td><td><input type="text" name="adminmail" value="<?php echo $App->ConfigObj['adminmail'];?>"></td>
			</tr>
			</table>
			
		<?php } else if($tab == 3) { ?>
		
			<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator" id="ConnectionTable">
			<tr bgcolor="#cccccc"><th colspan="4" align="left"><h4>Connections</h4></th></tr>
			<tr>
				<td colspan="99"><h2 class="subtitle_blue">Database</h2></td>
			</tr>
			<tr>
			<?php
			$hosts = $App->query('/config/database')->item(0)->childNodes;
			$ind = 0;
			foreach ($hosts as $host) {
				if($host->nodeName != '#text') { 
					$ind++;?>
					<td>
					<table width="250" class="connectionTable">
					<tr>
						<td colspan="2"><h2><?php echo $host->nodeName; ?></h2></td>
					</tr>
					<tr>
						<td><strong>Database host: </strong></td>
						<td><input type="text" name="host<?=$ind?>" value="<?php echo $host->getElementsByTagName('host')->item(0)->nodeValue; ?>" /></td>
					</tr>
					<tr>
						<td><strong>Database name: </strong></td>
						<td><input type="text" name="name<?=$ind?>" value="<?php echo $host->getElementsByTagName('name')->item(0)->nodeValue; ?>" /></td>
					</tr>
					<tr>
						<td><strong>Database user: </strong></td>
						<td><input type="text" name="user<?=$ind?>" value="<?php echo $host->getElementsByTagName('user')->item(0)->nodeValue; ?>" /></td>
					</tr>
					<tr>
						<td nowrap="nowrap"><strong>Database password: </strong></td>
						<td><input type="text" name="pwd<?=$ind?>" value="<?php echo $host->getElementsByTagName('pwd')->item(0)->nodeValue; ?>" /></td>
					</tr>
					</table>
					<div align="right"><a class="cursorPointer normal" onclick="FF.Configurator.removeDatabaseConnection(this, '<?=$ind?>');">&raquo; Cancella</a></div>
					</td>
				<?php }
			}
			?>
			<td width="99%"><input type="hidden" name="tot_connections" value="<?=$ind?>" /></td>
			</tr>
			<tr>
				<td>
					<a href="javascript:FF.Configurator.addNewConnection();" class="normal">&raquo; Add Database connection</a>
				</td>
			</tr>
			</table>
			
		<?php } ?>
		
		</form>
	