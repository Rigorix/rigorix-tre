<?php
require_once ("../inc/Engine.php");
$App->Context = 'ModuleCreation';
if(!isset($_SESSION['FF']['newmodule']))
	$_SESSION['FF']['newmodule'] = array();
if($_GET['action'] == 'edit' || $_SESSION['GET']['action'] == 'edit') {
	$createTab 	= 'false';
	$editTab	= 'true';
} else {
	$createTab 	= 'true';
	$editTab	= 'false';
}

?>
		
		<script type="text/javascript">
			FF.UI.addContentTab({
				active	: <?php echo $createTab ?>, 
				label	: 'Create',
				fn		: function() {
					$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
					new Ajax.Request('conf.modules.php?action=create', {
						onComplete: function(res){
							$('contentWrapper').update(res.responseText);
							eval(res.responseText.extractScripts());
						}
					});
				}
			});
			FF.UI.addContentTab({
				active	: <?php echo $editTab ?>, 
				label	: 'Modify',
				fn		: function() {
					$('contentWrapper').update("<div style=padding:10px>Loading...</div>");
					new Ajax.Request('conf.modules.php?action=edit', {
						onComplete: function(res){
							$('contentWrapper').update(res.responseText);
							eval(res.responseText.extractScripts());
						}
					});
				}
			});
		</script>
		
		<?php if($_GET['action'] == 'edit' || $_SESSION['GET']['action'] == 'edit') { ?>
		
		
			<!-- EDIT EXISTING MODULES -->
			<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
			<tr bgcolor="#cccccc"><th colspan="4" align="left"><h4>Modify existing modules</h4></th></tr>
			</table>
			
			<table cellspacing="1" cellpadding="9" class="adm_configurator">
				<tr valign="top">
					<td>
					<h2 class="subtitle_blue">Existing modules:</h2>
					<br />
					<select id="ModuleEditingSelect" multiple="multiple" onclick="window.location.href='?act=modules&action=edit&editing='+this.options[this.selectedIndex].value;">
					<?php
					$modules = $User->getUserModules();
					foreach($modules as $module) {
						if($_SESSION['GET']['editing'] == $module['attributes']['path'])
							$selected = ' selected';
						echo '<option'.$selected.' value="' . $module['attributes']['path'] . '">' . $module['attributes']['name'] . '</option>';
						echo '<a href="#" class="normal black">&raquo; ' . $module['attributes']['name'] . '</a><br />';
						$selected = '';
					}
					?>
					</select>
					</td>
					<?php if(isset($_SESSION['GET']['editing'])) { ?>
						
						<td>
						<h2 class="subtitle_blue">Actions:</h2>
						<br />
						<div id="ModuleEditingCell">
						<input type="button" value="Remove" onclick="FF.Configurator.removeModule('<?php echo $_SESSION['GET']['editing']; ?>');" /> &nbsp; <input type="button" value="Edit" />
						</div>
						</td>
						
					<?php }	?>
				</tr>
			</table>			
			
		
		<?php } else { ?>
			
			
			<!-- CREATE NEW MODULES -->
			
			<table width="100%" cellspacing="1" cellpadding="9" class="adm_configurator">
			<tr bgcolor="#cccccc"><th colspan="4" align="left"><h4>Create new module</h4></th></tr>
			</table>
			
			<?php if($_SESSION['FF']['newmodulestatus'] == 'DONE') { ?>
				
				<table cellspacing="1" cellpadding="9" class="adm_configurator">
				<tr valign="top">
					<td>
					<br /><br /><h2 class="subtitle_blue">Modulo creato correttamente!!!</h2>
					</td>
				</tr>
				</table>
				<?php unset($_SESSION['FF']['newmodulestatus']); ?>
				
			<?php } else { ?>
			
				<table cellspacing="1" cellpadding="9" class="adm_configurator">
				<tr valign="top">
					<td>
						<h2 style="font-weight: normal; color: blue">Insert module setup</h2>
						<br />
						<strong>Module dir</strong><br />
						<input type="text" id="module_dir" name="module_dir" value="<?php echo $_SESSION['FF']['newmodule']['newModuleDir']; ?>"><br /><br />
						<strong>Module name</strong><br />
						<input type="text" id="module_name" name="module_name" value="<?php echo $_SESSION['FF']['newmodule']['newModuleName']; ?>"><br /><br />
					</td>
					<td valign="top">
						<br /><br /><a class="cursorPointer next_blue" onclick="FF.Configurator.createModule($F('module_name'), $F('module_dir'), this);">&raquo;</a>
					</td>
					<?php if(isset($_SESSION['FF']['newmodule']['newModuleName']) && isset($_SESSION['FF']['newmodule']['newModuleDir'])) { ?>
						<td>
							<h2 class="subtitle_blue">Admin main properties</h2>
							<br />
							<form id="properties">
							<strong>Title</strong><br />
							<input type="text" name="module_title" value="<?php echo $_SESSION['FF']['newmodule']['module_title']; ?>"><br /><br />
							<strong>Subtitle</strong><br />
							<input type="text" name="module_subtitle" value="<?php echo $_SESSION['FF']['newmodule']['module_subtitle']; ?>"><br /><br />
							<strong>Version</strong><br />
							<input type="text" name="module_version" value="<?php echo $_SESSION['FF']['newmodule']['module_version']; ?>"><br /><br />
							<strong>Path to admin</strong><br />
							<input type="text" name="module_adminpath" value="<?php echo $_SESSION['FF']['newmodule']['module_adminpath']; ?>"><br /><br />
							<strong>Loading dir</strong><br />
							<input type="text" name="module_loadingdir" value="<?php echo $_SESSION['FF']['newmodule']['module_loadingdir']; ?>"><br /><br />
							<strong>Admin email</strong><br />
							<input type="text" name="module_email" value="<?php echo $_SESSION['FF']['newmodule']['module_email']; ?>"><br /><br />
							<input type="hidden" name="module_creation_done" value="true" />
							</form>
						</td>
						<td valign="top">
							<br /><br /><a class="cursorPointer next_blue" onclick="FF.Configurator.setModuleProps($('properties').serialize());">&raquo;</a>
						</td>
					<?php } ?>
					<?php if(isset($_SESSION['FF']['newmodulestatus'])) { ?>
					<td valign="top">
						<br /><br /><br /><input type="button" value="Create!" onclick="window.location.href='?act=modules&do=CREATE'" style="font-size: 30px" />
						<br />
						<span style="color: red"><?=$_SESSION['FF']['newmodulestatus'];?></span>
						<? unset($_SESSION['FF']['newmodulestatus']); ?>
					</td>
					<?php } ?>
				</tr>
				</table>
			
			<?php } ?>
			<br />
			
			
		<?php } ?>