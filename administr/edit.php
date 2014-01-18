<?php
require_once ("inc/Engine.php");
$App->Context = 'EditContent';
$Table = new Table($App->CurrentTable);
if($_REQUEST['action'] == 'EDIT') 
	$Data = $Table->getEditData();
else {
	$Data = null;
	$App->Context = 'InsertContent';
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Edit Content</title>
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
<div id="contentsContent">

	<div id="contentHeader">
		
		<?php require_once ($App->inc_dir . "/toolbar.php"); ?>
		<h1><?php echo $Table->title; ?>  <span class="description"><?php if($_SESSION['FF'][$Table->name]['show'] == 'all') echo '(Table name: '.$Table->name.')'; ?><br /></span></h1>
		<div class="clear" id="tabContainerClearer"><br /></div>
		
	</div>
	
	<div id="contentWrapper">
		<form action="dataProcessor.php" enctype="multipart/form-data" method="post" name="editForm" id="editForm">
		<input type="hidden" name="processACTION" value="<?php echo $_REQUEST['action']; ?>" />
		<input type="hidden" name="processID" value="<?php echo $_REQUEST['editId']; ?>" />
		<input type="hidden" name="processIDFIELD" value="<?php echo $_REQUEST['editField']; ?>" />
		<input type="hidden" name="processTABLE" value="<?php echo $Table->name?>" />
		
		<table cellpadding="4" cellspacing="0" width="100%" class="editContentsTable" id="EditContentTable">
		<thead>
			<tr><th width="1%"><a>Campo</a></th><th><a>Valore</a></th></tr>
		</thead>
		<tbody>
			<?php
			$i = 0;
			$Fields = $Table->getFields();
			foreach($Fields as $name => $type) {
				// Scrivo il dato
				
				if($Table->isFieldVisible($name)) {
					
					echo '<tr valign="top" bgcolor="' . (($i%2==0) ? '#f3f3f3' : '#ffffff') . '">';
					
					// Faccio vedere il dato
					$Field = $Table->getFieldConfig($name);
					echo '<td class="nowrap"><strong>' . $Field['attributes']['title'] . '</strong></td>';
					echo '<td>';
					
					// Controllo il dato e inserisco il campo form appropriato
					if(($Data[$name] == null || $Data[$name] == '') && $App->Context == "InsertContent") 
						$Data[$name] = $Table->getFieldDefault($name);
					$Table->printFieldInput($Field, $Data[$name]);
					
					echo "</td>";
					echo '</tr>
					<tr><td class="rowSep" colspan="' . $Table->getNumFields() . '"></td></tr>';
					$i++;
				}
				
			}
			
			?>
		</tbody>
		</table>

	</div>
	</form>	

</div>
</body>
</html>
