<?php
session_start();
unset($_SESSION['rigorix_logged_user']);

/**
 * Callback for Opauth
 * 
 * This file (callback.php) provides an example on how to properly receive auth response of Opauth.
 * 
 * Basic steps:
 * 1. Fetch auth response based on callback transport parameter in config.
 * 2. Validate auth response
 * 3. Once auth response is validated, your PHP app should then work on the auth response 
 *    (eg. registers or logs user in to your site, save auth data onto database, etc.)
 * 
 */


/**
 * Define paths
 */
define('CONF_FILE', dirname(__FILE__).'/'.'opauth.conf.php');
define('OPAUTH_LIB_DIR', dirname(__FILE__).'/lib/Opauth/');

/**
* Load config
*/
if (!file_exists(CONF_FILE)){
	trigger_error('Config file missing at '.CONF_FILE, E_USER_ERROR);
	exit();
}
require CONF_FILE;

/**
 * Instantiate Opauth with the loaded config but not run automatically
 */
require OPAUTH_LIB_DIR.'Opauth.php';
$Opauth = new Opauth( $config, false );


/**
* Fetch auth response, based on transport configuration for callback
*/
$response = null;

switch($Opauth->env['callback_transport']){	
	case 'session':
		session_start();
		$response = $_SESSION['opauth'];
		unset($_SESSION['opauth']);
		break;
	case 'post':
		$response = unserialize(base64_decode( $_POST['opauth'] ));
		break;
	case 'get':
		$response = unserialize(base64_decode( $_GET['opauth'] ));
		break;
	default:
		echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.'."<br>\n";
		break;
}

/**
 * Check if it's an error callback
 */
if (array_key_exists('error', $response)){
	echo '<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.'."<br>\n";
}

/**
 * Auth response validation
 * 
 * To validate that the auth response received is unaltered, especially auth response that 
 * is sent through GET or POST.
 */
else{
	if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])){
		echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.'."<br>\n";
	}
	elseif (!$Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)){
		echo '<strong style="color: red;">Invalid auth response: </strong>'.$reason.".<br>\n";
	}
	else {
    /**
     * It's all good. Go ahead with your application-specific authentication logic
     */
    require_once __DIR__ . '/../classes/restclient.php';
    $env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env'));
    $api = new RestClient(array('base_url' => substr($env->API_DOMAIN, 0, -1)));

    $result = $api->get("users/bysocial/{$response['auth']['uid']}");
    if ($result->info->http_code == 200 ) {

      $user = $result->decode_response();
      $_SESSION['rigorix_logged_user'] = $result->decode_response()->id_utente;
      header('Location: /');

    } else if ($result->info->http_code == 404 ) {  // User not found, new subscription

      $newUserParams = $response['auth']['raw'];
      $newUserParams['provider'] = $response['auth']['provider'];
      $newUserPost = $api->post("users/create/", $newUserParams);

      if ($newUserPost->info->http_code == 200) {

        $_SESSION['rigorix_logged_user'] = $newUserPost->decode_response()->id_utente;
        header('Location: /');

      } else if ($newUserPost->info->http_code == 500 ) {
        echo "Error 500";

      } else {
        var_dump($newUserPost);
        echo "<br>";
        var_dump($newUserParams);
        echo "Status {$newUserPost->info->http_code}";
      }

    } else {
      echo '<strong style="color: red;">Unknown error on login.'."<br>\n";
    }

	}
}

/**
* Auth response dump
*/
//echo "<pre>";
//$_SESSION['rigorix_oauth_user'] = $response;
print_r($response);
//echo "</pre>";
?>

