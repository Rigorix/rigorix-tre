<?php
global $App;
global $Table;
?>
<div id="toolBar">
	<ul class="FirstLevel">
		<li><a href="#">File</a>
			<ul class="SecondLevel">
				<li><a href="#" onclick="window.parent.location = '?action=resetModule'"><?=$print['change_module']?></a></li>
				<li><hr /></li>
				<li><a href="#" onclick="window.parent.location = 'login.php?action=logout'"><?=$print['logout_exit']?></a></li>
			</ul>
		</li>
		<? if($User->Type > 1) { ?>
		<li><a href="#">Settings</a>
			<ul class="SecondLevel">
			<!--li><a href="#" onclick="FF.Console.show('<?php echo $App->Root; ?>');"><?=$print['show_console']?></a></li -->
			<?php if($App->Context != 'AdminConfiguring') { ?>
				<li><a href="conf/configurator.php?table=<?=$App->CurrentTable?>"><?=$print['configure']?></a></li>
			<? } ?>
			</ul>
		</li>
		<? } ?>
	</ul>
	<div class="clear"><br /></div>
</div>

<script>
	FF.Toolbar.init();
	FF.Toolbar.Finder.init('FinderInput', '<?php echo $Table->name; ?>');
</script>


