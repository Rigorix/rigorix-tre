<?php
session_start();

// Settings
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');
ini_set("memory_limit", "128M") ;
ini_set("display_errors", 1);
ini_set("display_startup_errors", true);

date_default_timezone_set('Europe/Rome');

// GET environment conf
$env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env'));

// Libs and classes
require_once __DIR__ . '/fastjson.php';
require_once __DIR__ . '/restclient.php';
require_once __DIR__ . '/logger.php';

$api = new RestClient(array(
  'base_url' => substr($env->API_DOMAIN, 0, -1)
));


// Start App
$core = new Core();
$core->start();


class Core {

  function core ()
  {
    $this->logged = "false";
  }

  function start ()
  {
    _log("CORE CLASS", "starts");
    $this->check_user ();
  }

  function check_user ()
  { global $api;

    if ( isset ($_REQUEST['logout']) ) {
      if ( $_REQUEST['logout'] == $_SESSION['rigorix_logged_user'])
        session_destroy();
      header("Location: /");
    }

    if ( isset($_SESSION['rigorix_logged_user']) && $_SESSION['rigorix_logged_user'] != 0 ) {

      $result = $api->get("users/{$_SESSION['rigorix_logged_user']}");
      if ($result->info->http_code == 200 )
        $this->logged = $result->response;
      else {
        unset($_SESSION['rigorix_logged_user']);
        $this->logged = "false";
      }
    }
    if ( isset($_REQUEST['signature'])) {

      $result = $api->get("users/bysocial/{$_REQUEST['auth']['uid']}");
      if ($result->info->http_code == 200 ) {

        _log("Opauth callback", "User found, get id: {$result->decode_response()->id_utente}");
        $_SESSION['rigorix_logged_user'] = $result->decode_response()->id_utente;
        header('Location: /');

      } else if ($result->info->http_code == 404 ) {  // User not found, new subscription

        $newUserParams = $_REQUEST['auth']['raw'];
        $newUserParams['provider'] = $_REQUEST['auth']['provider'];
        $newUserPost = $api->post("users/create/", $newUserParams);

        if ($newUserPost->info->http_code == 200) {

          $_SESSION['rigorix_logged_user'] = $newUserPost->decode_response()->id_utente;
          header('Location: /#/first-login');

        } else if ($newUserPost->info->http_code == 500 ) {
          echo "Error 500";
        }

      } else {
        $this->logged = "false";
        echo '<strong style="color: red;">Unknown error on login.'."<br>\n";
      }

    }

    _log ("CORE_CLASS", "check_user ended");

  }

}
