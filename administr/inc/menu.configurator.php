<?php
global $App;
global $Table;
?>
<div id="toolBar">
	<ul class="FirstLevel">
		<li><a href="#">File</a>
			<ul class="SecondLevel">
				<li><a href="javascript:FF.Utils.saveTextareas();document.configurator.submit();"><?=$print['save']?></a></li>
				<li><hr /></li>
				<li><a href="#" onclick="window.parent.location = '?action=resetModule'"><?=$print['change_module']?></a></li>
				<li><hr /></li>
				<li><a href="#" onclick="window.parent.location = 'login.php?action=logout'"><?=$print['logout_exit']?></a></li>
			</ul>
		</li>
		<li><a href="#"><?=$print['contents']?></a>
			<ul class="SecondLevel">
				<li><a href="#" onClick="window.location.reload();"><?=$print['refresh_page']?></a></li>
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


