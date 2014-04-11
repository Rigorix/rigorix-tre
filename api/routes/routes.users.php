<?php

/// Save user token
Flight::route("PUT /users/@id_utente/{$env->TOKEN_SECRET}/@token", function($id_utente, $token) {
  if (Users::find($id_utente)->token()->count() > 0) {
    $userToken = Users::find($id_utente)->token;
    $userToken->update(array(
      "token"     => $token,
      "expire"    => date("Y-m-d H:m:s", strtotime("+1 week")),
    ));
  } else
    UserToken::create(array(
      "id_utente"   => $id_utente,
      "expire"      => date("Y-m-d H:m:s", strtotime("+1 week")),
      "token"       => $token
    ));
  echo $token;
  return false;
});

///---------------------------------------------------------------------------------------------------------------------

Flight::route('GET /users/active', function() {
  echo (string)Users::active()->get();
});

Flight::route('GET /users/top/@count', function($count) {
  echo Users::whereRaw("attivo = 1")->orderBy("punteggio_totale", "desc")->limit($count)->get();
});

Flight::route('GET /users/bysocial/@uid', function($uid) {
  $result = Users::findBySocialId($uid)->get();
  if ( $result->count() == 0)
    Flight::notFound();
  else
    echo Users::findBySocialId($uid)->get()->first();

});

Flight::route('GET /users/byemail/@email', function($email) {
  $result = Users::where("email", "=", $email)->get();
  if ( $result->count() == 0)
    Flight::notFound();
  else
    echo Flight::json(array(
      "id_utente" => $result->first()->getAttribute("id_utente"),
      "social_provider" => $result->first()->getAttribute("social_provider")
    ));
});

Flight::route('GET /user/exists', function($username) {
  echo Flight::userExists(Flight::request()->query) ? "true" : "false";
});

Flight::route('GET /users/champion/@period', function($period) {
  if ($period == "week")
    $sfide = Sfide::lastWeek()->done();
  if ($period == "month")
    $sfide = Sfide::lastMonth()->done();
  if ($period == "day")
    $sfide = Sfide::today()->done();

  if ( $sfide != null && $sfide->count() > 0):
    $usersPoints = array();
    foreach ( $sfide->get() as $sfida) {
      if ( !array_key_exists($sfida->id_sfidante, $usersPoints))
        $usersPoints[$sfida->id_sfidante] = 0;
      $usersPoints[$sfida->id_sfidante] += $sfida->punti_sfidante;

      if ( !array_key_exists($sfida->id_sfidato, $usersPoints))
        $usersPoints[$sfida->id_sfidato] = 0;
      $usersPoints[$sfida->id_sfidato] += $sfida->punti_sfidato;
    }

    $bestUser = 0;
    $bestUserPunteggio = 0;
    foreach ( $usersPoints as $id_utente => $punteggio ) {
      if ( $punteggio > $bestUserPunteggio ) {
        $bestUser = $id_utente;
        $bestUserPunteggio = $punteggio;
      }
    }
    $best = new stdClass();
    $best->userObject = Flight::getUserObjectExtended($bestUser);
    $best->punteggio = $bestUserPunteggio;
    $best->vittorie = Sfide::whereRaw("id_vincitore = $bestUser")->count();
    echo FastJSON::convert($best);

  else:
    Flight::notFound();
  endif;
});

Flight::route('GET /users/@id_utente/messages', function($id_utente) {
  Flight::needsAuth();

  $start = isset(Flight::request()->query->start) ? Flight::request()->query->start : 0;
  $count = isset(Flight::request()->query->count) ? Flight::request()->query->count : 20;

  echo (string)Users::find($id_utente)->messages()->orderBy('created_at', 'DESC')->take($count)->skip($start)->get();
});

Flight::route('GET /users/@id_utente/messages/unread', function($id_utente) {
  Flight::needsAuth();
  echo (string)Messages::receiver($id_utente)->unread()->orderBy('created_at', 'DESC')->get();
});

Flight::route('GET /user/@id_utente/messages/sent', function($id_utente) {
  Flight::needsAuth();
  echo (string)Users::find($id_utente)->sentMessages()->orderBy('created_at', 'DESC')->get();
});

Flight::route('GET /users/@id_utente/rewards', function($id_utente) {
  Flight::needsAuth();
  echo Users::find($id_utente)->rewards->toJson();
});

Flight::route('GET /users/@id_utente/badges/unseen', function($id_utente) {
  Flight::needsAuth();
  echo (string)Users::find($id_utente)->unseenBadges();
});

Flight::route('GET /users/@id_utente/badges', function($id_utente) {
  Flight::needsAuth();
  echo (string)Users::find($id_utente)->badges();
});

Flight::route('GET /users/@id_utente/sfide/dagiocare', function($id_utente) {
  Flight::needsAuth();
  echo Sfide::receivedBy($id_utente)->unplayed()->normal()->get();
});

Flight::route('GET /users/@id_utente/basic', function($id_utente) {
  $user = Users::find($id_utente);
  echo Flight::json(array(
    "id_utente"   => intval($id_utente),
    "username"    => $user->getAttribute("username"),
    "picture"     => $user->getAttribute("picture"),
    "punteggio"   => $user->getAttribute("punteggio_totale"),
    "attivo"      => $user->getAttribute("attivo")
  ));
});

