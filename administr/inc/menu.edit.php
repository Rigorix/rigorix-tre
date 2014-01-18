<?php
global $App;
global $Table;
?>
<div id="toolBar">
	<ul class="FirstLevel">
		<li><a href="#">File</a>
			<ul class="SecondLevel">
				<? if($App->Context == "EditContent") { ?>
					<li><a href="javascript:FF.Utils.saveTextareas();FF.Contents.SAVE();"><?=$print['save']?></a></li>
					<li><a href="content.php?table=<?php echo $Table->name; ?>"><?=$print['back_to_list']?></a></li>
				<? } ?>
				<? if($App->Context == "InsertContent") { ?>
					<li><a href="javascript:FF.Contents.SAVE();">Salva</a></li>
					<li><a href="javascript:FF.Contents.SAVE_AND_REDO();"><?=$print['save_and_insert_new']?></a></li>
					<li><a href="content.php?table=<?php echo $Table->name; ?>"><?=$print['back_to_list']?></a></li>
				<? } ?>
				<li><hr /></li>
				<li><a href="#" onclick="window.parent.location = '?action=resetModule'"><?=$print['change_module']?></a></li>
				<li><hr /></li>
				<li><a href="#" onclick="window.parent.location = 'login.php?action=logout'"><?=$print['logout_exit']?></a></li>
			</ul>
		</li>
		<li><a href="#"><?=$print['contents']?></a>
			<ul class="SecondLevel">
				<li><a href="#" onClick="window.location.reload();"><?=$print['refresh_page']?></a></li>
				<?
				$plugins = $App->getTablePlugins($App->CurrentTable);
				if($plugins != false) {
					echo '<li><hr /></li>';
					foreach($plugins as $plugin) {
						echo '<li><a href="pluginManager.php?action=runPlugin&table='.$App->CurrentTable.'&name='.str_replace('"', "'", $plugin->getAttribute('name')).'&file='.$plugin->getAttribute('ref').'" class="toolbarPlugin">'.$plugin->getAttribute('name').'</a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php $App->print_settings_menu (); ?>
	</ul>
	<input type="hidden" id="tableName" value="<?php echo $App->CurrentTable; ?>">
	<div class="clear"><br /></div>
</div>

<script>
	FF.Toolbar.init();
	FF.Toolbar.Finder.init('FinderInput', '<?php echo $Table->name; ?>');
</script>


