<?PHP
require_once ("inc/Engine.php");
$App->Context = 'FastEditing';
$Table = new Table($App->CurrentTable);
$Field = $Table->getFieldConfig($_REQUEST['field']);
$Data = $Table->getEditData();
?>
<html>
<head>
	<title>Config crossfields</title>
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
</head>
<body>

	<div id="popupContent">
		<form id="fastEditForm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="field" value="<?=$_REQUEST['field']?>">
		<input type="hidden" name="table" value="<?=$_REQUEST['table']?>">
		<input type="hidden" name="idField" value="<?=$_REQUEST['idField']?>">
		<input type="hidden" name="idValue" value="<?=$_REQUEST['id']?>">
		<table cellspacing="0">
		<tr><td colspan="4"><p>Tabella: <strong><?php echo $Table->title; ?></strong> | Campo: <strong><?php echo $Field['attributes']['title']; ?></strong></p></td></tr>
		<tr><td colspan="4"><br />
		<?php
		
		// Stampo il campo
		$Table->printFieldInput($Field['attributes']['name'], $Data[$Field['attributes']['name']]);
		
		?>
		</td></tr>
		</table>
		
		<div id="popupButtonRow">
			<input id="fastSaveButton" type="button" name="invia" value="SAVE" onClick="FF.Contents.saveFastEdit(this, $('fastEditForm').serialize(), true);"> <!-- input type="button" value="REFRESH" onClick="window.location = window.location.href" -->
		</div>
		
		</form>
		
	</div>
	
	<script type="text/javascript">
		FF.EventManager.addEscAction(function() {
			parent.window._GLOBAL['fast_edit_popup'].close();
			_GLOBAL['fast_edit_popup'] = null;
		});
	</script>
	
</body>
</html>
