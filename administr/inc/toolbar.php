<?php
global $App;
global $Table;

$pageName = explode("/", $_SERVER["PHP_SELF"]);
$pageName = $pageName[count($pageName)-1];
if ( is_file ( $App->Root . "inc/menu." . $pageName) )
	require_once ( $App->Root . "inc/menu." . $pageName );
else 
	echo '<div id="toolBar"><ul class="FirstLevel"><li><a>('.$pageName.')</a></li></ul><div class="clear"><br /></div></div>';
	
/*
	
	<div id="toolBar">
		<ul class="FirstLevel">
		<li><a href="#">File</a>
			<ul class="SecondLevel">
				
			<? if ($App->Context == 'Configuring') { ?>
				
				<li><a href="javascript:FF.Utils.saveTextareas();document.configurator.submit();"><?=$print['save']?></a></li>
			
			<? } else if ($App->Context == 'AdminConfiguring') { ?>
			
				<li><a href="javascript:FF.Utils.saveTextareas();document.configurator.submit();"><?=$print['save']?></a></li>
				<li><a href="javascript:window.location.reload();"><?=$print['reload']?></a></li>
			
			<? } else if ($App->Context == 'PluginManager') { ?>
				
				<li><a href="content.php?table=<?php echo $Table->name; ?>"><?=$print['close_plugin']?></a></li>
				
			<? } else { ?>
			
				<? if($App->Context == "ShowContent") { ?>
					<li><a href="#" onClick="window.location.href = 'edit.php?table=<?php echo $Table->name; ?>&action=INSERT'"><?=$print['new']?></a></li>
				<? } ?>
				<? if($App->Context == "RunningPlugin") { ?>
					<li><a href="content.php?table=<?php echo $Table->name; ?>"><?=$print['close_plugin']?></a></li>
				<? } ?>
				<? if($App->Context == "EditContent") { ?>
					<li><a href="javascript:FF.Utils.saveTextareas();FF.Contents.SAVE();"><?=$print['save']?></a></li>
					<li><a href="content.php?table=<?php echo $Table->name; ?>"><?=$print['back_to_list']?></a></li>
				<? } ?>
				<? if($App->Context == "InsertContent") { ?>
					<li><a href="javascript:FF.Contents.SAVE();">Salva</a></li>
					<li><a href="javascript:FF.Contents.SAVE_AND_REDO();"><?=$print['save_and_insert_new']?></a></li>
					<li><a href="content.php?table=<?php echo $Table->name; ?>"><?=$print['back_to_list']?></a></li>
				<? } ?>
				
			<? } ?>
			<li><hr /></li>
			<li><a href="#" onclick="window.parent.location = '?action=resetModule'"><?=$print['change_module']?></a></li>
			<li><hr /></li>
			<li><a href="#" onclick="window.parent.location = 'login.php?action=logout'"><?=$print['logout_exit']?></a></li>
			</ul>
		</li>
		
		<? if ($App->Context != "Configurator" && $App->Context != 'AdminConfiguring') { ?>
		
			<li><a href="#"><?=$print['contents']?></a>
				<ul class="SecondLevel">
				<li><a href="#" onClick="window.location.reload();"><?=$print['refresh_page']?></a></li>
				<li><a href="#" onClick="FF.Contents.refreshDatas();"><?=$print['refresh_datas']?></a></li>
				<?php if($App->Context != 'Intro') { ?> 
					
					<? if(strpos($_SERVER['PHP_SELF'], 'content.php') !== false) { ?>
						<li><a class="toolBarContent"><?=$print['datas_per_page']?> <input id="dataPerPageSetter" type="text" size="1" value="<?php echo $Table->dataperpage; ?>" /><input type="button" value="OK" onclick="FF.Configurator.setDataPerPage('<?php echo $Table->name; ?>', $F('dataPerPageSetter'));" /></a></li>
						<?php if($_SESSION['FF']['RowCompressedView'] != true) { ?>
							<li><a id="ToolbarRowCompressor" class="cursorPointer" onclick="FF.Contents.CompressRows(this);"><?=$print['compress_rows']?></a></li>
						<? } else { ?>
							<li><a id="ToolbarRowCompressor" class="cursorPointer" onclick="FF.Contents.DecompressRows(this);"><?=$print['expand_rows']?></a></li>
						<? } ?>
					<? } ?>
					<? if($User->Type > 1) { ?>
						<? if($_SESSION['FF'][$Table->name]['show'] != 'all') { ?>
							<li><a class="toolBarContent" href="javascript:void(FF.Contents.SHOW_ALL());"><?=$print['show_hidden_fields']?></a></li>
						<? } else { ?>
							<li><a class="toolBarContent" href="javascript:void(FF.Contents.SHOW_NORMAL());"><?=$print['hide_hidden_fields']?></a></li>
						<? } ?>
					<? } ?>
					<?php if(isset($_SESSION['FF']['FILTERS'][$Table->name])) { ?>
						<li><a class="toolBarContent cursorPointer" onclick="window.location.href = FF.Utils.getLocationAndAppend('action=deleteFilter', true);" style="color: red !important"><?=$print['clear_filter']?></a></li>
					<?php } ?>
					<li><hr /></li>
					<li><a href="#" onClick="FF.Contents.getExcelDump('<?=$Table->name?>');"><?=$print['export_table_excel']?></a></li>
					<li><a href="#" onClick="FF.Contents.getCSVDump('<?=$Table->name?>');"><?=$print['export_table_csv']?></a></li>
					<li><a href="#" onClick="FF.Contents.getSQLDump('<?=$Table->name?>');"><?=$print['export_table_sql']?></a></li>
					<?
					if(!isset($_GET['action'])) { 
						$plugins = $App->getTablePlugins($App->CurrentTable);
						if($plugins != false) {
							echo '<li><hr /></li>';
							foreach($plugins as $plugin) {
								echo '<li><a href="pluginManager.php?action=runPlugin&table='.$App->CurrentTable.'&name='.str_replace('"', "'", $plugin->getAttribute('name')).'&file='.$plugin->getAttribute('ref').'" class="toolbarPlugin">'.$plugin->getAttribute('name').'</a></li>';
							}
						}
					} 
					?>
					
				<?php } ?>
				</ul>
			</li>
			
		<? } ?>
		
		<? if($User->Type > 1) { ?>
		<li><a href="#">Settings</a>
			<ul class="SecondLevel">
			<li><a href="#" onclick="FF.Console.show('<?php echo $App->Root; ?>');"><?=$print['show_console']?></a></li>
			<?php if($App->Context != 'AdminConfiguring') { ?>
				<li><a href="conf/configurator.php?table=<?=$App->CurrentTable?>"><?=$print['configure']?></a></li>
			<? } ?>
			</ul>
		</li>
		<? } ?>
		</ul>
		<input type="hidden" id="tableName" value="<?php echo $App->CurrentTable; ?>">
		
		<?php if($App->Context != 'AdminConfiguring' && $App->Context != 'EditContent') { ?>
			<div id="FinderContainer">
				Finder: <input type="text" value="<?=$print['type_text_here']?>" id="FinderInput" />
			</div>
		<?php } ?>
		
		<div class="clear"><br /></div>
		
	</div>
	
	<script>
		FF.Toolbar.init();
		FF.Toolbar.Finder.init('FinderInput', '<?php echo $Table->name; ?>');
	</script>
<?php } */?>