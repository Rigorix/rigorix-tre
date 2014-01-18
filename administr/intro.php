<?php
require_once ("inc/Engine.php");
$App->Context = 'Intro';
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>FF Intro</title>
	<style>@import 'css/common.css';</style>
	<?php echo $App->setGlobalJsVars(); ?>
	<script type="text/javascript" src="js/FF.language.php"></script>
	<script type="text/javascript" src="js/libs/prototype.js"></script>
	<script type="text/javascript" src="js/libs/scriptaculous.js"></script>
	<script type="text/javascript" src="js/FF.js"></script>
	<script type="text/javascript" src="js/FF.Utils.js"></script>
	<script type="text/javascript" src="js/FF.UI.js"></script>
	<script type="text/javascript" src="js/FF.EventManager.js"></script>
	<script type="text/javascript" src="js/FF.Toolbar.js"></script>
	<script type="text/javascript" src="js/FF.Contents.js"></script>
	<script type="text/javascript" src="js/FF.Console.js"></script>
</head>
<body id="contents">
<script>FF.Contents.onStartLoading();</script>
<div id="contentsContent">

	<div id="contentHeader">
		
		<?php require_once ($App->inc_dir . "/toolbar.php"); ?>
		<h1><?=$print['wellcome_screen']?></h1>
		
	</div>
	
	<div id="contentWrapper">
		<div class="textContent">
			<h4>Suggerimenti all'uso di FF admin</h4>
			<br />
			<table cellpadding="8">
				<tr>
					<th>Shortcuts</th>
				</tr>
				<tr>
					<td>
						<ul class="numeric">
							<li>Assicurati di avere la <tips msg="La finestra centrale &#65533; quella dove risiedono tutti i contenuti tranne il men&#65533; a sinistra e la fascia in alto">finestra centrale</tips> attiva (cliccandola)</li>
							<li>Premi, e tieni premuto per un secondo, il tasto "f" (prova adesso)</li>
							<li>Continuando a tener premuto f, premi simultaneamente la lettera della lista che &#65533; apparsa per eseguire quello "shortcut".</li>
							<li><strong>VELOCE E FACILE!</strong></li>
						</ul>
						<br />
						<strong>Lista dei comandi</strong><br />
						<table cellpadding="6">
						<tr><td bgcolor="#ffffff"><strong>C</strong></td><td>Apre la console (se amministratore)</td></tr>
						<tr><td bgcolor="#ffffff"><strong>D</strong></td><td>Ricarica tutti i dati della pagina</td></tr>
						<tr><td bgcolor="#ffffff"><strong>G</strong></td><td>Sposta il focus sulla ricerca</td></tr>
						<tr><td bgcolor="#ffffff"><strong>M</strong></td><td>Massimizza / Minimizza la visualizzazione</td></tr>
						<tr><td bgcolor="#ffffff"><strong>R</strong></td><td>Ricarica la pagina intera</td></tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
</div>
<script>
	FF.Contents.onLoadingComplete();
</script>
<?php $App->checkJsMessages(); ?>
</body>
</html>
