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
  'base_url' => substr($env->API_DOMAIN, 0, -1),
  'parameters' => array(
    'auth_token' => isset($_COOKIE['auth_token']) ? $_COOKIE['auth_token'] : "",
    'auth_id' => isset($_COOKIE['auth_id']) ? $_COOKIE['auth_id'] : ""
  )
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

  function check_user () { global $api;
    _log("CHECK_USER", "##############################################################################################");

    if ( isset ($_REQUEST['logout']) )
      $this->do_user_logout ();

    if ( isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_id']) ) {
      _log("CHECK_USER", "auth_token={$_COOKIE['auth_token']}, auth_id={$_COOKIE['auth_id']}");
      $this->do_user_login ();
    }

    if (isset($_REQUEST['signature'])) {
      _log("CHECK_USER", "signature found");

      $result = $api->get("users/bysocial/{$_REQUEST['auth']['uid']}");

      if ($result->info->http_code == 200 ) {
        _log("CHECK_USER", "Found user by social uid ({$_REQUEST['auth']['uid']}), create token and redirect to /");

        $this->create_user_token($result->decode_response()->id_utente, md5($_REQUEST['auth']['credentials']['token']));
        header('Location: /');

      } else if ($result->info->http_code == 404 ) {
        _log("CHECK_USER", "User not found, check if this new user uses an existing email, then create it");

        $result = $api->get("users/byemail/{$_REQUEST['auth']['info']['email']}");
        if ( $result->info->http_code == 200 )
          setcookie("auth_user_exist", $result->decode_response()->social_provider, time() + (60*512312));

        $this->do_create_new_user();

      } else
        $this->do_user_logout ();

    }

  }

  function do_create_new_user () { global $api;
    _log("DO_CREATE_NEW_USER");

    $newUserParams = $this->prepare_social_login_object($_REQUEST);
    $newUserPost = $api->post("users/create/", $newUserParams);

    if ($newUserPost->info->http_code == 200) {
      _log("DO_CREATE_NEW_USER", "User created successfully ({$newUserPost->decode_response()->id_utente}), create token");

      $this->create_user_token($newUserPost->decode_response()->id_utente, md5($_REQUEST['auth']['credentials']['token']));

      _log("DO_CREATE_NEW_USER", "User token created, going to /first-login");
      header('Location: /#/first-login');

    } else if ($newUserPost->info->http_code == 500 ) {
      _log("DO_CREATE_NEW_USER", "Problems creating new user, response 500 from API");
      echo "Error 500 - cannot create new user";
    }
  }

  function do_user_login () { global $api;
    _log("DO_USER_LOGIN", "User id: {$_COOKIE["auth_id"]}");

    $result = $api->get("users/{$_COOKIE["auth_id"]}");
    if ($result->info->http_code == 200 )
      $this->logged = $result->response;
    else
      _log("DO_USER_LOGIN", "Not able to get the logged user with id: {$_COOKIE["auth_id"]}");
  }

  function do_user_logout ()
  {
    setcookie("auth_token", "", time()-(60*60*24));
    setcookie("auth_id", "", time()-(60*60*24));

    _log("Core::logout", "Logout user {$_REQUEST['logout']}");
    if ( $_REQUEST['logout'] == $_SESSION['rigorix_logged_user'])
      session_destroy();
    else
      _log("Core::logout", "User isn't logged in");
    $this->logged = "false";
    header("Location: /");
  }

  function create_user_token($id_utente, $token) { global $env, $api;
    $api->put("users/{$id_utente}/{$env->TOKEN_SECRET}/{$token}");

    setcookie("auth_token", $token, time() + $env->AUTH_TOKEN_VALIDITY * (24 * 60 * 60));
    setcookie("auth_id", $id_utente, time() + $env->AUTH_TOKEN_VALIDITY * (24 * 60 * 60));
  }

  function sanitizeUsername ($username)
  {
    return $username;
  }

  function prepare_social_login_object($request)
  {
    $username = $this->sanitizeUsername(isset($request['auth']['info']['nickname']) ? $request['auth']['info']['nickname'] : "");
    // TODO: get a safe username

    return array(
      "attivo"          => 0,
      "first_login"     => 1,
      "social_provider" => $request['auth']['provider'],
      "social_uid"      => $request['auth']['uid'],
      "social_url"      => isset($request['auth']['info']['urls'][0]),
      "username"        => $username,
      "picture"         => isset($request['auth']['info']['image']) ? $request['auth']['info']['image'] : "",
      "nome"            => isset($request['auth']['info']['first_name']) ? $request['auth']['info']['first_name'] : "",
      "cognome"         => isset($request['auth']['info']['last_name']) ? $request['auth']['info']['last_name'] : "",
      "email"           => isset($request['auth']['info']['email']) ? $request['auth']['info']['email'] : "",
      "email_utente"    => isset($request['auth']['info']['email']) ? $request['auth']['info']['email'] : ""
    );
  }
  
}
