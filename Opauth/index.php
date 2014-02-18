<?php
/**
 * Opauth example
 * 
 * This is an example on how to instantiate Opauth
 * For this example, Opauth config is loaded from a separate file: opauth.conf.php
 * 
 */

require_once "../classes/logger.php";
/**
 * Define paths
 */
session_start();
define('CONF_FILE', dirname(__FILE__).'/opauth.conf.php');
define('OPAUTH_LIB_DIR', dirname(__FILE__).'/lib/Opauth/');

/**
* Load config
*/
if (!file_exists(CONF_FILE)){
	trigger_error('Config file missing at '.CONF_FILE, E_USER_ERROR);
	exit();
}
require CONF_FILE;
$config['callback_url'] = $_SERVER['HTTP_REFERER'] . "Opauth/callback.php";

_log("Opauth", "################################################################################");
_log("Opauth", "START NEW login process, callback url: {$config['callback_url']}");

/**
 * Instantiate Opauth with the loaded config
 */
require OPAUTH_LIB_DIR.'Opauth.php';
_log("Opauth", "Instanzio Opauth");
$Opauth = new Opauth( $config );
_log("Opauth", "DONE");

?>


Sto caricando il motherfucker

<? var_dump($config); ?>