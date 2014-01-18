<?php
require_once ("../inc/Engine.php");
$App->Context = 'AdminConfiguring';
$_SESSION['POST'] 	= $_POST;
$_SESSION['GET'] 	= $_GET;
if(!isset($_REQUEST['act']))
	$_REQUEST['act'] = 'general_preferences';
/*
 * Salvo le preferenze
 */

if($_REQUEST['action'] == 'SAVING_PREFERENCES') {

	if($App->requestIsValid('UpdateAdminPrefs')) {
		//$App->doBackup();

		if($_REQUEST['tab'] == 1) {
			// Salvo le "General infos"
			$App->query('.//title', $App->DOMObj)->item(0)->nodeValue			= $_REQUEST['title'];
			$App->query('.//subtitle', $App->DOMObj)->item(0)->nodeValue		= $_REQUEST['subtitle'];
			$App->query('.//version', $App->DOMObj)->item(0)->nodeValue			= $_REQUEST['version'];
			$App->query('.//adminlanguage', $App->DOMObj)->item(0)->nodeValue	= $_REQUEST['adminlanguage'];

			if(isset($_FILES['logo_load']['name'])) {
				$dir = $_SERVER['DOCUMENT_ROOT'].$App->ConfigObj['adminpath']."i/";
				if (!is_dir($dir))
					$_ERROR = 'Impossibile trovare e creare la directory';
				else {
					$fileName = str_replace(" ", "-", $_FILES['logo_load']['name']);
					$fileNameTemp = $_FILES['logo_load']['tmp_name'];
					_log("ADM Configurator: Carico nuovo logo (".$dir.$fileName.")");
					if(move_uploaded_file($fileNameTemp, $dir.$fileName))
						$App->query('.//logo', $App->DOMObj)->item(0)->nodeValue = $fileName;
				}
			}
		}

		if($_REQUEST['tab'] == 2) {
			// Salvo i "Resources path"
			$App->query('.//adminpath', $App->DOMObj)->item(0)->nodeValue		= $_REQUEST['adminpath'];
			$App->query('.//adminmail', $App->DOMObj)->item(0)->nodeValue		= $_REQUEST['adminmail'];
			$App->query('.//adminloadingpath', $App->DOMObj)->item(0)->nodeValue= $_REQUEST['adminloadingpath'];

			/* not used */
			$App->query('.//adminlanguage', $App->DOMObj)->item(0)->nodeValue	= $_REQUEST['adminlanguage'];
			$App->query('.//admininc', $App->DOMObj)->item(0)->nodeValue		= $_REQUEST['admininc'];
			/* end not used */
		}

		if($_REQUEST['tab'] == 3) {
			// Salvo le "Connections"
			$tot_connections = $App->getNumConnections();

			// Salvo le eventuali modifiche alla configurazione
		}

		$App->saveConfig("ADM configurator: salvata nuova configurazione");
		$App->dom->save( $App->file );
		$App->reloadConfiguration();
		$App->Context = 'AdminConfiguring';

	} else
		_log("Trying updating Admin settings. Getting error: BAD _REQUEST");

}


if($_REQUEST['action'] == 'ADD_CONNECTION') {
	// Ok, devo salvare un nuovo oggetto di connessione
	$connName = str_ireplace(".", "", $_REQUEST['connectionHost']);
	$newConn = $App->dom->createElement($connName);
	$App->query('/config/database')->item(0)->appendChild($newConn);
	$host = $App->dom->createElement('host');
	$host->nodeValue = $_REQUEST['host'];
	$name = $App->dom->createElement('name');
	$name->nodeValue = $_REQUEST['name'];
	$user = $App->dom->createElement('user');
	$user->nodeValue = $_REQUEST['user'];
	$pwd = $App->dom->createElement('pwd');
	$pwd->nodeValue = $_REQUEST['pwd'];
	$newConn->appendChild($host);
	$newConn->appendChild($name);
	$newConn->appendChild($user);
	$newConn->appendChild($pwd);

	$App->saveConfig("ADM configurator: salvata nuova connessione Database");
	$App->dom->save( $App->file );
	header('Location: adm_configurator.php?tab=3');
}


if($_REQUEST['action'] == 'SAVING_DBTABLES') {

	if( isset($_REQUEST['app_tables'])) {
		$App->doBackup("Adding / Removing tables to Config.xml");

		/*
		 * Aggiungo tabelle non esistenti
		 */
		$tables = explode($App->ConfigObj['multifieldseparator'], $_REQUEST['app_tables']);
		foreach ($tables as $table) {
			if(!$App->hasTable($table) && $table != '' && $table != null)
				$App->addTable($table);
		}

		/*
		 * Cancello tabelle superflue
		 */
		foreach ($App->getTables() as $table) {
			if(!in_array($table->getAttribute('name'), $tables))
				$App->removeTable($table->getAttribute('name'));
		}
	}

}