Flight::route('GET /users/search/@attribute/@search', function($attribute, $search) {
  Flight::needsAuth();
  echo  Users::searchAttribute($attribute, $search)->get()->toJson();
});



/*
 * POSTS / PUTS
 */

Flight::route('POST /users/@id_utente/badges/seen', function($id_utente) {
  Flight::needsAuth();
  RewardsSfide::user($id_utente)->update(array(
    "seen" => 1
  ));
});

Flight::route('POST /users/save/picture', function() { global $env;
  if (!isset($_FILES['file']))
    Flight::halt(500, "Errore generico. Riprova dopo");

  if ($_FILES['file']['error'] !== 0)
    Flight::halt(500, "Errore generico. Riprova di nuovo");

  if ($_FILES['file']['size'] > $env->PROFILE_PICTURE_MAX_SIZE)
    Flight::halt(500, "Immagine troppo grande (Max {$env->PROFILE_PICTURE_MAX_SIZE}bytes)");

  $info = getimagesize($_FILES['file']['tmp_name']);
  if ($info === false)
    Flight::halt(500, "Tipo immagine non riconosciuto");

  if ( !in_array($info[2], array(IMG_GIF, IMG_JPEG, IMG_JPG, IMG_PNG)))
    Flight::halt(500, "L'immagine dev'essere gif/jpeg/png");

  $fileName = $env->PROFILE_PICTURE_PATH . $_FILES['file']['name'];
  if (file_exists($_SERVER['DOCUMENT_ROOT'] . $fileName))
    $fileName = str_replace(".", time().".", $fileName);

  if ( move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $fileName) )
    Flight::json(array("profile_picture" => $fileName));

  Flight::halt(500, "Errore generico");
});

Flight::route('POST /users/create', function() {
  if (
    Users::findBySocialId($_POST['id'])->get()->count() == 0 &&
    Flight::request()->ajax === false
  ):
    unset($_POST["auth_token"]);
    unset($_POST["auth_id"]);
    $_POST["username"] = Flight::getValidUsername($_POST["username"]);

    try {
      $newUser = Users::create($_POST);
      _log("Api:users/create", "new user:".(string)$newUser);

      Flight::json(array("id_utente" => $newUser->id_utente));
    } catch (Exception $e) {
      Flight::error($e);
    }
  else:
    Flight::halt(409, "Trying to create a user that is already there");
  endif;
});


Flight::route("GET /test-periodic-actions", function () {
  Flight::checkPeriodicActions();
});





Flight::route('POST /users/rawdelete/@id_utente', function($id_utente) {
  Flight::needsAuth();
  Flight::needsPermission($id_utente);

  Users::find($id_utente)->delete();
});

Flight::route('POST /users/delete', function() {
  Flight::needsAuth();
  $data = getParams();
  $user = $data->user;

  if ( UsersUnsubscribe::user($user->id_utente)->count() == 0 ):
    $unsubscribe = new UsersUnsubscribe;
    $unsubscribe->id_utente = $user->id_utente;
    $unsubscribe->stato = 0;
    $unsubscribe->conf_code = md5( $user->id_utente . $user->username . "unsubscribe_utente_key" );
    $unsubscribe->save();

    $userObj = Users::find($user->id_utente);
    $userObj->attivo = 0;
    $userObj->save();

    Flight::json(array("status" => "success", "id_utente" => "{$user->id_utente}"));
  else:
    Flight::halt(406, "Cannot accept to unsubscribe a user that already requested to subscribe");
  endif;
});

Flight::route('POST /users/@id_utente/logout', function($id_utente) {
  Flight::needsAuth();
  Flight::redirect($_SERVER["HTTP_REFERER"] . "?logout={$id_utente}");
});

Flight::route('GET /users/@id_utente/@attribute', function($id_utente, $attribute) {
  Flight::needsAuth();
  $userAttr = Users::find($id_utente);
  $attrValue = ( $userAttr != null ) ? $userAttr->getAttribute($attribute) : "- sconosciuto -";

  Flight::json( array($attribute => $attrValue, "id_utente" => $id_utente) );
});




/*
 * Resource Object for Users
 */

Flight::route('GET /users/@id_utente', function($id_utente) {
  Flight::needsAuth();
  if ( Users::find($id_utente)->exists ) {
    Users::find($id_utente)->update(array(
      "dta_activ" => date('Y-m-d H:i:s')
    ));
    echo FastJSON::convert( Flight::getUserObjectExtended($id_utente) );
  } else
    Flight::notFound();
});

Flight::route('POST /users/@id_utente', function($id_utente) {
  Flight::needsAuth();
  Flight::needsPermission($id_utente);

  $user = Users::find($id_utente);
  $data = json_decode(Flight::request()->body);

  $userData = (array)( isset($data->db_object) ) ? $data->db_object : $data;
  $userData->picture = Flight::createUserPicture($userData->picture, $userData->username, $userData->id_utente);
  if ($user->getAttribute("attivo") == 1)
    unset($userData->username);
  unset($userData->email);

  $user->update((array)$userData);

  echo FastJSON::convert( Flight::getUserObjectExtended($id_utente) );
});
