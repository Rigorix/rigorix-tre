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
    _log("Core::start");
    $this->check_user ();
  }

  function get_env_vars ()
  { global $env;
    $envPrivate = $env;
    unset($envPrivate->DB);
    return json_encode($envPrivate);
  }

  function check_user ()
  { global $api, $env;

    if ( isset ($_REQUEST['logout']) ) {
      _log("Core::logout", "Logout user {$_REQUEST['logout']}");
      if ( $_REQUEST['logout'] == $_SESSION['rigorix_logged_user'])
        session_destroy();
      else
        _log("Core::logout", "User isn't logged in");
      header("Location: /");
    }

    if ( isset($_SESSION['rigorix_logged_user']) && $_SESSION['rigorix_logged_user'] != 0 ) {
      _log("Core::rigorix_logged_user", $_SESSION['rigorix_logged_user']);

      $result = $api->get("users/{$_SESSION['rigorix_logged_user']}");
      if ($result->info->http_code == 200 ) {
        _log("Core::rigorix_logged_user", "User found, this->logged: {$result->response}");
        $this->logged = $result->response;
      } else {
        _log("Core::rigorix_logged_user", "User not found, clear session and this->logged");
        unset($_SESSION['rigorix_logged_user']);
        $this->logged = "false";
      }
    }
    if ( isset($_REQUEST['signature'])) {
      _log("Core::rigorix_logged_user", "Found a signature, coming from Opauth (uid: {$_REQUEST['auth']['uid']})");

      $result = $api->get("users/bysocial/{$_REQUEST['auth']['uid']}");
      if ($result->info->http_code == 200 ) {
        _log("Core::rigorix_logged_user", "User found by social uid ({$_REQUEST['auth']['uid']}), inserisco in sessione e vado in /");

//        $token = $this->createUserToken($result->decode_response()->id_utente)->response;
        $token = $_REQUEST['auth']['credentials']['token'];
        $path = "users/{$result->decode_response()->id_utente}/{$env->TOKEN_SECRET}/{$token}";
        $api->put($path);

        _log("Core::rigorix_logged_user_token", $token);
        setcookie("auth_token", $token, time() + $env->AUTH_TOKEN_VALIDITY * (24 * 60 * 60));

        $_SESSION['rigorix_logged_user'] = $result->decode_response()->id_utente;
        header('Location: /');

      } else if ($result->info->http_code == 404 ) {
        _log("Core::rigorix_logged_user", "User not found by social uid, new subscription");

        $newUserParams = $this->prepareSocialLoginObject($_REQUEST);
        _log("Core::rigorix_logged_user", "Social AUTH: ".FastJSON::convert($newUserParams));
        $newUserPost = $api->post("users/create/", $newUserParams);

        if ($newUserPost->info->http_code == 200) {
          _log("Core::rigorix_logged_user", "User created successfully ({$newUserPost->decode_response()->id_utente})");

          $_SESSION['rigorix_logged_user'] = $newUserPost->decode_response()->id_utente;
          header('Location: /#/first-login');

        } else if ($newUserPost->info->http_code == 500 ) {
          echo "Error 500";
        }

      } else {
        _log("Core::rigorix_logged_user", "Unknown error during login");
        $this->logged = "false";
      }

    }

  }

  function createUserToken($id_utente)
  { global $env, $api;

    $path = "users/{$id_utente}/{$env->TOKEN_SECRET}";
    $result = $api->put($path);
    return $result;
  }

  function prepareSocialLoginObject($request)
  {
    return array(
      "attivo"          => 0,
      "first_login"     => 1,
      "social_provider" => $request['auth']['provider'],
      "social_uid"      => $request['auth']['uid'],
      "social_url"      => isset($request['auth']['info']['urls'][0]),
      "username"        => isset($request['auth']['info']['nickname']) ? $request['auth']['info']['nickname'] : "",
      "picture"         => isset($request['auth']['info']['image']) ? $request['auth']['info']['image'] : "",
      "nome"            => isset($request['auth']['info']['first_name']) ? $request['auth']['info']['first_name'] : "",
      "cognome"         => isset($request['auth']['info']['last_name']) ? $request['auth']['info']['last_name'] : "",
      "email"           => isset($request['auth']['info']['email']) ? $request['auth']['info']['email'] : "",
      "email_utente"    => isset($request['auth']['info']['email']) ? $request['auth']['info']['email'] : ""
    );
  }

}
