<?php



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
    $best->userObject = Users::find($bestUser)->toArray();
    $best->punteggio = $bestUserPunteggio;
    echo FastJSON::convert($best);

  else:
    Flight::notFound();
  endif;
});

Flight::route('GET /users/@id_utente/messages', function($id_utente) {
  echo  Users::find($id_utente)->messages->toJson();
});

Flight::route('GET /users/@id_utente/messages/unread', function($id_utente) {
  echo Messages::receiver($id_utente)->unread()->get()->toJson();
});

Flight::route('GET /user/@id_utente/messages/sent', function($id_utente) {
  echo Users::find($id_utente)->sentMessages->toJson();
});

Flight::route('GET /users/@id_utente/rewards', function($id_utente) {
  echo Users::find($id_utente)->rewards->toJson();
});

Flight::route('GET /users/@id_utente/sfide/dagiocare', function($id_utente) {
  echo Sfide::receivedBy($id_utente)->unplayed()->get();
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
  echo  Users::searchAttribute($attribute, $search)->get()->toJson();
});



/*
 * POSTS
 */

Flight::route('POST /users/@id_utente/badges/seen', function($id_utente) {
  RewardsSfide::user($id_utente)->update(array(
    "seen" => 1
  ));
});

Flight::route('POST /users/create', function() {
  if (Users::findBySocialId($_POST['id'])->get()->count() == 0):
    try {
      $newUser                  = new Users;
      $newUser->attivo          = 0;
      $newUser->social_provider = $_POST['provider'];
      $newUser->social_uid      = $_POST['id'];
      $newUser->social_url      = $_POST['link'];
      $newUser->username        = str_replace(" ", "_", $_POST['name']);
      $newUser->picture         = $_POST['picture'];
      $newUser->nome            = $_POST['given_name'];
      $newUser->cognome         = $_POST['family_name'];
      $newUser->sesso           = strtoupper(substr($_POST['gender'], 0, 1));
      $newUser->email           = $_POST['email'];

      $newUser->save();

      Flight::json(array("id_utente" => $newUser->id_utente));
    } catch (Exception $e) {
      Flight::error($e);
    }
  else:
    Flight::halt(409, "Trying to create a user that is already there");
  endif;
});

Flight::route('POST /users/delete', function() {
  $data = getParams();
  $user = $data->user;

  if ( UsersUnsubscribe::user($user->id_utente)->count() == 0 ):
    $unsubscribe = new UsersUnsubscribe;
    $unsubscribe->id_utente = $user->id_utente;
    $unsubscribe->stato = 0;
    $unsubscribe->conf_code = md5( $id_utente . $user->username . "unsubscribe_utente_key" );
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
  Flight::redirect($_SERVER["HTTP_REFERER"] . "?logout={$id_utente}");
});

Flight::route('GET /users/@id_utente/@attribute', function($id_utente, $attribute) {
  $userAttr = Users::find($id_utente);
  $attrValue = ( $userAttr != null ) ? $userAttr->getAttribute($attribute) : "- sconosciuto -";

  Flight::json( array($attribute => $attrValue, "id_utente" => $id_utente) );
});




/*
 * Resource Object for Users
 */

Flight::route('GET /users/@id_utente', function($id_utente) {
  if ( Users::find($id_utente)->exists ) {
    Users::find($id_utente)->update(array(
      "dta_activ" => date('Y-m-d H:i:s')
    ));
    echo FastJSON::convert( getUserObjectExtended($id_utente) );
  } else
    Flight::notFound();
});

Flight::route('POST /users/@id_utente', function($id_utente) {
  $data = json_decode(file_get_contents("php://input"));
  $userData = (array)( isset($data->db_object) ) ? $data->db_object : $data;
  $userData->picture = createUserPicture($userData->picture, $userData->username, $userData->id_utente);

  Users::find($id_utente)->update((array)$userData);

  echo FastJSON::convert( getUserObjectExtended($id_utente) );
});