if(isset($_REQUEST['do']) && $_REQUEST['do'] == 'CREATE') {
	/*
	 * Salvo
	 */
	$_ERR = 'DONE';
	_log("CONF: Trying create new module (name: ".$_SESSION['FF']['newmodule']['module_title'].", dir: ".$_SESSION['FF']['newmodule']['newModuleDir'].")");
	$ModuleRootDir = $App->getModuleRootDir();

	/* Creo la directory */
	if(!is_dir($ModuleRootDir . "/" . $_SESSION['FF']['newmodule']['newModuleDir'])) {

		if(mkdir($ModuleRootDir . "/" . $_SESSION['FF']['newmodule']['newModuleDir'], 0777)) {

			/* Setting-up new module config.xml file */
			$NewModule = new xml_manager();
			$NewModule->load($ModuleRootDir . "/template.conf.xml");
			$NewModule->query('/config/admin/title')->item(0)->nodeValue = $_SESSION['FF']['newmodule']['module_title'];
			$NewModule->query('/config/admin/subtitle')->item(0)->nodeValue = $_SESSION['FF']['newmodule']['module_subtitle'];
			$NewModule->query('/config/admin/version')->item(0)->nodeValue = $_SESSION['FF']['newmodule']['module_version'];
			$NewModule->query('/config/admin/adminpath')->item(0)->nodeValue = $_SESSION['FF']['newmodule']['module_adminpath'];
			$NewModule->query('/config/admin/adminloadingdir')->item(0)->nodeValue = $_SESSION['FF']['newmodule']['module_loadingdir'];
			$NewModule->query('/config/admin/adminemail')->item(0)->nodeValue = $_SESSION['FF']['newmodule']['module_email'];
			$NewModule->dom->save($ModuleRootDir . "/" . $_SESSION['FF']['newmodule']['newModuleDir'] . "/config.xml");

			/* Updating modules.conf.xml */
			$ModuleConf = new xml_manager();
			$ModuleConf->load($ModuleRootDir . "/modules.conf.xml");
			$newModule = $ModuleConf->dom->createElement('module');
			$newModule->setAttribute('name', $_SESSION['FF']['newmodule']['newModuleName']);
			$newModule->setAttribute('path', $_SESSION['FF']['newmodule']['newModuleDir']);
			$ModuleConf->query('//modules')->item(0)->appendChild($newModule);
			$ModuleConf->dom->save( $ModuleConf->file );

			_log("CONF: New module successfully created!");
			unset($_SESSION['FF']['newmodule']);

		} else {
			_log("CONF: Impossible to create new module dir. Access denied!");
			$_ERR = "Impossibile creare la directory: " . $ModuleRootDir . "/" . $_SESSION['FF']['newmodule']['newModuleDir'];
		}

	} else {
		_log("CONF: Impossible to create new module. Directory already exists!");
		$_ERR = "La directory ".$ModuleRootDir . "/" . $_SESSION['FF']['newmodule']['newModuleDir']." esiste gia'";
	}

	$_SESSION['FF']['newmodulestatus'] = $_ERR;
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Content</title>
	<style>@import '../css/common.css';</style>
	<?php echo $App->setGlobalJsVars(); ?>
	<script type="text/javascript" src="../js/libs/prototype.js"></script>
	<script type="text/javascript" src="../js/libs/scriptaculous.js"></script>
	<script type="text/javascript" src="../js/FF.js"></script>
	<script type="text/javascript" src="../js/FF.Utils.js"></script>
	<script type="text/javascript" src="../js/FF.UI.js"></script>
	<script type="text/javascript" src="../js/FF.EventManager.js"></script>
	<script type="text/javascript" src="../js/FF.Toolbar.js"></script>
	<script type="text/javascript" src="../js/FF.Contents.js"></script>
	<script type="text/javascript" src="../js/FF.Console.js"></script>
	<script type="text/javascript" src="../js/FF.Configurator.js"></script>
	<?php echo $App->setGlobalAppVars(); ?>
</head>
<body>

<div id="contentsContent">

	<div id="contentHeader">
		<?php require_once ($App->inc_dir . "/toolbar.php"); ?>
		<?php require_once ($App->conf_dir . "/conf_tabs.php"); ?>
	</div>

	<div id="contentWrapper">

		<?php require_once('conf.general_preferences.php'); ?>

	</div>
	<!-- fine contentWrapper -->

	<?php if(isset($_REQUEST['action'])) { ?>
		<script>FF.report('Last action: <span style="color:green"><?php echo $_REQUEST['action']; ?></span>');</script>
	<?php } ?>

</div>
<script>
	FF.Configurator.ShowTab($('<?=$_REQUEST['act']?>').down(), 'conf.<?=$_REQUEST['act']?>.php');
</script>
</body>
</html>




