<?php
session_start();
error_reporting(E_ERROR);
ini_set("display_errors", "on");

require_once ("class.xml.php");
require_once ("class.user.php");
require_once ("class.app.php");


$User = new UserManager('modules/modules.conf.xml');
if(isset($User->Module)) {
	
	$App = new Application('modules/' . $User->Module . '/config.xml');
	
	require_once ($App->inc_dir . "/class.database.php");
	require_once ($App->inc_dir . "/class.table.php");
	require_once ($App->inc_dir . "/class.utils.php");
	
	$Utils = new Utils();
	
}
/* Leggo il dizionario */
if($App != null && $App->ConfigObj['adminlanguage'] != '' && file_exists($App->Root . "languages/" . $App->ConfigObj['adminlanguage'])) 
	require_once ($App->Root . "languages/" . $App->ConfigObj['adminlanguage']);
else 
	require_once (getRoot() . "languages/default.php");


function _log($msg, $error = false) {
	global $App;
	
	if($error != false)
		$msg = '<span style="color: red">'.$msg.'/span>';
		
	$_SESSION['FF']['logger'] .= "*" . $msg . "<br>";
	
	$fc = fopen($App->ErrorLogFile, 'a') or die ("can't open errorlog file");
	fwrite($fc, '
'.date("Y-m-d H:i:s").'> ' . $msg);
	fclose($fc);
}

function _logToPage($msg) {
	_log($msg);
	$_SESSION['FF']['pageLogger'] .= $msg."<br />";
}

function getRoot()
{
	$root = '';
	while(!is_dir($root . 'inc')) 
		$root = '../' . $root;
	return $root;
}

?>