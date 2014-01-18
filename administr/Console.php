<?php
require_once ("inc/Engine.php");
$App->Context = 'Console';
require_once ("inc/class.mysqldumper.php");
?>
<html>
<head>
	<title>Config crossfields</title>
	<style type="text/css">@import 'css/common.css';</style>
	<?php echo $App->setGlobalJsVars(); ?>
	<script type="text/javascript" src="js/libs/prototype.js"></script>
	<script type="text/javascript" src="js/libs/scriptaculous.js"></script>
	<script type="text/javascript" src="js/FF.js"></script>
	<script type="text/javascript" src="js/FF.UI.js"></script>
	<script type="text/javascript" src="js/FF.EventManager.js"></script>
	<script type="text/javascript" src="js/FF.Utils.js"></script>
	<script type="text/javascript" src="js/FF.Console.js"></script>
</head>
<body style="margin: 0; height: 100%">
<? if($_GET['action'] == 'save') {
	
	if(stripos($_GET['file'], $_SERVER['DOCUMENT_ROOT']) > -1) {
		
		$_POST = $Utils->removeMagicQuotes($_POST);
		$file = str_replace($_SERVER['DOCUMENT_ROOT'], "", $_GET['file']);
		echo $file;
		$fc = fopen($_GET['file'], 'w');
		if(fwrite($fc, $_POST['code'])) 
			echo '<script>setTimeout(function(){parent.document.getElementById("_EditorActions").SaveDone();}, 500);</script>';
		else
			echo '<script>setTimeout(function(){parent.document.getElementById("_EditorActions").SaveError("Directory is write protected");}, 500);</script>';
		fclose($fp);
		
		
	} else {
		echo '<script>setTimeout(function(){parent.document.getElementById("_EditorActions").SaveError("Out of Document root");}, 1000);</script>';
	}
	
} else { ?>
	
	
	<? if($User->Type > 1) { ?>
	
		<div id="Console" class="popup">
			<div class="ConsoleContainer">
				<div id="consoleShower"></div>
				<input type="text" value=">" id="consoleInput">
			</div>
		</div>
		<div style="position: absolute; left: -1000px; top: -500; width: 10px; height: 10px">
			<iframe name="ConsoleActionFrame"></iframe>
		</div>
		<script>
			FF.Console.Console = $('Console');
			FF.Console.ViewType = 'popup';
			FF.Console.addMessage("Console Ready!");
			$('consoleInput').focus();
			$('consoleInput').observe('keydown', FF.Console.manageKey);
			FF.EventManager.addEscAction(function() {
				FF.Console.resetCursor();
			});
			if(Dashboard.consoleConfig) {
				// Riporto le configurazioni della console precedente
				FF.Console.returnField = Dashboard.consoleConfig.returnField
			}
		</script>
		
	<?php } else { ?>
		
		You don't have the permission to use the console!
		
	<?php } ?>
	
	
<? } ?>
</body>
</html>