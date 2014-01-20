<?
/*
 * 	Core main settings
 */
// GET environment conf
$env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env'));

ini_set("memory_limit", "128M") ;
ini_set("display_errors", 1);
ini_set("display_startup_errors", true);
session_start();
date_default_timezone_set('Europe/Rome');

define ( "hybridauth_config", "/hybridauth/config.php");

define ( "BADGE_PICTURE_PATH", '/i/rewards/' );
define ( "BADGE_PICTURE_REPOSITORY", $_SERVER['DOCUMENT_ROOT'] . '/i/rewards/' );

// USER
define ( "DELETED_USER_PREFIX", "__DELETED__" );

// Number of penalty sets to be filled.
// The number is referring to "pairs" (x shots, x saves)
define ( "GAMSE_SETS", 5);


/*
 * 	Rigorix configurations
 */
if ( !isset ($_SESSION['rigorix']) || $_SESSION['rigorix'] == false ) {
	// Apply default starting configurations
	$setup = array();
	$setup['settings'] = array(
		"DEVELOPER"					          => false,
		"MAX_PROFILE_PICTURE_SIZE"	  	=> 1000000,
		"PROFILE_PICTURE_ROOT"  		=> '/i/profile_picture/',
		"PROFILE_PICTURE_REPOSITORY"  	=> $_SERVER['DOCUMENT_ROOT'] . '/i/profile_picture/',
		"ALLOW_EMAIL_SEND"			    => true,
		"MAIL_HEADER"				    => "",
		"MAX_MATCH_PER_WEEK"		    => 50,
		"INLINE_DEBUG"				    => false,
		"LOG_FILE"						=> $_SERVER['DOCUMENT_ROOT'] . "/log/".date("Y-m-d")."_log.txt",
		"ADMINS"						=> array( "bitter" )
	);
	$setup['storage'] = array();
	$setup['db'] = array(
		"DB_HOST"			=> $env->DB->host,
		"DB_NAME"			=> $env->DB->name,
		"DB_USER"			=> $env->DB->username,
		"DB_PWD"			=> $env->DB->password,
		"DB_CONN"			=> false
	);
	$setup['user'] = false;
	$setup['test'] = true;
	$setup['log'] = '';


	$_SESSION['rigorix'] = $setup;

}

/*
 * 	Database connection
 */
$db_conn = mysql_pconnect ($env->DB->host, $env->DB->username, $env->DB->password);
mysql_select_db ( $env->DB->name );

$sql_debug = 1;
$db_name = $env->DB->name;

/*
 * 	Setup Developer Mode
 */
if (isset ($_REQUEST['developer-mode']) && $_REQUEST['developer-mode'] == "false")
	$_SESSION['rigorix']['settings']['DEVELOPER'] = false;
if (
	(isset($_REQUEST['test']) && $_REQUEST['test'] == '123qweasd') ||
	(isset ($_REQUEST['developer-mode']) && $_REQUEST['developer-mode'] == "true")
)
	$_SESSION['rigorix']['settings']['DEVELOPER'] = false;


/*
 * 	Shutdown RIGORIX!!

if (SHUTDOWN == true && $_SESSION['rigorix']['settings']['developer_mode'] == 'true')
	header ('Location: lavori_in_corso.html');

*/

function dev() {
	return  $_SESSION['rigorix']['settings']['DEVELOPER'];
}

?>