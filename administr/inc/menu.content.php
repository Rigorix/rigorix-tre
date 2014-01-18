<?php
global $App;
global $Table;
?>
<div id="toolBar">
	<ul class="FirstLevel">
		<li><a href="#">File</a>
			<ul class="SecondLevel">
				<li><a href="#" onClick="window.location.href = 'edit.php?table=<?php echo $Table->name; ?>&action=INSERT'"><?=$print['new']?></a></li>
				<li><a href="#" onclick="window.parent.location = '?action=resetModule'"><?=$print['change_module']?></a></li>
				<li><hr /></li>
				<li><a href="#" onclick="window.parent.location = 'login.php?action=logout'"><?=$print['logout_exit']?></a></li>
			</ul>
		</li>
		<li><a href="#"><?=$print['contents']?></a>
			<ul class="SecondLevel">
				<li><a href="#" onClick="window.location.reload();"><?=$print['refresh_page']?></a></li>
				<li><a href="#" onClick="FF.Contents.refreshDatas();"><?=$print['refresh_datas']?></a></li>
				<li><a class="toolBarContent"><?=$print['datas_per_page']?> <input id="dataPerPageSetter" type="text" size="1" value="<?php echo $Table->dataperpage; ?>" /><input type="button" value="OK" onclick="FF.Configurator.setDataPerPage('<?php echo $Table->name; ?>', $F('dataPerPageSetter'));" /></a></li>
				<?php if($_SESSION['FF']['RowCompressedView'] != true) { ?>
					<li><a id="ToolbarRowCompressor" class="cursorPointer" onclick="FF.Contents.CompressRows(this);"><?=$print['compress_rows']?></a></li>
				<? } else { ?>
					<li><a id="ToolbarRowCompressor" class="cursorPointer" onclick="FF.Contents.DecompressRows(this);"><?=$print['expand_rows']?></a></li>
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
			</ul>
		</li>
		<?php $App->print_settings_menu (); ?>
	</ul>
	<input type="hidden" id="tableName" value="<?php echo $App->CurrentTable; ?>">
		
	<div id="FinderContainer">
		Finder: <input type="text" value="<?=$print['type_text_here']?>" id="FinderInput" />
	</div>
	<div class="clear"><br /></div>
</div>

<script>
	FF.Toolbar.init();
	FF.Toolbar.Finder.init('FinderInput', '<?php echo $Table->name; ?>');
</script>


