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

      $result = $api->get("users/" . $_SESSION['rigorix_logged_user']);
      if ($result->info->http_code == 200 )
        $this->logged = $result->response;

    } else if ( isset($_GET['id']) && isset($_GET['token']) && $_GET['token'] != "") {

      $result = $api->get("users/bysocial/" . $_GET['id']);
      if ($result->info->http_code == 200 ) {
        $user = $result->decode_response();
        $_SESSION['rigorix_logged_user'] = $user->id_utente;
        $_SESSION['rigorix_logged_user_token'] = $_GET['token'];
        header('Location: /');
      } else if ($result->info->http_code == 404 ) {  // User not found, new subscription

        $api->post("users/create/", $result->decode_response());
      }

    } else
      $this->logged = "false";

  }

}

