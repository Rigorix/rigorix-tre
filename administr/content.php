<?php
require_once ("inc/Engine.php");
$App->Context = 'ShowContent';
$Table = new Table($App->CurrentTable);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>@import 'css/common.css';</style>
	<style>@import 'css/redmond/jquery-ui-1.8.13.custom.css';</style>
	<!-- style>@import 'css/wysiwyg.css';</style -->
	<?php echo $App->setGlobalJsVars(); ?>
	<script type="text/javascript" src="js/libs/prototype.js"></script>
	<script type="text/javascript" src="js/libs/scriptaculous.js"></script>
	<script type="text/javascript" src="js/libs/jquery-1.5.1.min.js"></script>
	<script>
		var jq = jQuery.noConflict();
	</script>
	<script type="text/javascript" src="js/libs/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="js/FF.js"></script>
	<script type="text/javascript" src="js/FF.UI.js"></script>
	<script type="text/javascript" src="js/FF.EventManager.js"></script>
	<script type="text/javascript" src="js/FF.Toolbar.js"></script>
	<script type="text/javascript" src="js/FF.Contents.js"></script>
	<script type="text/javascript" src="js/FF.Utils.js"></script>
	<script type="text/javascript" src="js/FF.Console.js"></script>
	<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>
	<script> bkLib.onDomLoaded(function() { 
		nicEditors.allTextAreas();
	});</script>
	<?php echo $App->setGlobalAppVars(); ?>
</head>
<body id="contents">
<script>FF.Contents.onStartLoading();</script>
<div id="contentsContent">
	
	<div id="contentHeader">
		
		<?php require_once ($App->inc_dir . "/toolbar.php"); ?>
		<?php require_once ($App->inc_dir . "/tabs.php"); ?>
		
	</div>
	
	<div id="contentWrapper">
		
		<div id="tabContent" class="off"></div>
		<?php if($_REQUEST['showMessage']) echo '<div id="messagePanel">' . $print[$_REQUEST['showMessage']] . '</div>'; ?>
		
		<div class="content-table-outer">
			<div id="content-table-inner" class="content-table-inner">
				<table cellpadding="0" cellspacing="0" class="contentsTable" id="contentsTable">
				<thead id="tableHeader">
				<tr class="header" id="tableHeaderComponent">
					<th><div class="datasBox" flex="0"><!--a href="#" onclick="FF.Contents.checkAllRows(this);" class="btnCheckAll">All</a --></div></th>
					<?php
					/* ########## Stampo gli headers della tabella ########## */
					foreach($Table->getFields() as $Name => $Type) {
						$Field = $Table->getFieldConfig($Name);
						$FieldAttr = $Table->getFieldAttributes($Field);
						if($Table->isFieldVisible($Name)) { ?>
							<th <?php echo (($_SESSION['FF'][$Table->name]['orderField'] == $Name)?' class="ordering"':''); ?>>
								<div class="datasBox">
								<a href="content.php?orderfield=<?php echo $Name; ?>&table=<?php echo $Table->name; ?>" class="btnOrder"><?php 
									echo $FieldAttr['title'] ? $FieldAttr['title'] : $Name;
									if($_SESSION['FF'][$Table->name]['orderfield'] == $Name) {
										if($_SESSION['FF'][$Table->name]['orderdir'] == "ASC") 
											echo '<img src="i/asc.gif" />';
										else 
											echo ' <img src="i/dec.gif" />';	
									}
								?></a>
								<?php
								if($FieldAttr['type'] == "filesystem") {
									// E' un file.
									if($Table->getSetting("showImage_" . $Name) == "show") {
										echo '<a href="content.php?showImage_'.$Name.'=hide"><img src="'.$App->Root.'i/hide.gif" border="0" hspace="3" /></a>';
									} else {
										echo '<a href="#" onclick="FF.Contents.showImagesThumb(\''.$Name.'\', \''.$Table->loadPath.'\', 40, this);"><img src="'.$App->Root.'i/view.gif" border="0" hspace="3" /></a>';
									}
								}
								?>
								</div>
							</th>
							
						<?php } 
					}
					?>
				</thead>
				<tbody id="ContentDataTBody">
					<?php
					$Datas = $Table->getDatas();
					$i=0;
					foreach ($Datas as $Data) {
						$firstField = true;
		
						/* Ciclo tutti i campi */
						foreach($Table->getFields() as $Name => $Type) {
							$_FIELD = $Table->getFieldConfig($Name);
							if($firstField === true) {
								$idValue = $Data->$Name;
								?>
								<tr id="row_<?php echo $Data->$Name; ?>" title="<?=$idValue?>,<?=$Name?>" class="<?=(($i%2==0)?"row1":"row2")?>">
								<td><div class="datasBox" flex="0"><input style="visibility: hidden" class="rowSelector" type="checkbox" onClick="FF.Contents.checkRow(this, this.checked);"><input id="idField" type="hidden" value="<?php echo $Name; ?>"></div></td>
								<!--td width="15"><a href="#" class="roundButton" onClick="window.location='edit.php?action=EDIT&table=<?php echo $Table->name; ?>&editField=<?php echo $Name; ?>&editId=<?php echo $idValue; ?>'">Edit</a></td>
								<td width="15"><a href="#" class="roundButton" onClick="FF.Contents.deleteRow('<?php echo $idValue; ?>', '<?php echo $Name; ?>', '<?=$Table->name?>');">Del</a></td -->
								<?
								$firstField = false;
							} 
							if($Table->isFieldVisible($Name)) {
								// Faccio vedere il dato
								$Table->printFieldData($idValue, $Name, $Data);
							}
						}
						echo "</tr>";
						$i++;
					}
					?>
				</tbody>
				</table>
				<script>
					
				</script>
			</div>
		</div>
		
	</div>
	
</div>
<script>
	FF.UI.setRowsProperies();
	FF.Contents.addTooltips();
	FF.Contents.onLoadingComplete();
</script>
<?php $App->checkJsMessages(); ?>
</body>
</html>
