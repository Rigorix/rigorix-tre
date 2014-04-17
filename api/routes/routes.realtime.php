<?php


// METHODS //-----------------------------------------------------------------------------------------------------------

Flight::map("membersListChanged", function($count) {
  return $count != RealtimeRegistrations::all()->count();
});

Flight::map("memberHasUpdated", function($id_utente, $lastMemberUpdate) {
  return $lastMemberUpdate != RealtimeRegistrations::user($id_utente)->first()->updated_at;
});

Flight::map("returnRealtimeResponse", function () {
  Flight::json(array(
    "member"  => json_decode((string)RealtimeRegistrations::user(Flight::get("auth_id"))->get()->first()),
    "members" => json_decode((string)RealtimeRegistrations::all())
  ));
});

Flight::map("returnSfidaObject", function($id_sfida) {
  $sfida = Sfide::find($id_sfida);
  $id_avversario = ($sfida->getAttribute("id_sfidante") == Flight::get("auth_id")) ? $sfida->getAttribute("id_sfidato") : $sfida->getAttribute("id_sfidante");

  $return = array(
    sfida => json_decode((string)$sfida),
    user => array(
      "tiri" => json_decode((string)SfideTiri::user(Flight::get("auth_id"))->sfida($id_sfida)->get()->first()),
      "parate" => json_decode((string)SfideParate::user(Flight::get("auth_id"))->sfida($id_sfida)->get()->first())
    ),
    avversario => array(
      "tiri" => json_decode((string)SfideTiri::user($id_avversario)->sfida($id_sfida)->get()->first()),
      "parate" => json_decode((string)SfideParate::user($id_avversario)->sfida($id_sfida)->get()->first())
    )
  );

  Flight::json($return);
});



// GETS //--------------------------------------------------------------------------------------------------------------

Flight::route('GET /realtime/member', function() {
  Flight::needsAuth();

  $userCount = RealtimeRegistrations::all()->count();
  $lastMemberUpdate = RealtimeRegistrations::user(Flight::get("auth_id"))->first()->updated_at;

  while (!Flight::membersListChanged($userCount) && !Flight::memberHasUpdated(Flight::get("auth_id"), $lastMemberUpdate)) {
    sleep(3);
  }

  Flight::returnRealtimeResponse();
});




// POSTS //-------------------------------------------------------------------------------------------------------------

Flight::route('POST /realtime/register/@id_utente', function($id_utente) {
  Flight::needsAuth();

  if (RealtimeRegistrations::user($id_utente)->get()->isEmpty())
    RealtimeRegistrations::create(array(
      "id_utente" => $id_utente
    ));

  Flight::returnRealtimeResponse();
});

Flight::route('POST /realtime/unregister/@id_utente', function($id_utente) {
  Flight::needsAuth();

  RealtimeRegistrations::user($id_utente)->first()->delete();
});

Flight::route('POST /realtime/request/@id_utente', function($id_utente) {
  Flight::needsAuth();

  $avversario = RealtimeRegistrations::user($id_utente)->first();
  if ($avversario->busy_with == 0) {
    $avversario->update(array(
      "has_request_from" => Flight::get("auth_id")
    ));
    RealtimeRegistrations::user(Flight::get("auth_id"))->first()->touch();
  }
});

Flight::route('POST /realtime/accept/@id_avversario', function($id_avversario) {
  Flight::needsAuth();

  $loggedUser = RealtimeRegistrations::user(Flight::get("auth_id"))->first();
  if ($loggedUser->has_request_from == $id_avversario) {

    $newSfida = Sfide::create(array(
      "tipo"          => "realtime",
      "id_sfidante"   => $id_avversario,
      "id_sfidato"    => Flight::get("auth_id"),
      "stato"         => 0
    ));

    $idSfida = $newSfida->getAttribute("id_sfida");
    $idUtente = Flight::get("auth_id");

    SfideParate::create(array(
      "id_sfida"  => $idSfida,
      "id_utente" => $idUtente
    ));
    SfideParate::create(array(
      "id_sfida"  => $idSfida,
      "id_utente" => $id_avversario
    ));
    SfideTiri::create(array(
      "id_sfida"  => $idSfida,
      "id_utente" => $idUtente
    ));
    SfideTiri::create(array(
      "id_sfida"  => $idSfida,
      "id_utente" => $id_avversario
    ));

    $loggedUser->update(array(
      "busy_with"         => $idSfida,
      "has_request_from"  => 0
    ));
    RealtimeRegistrations::user($id_avversario)->first()->update(array(
      "busy_with"         => $idSfida,
      "has_request_from"  => 0
    ));

    Flight::json(array(
      "id_sfida" => $idSfida
    ));
  }

});

//Flight::route('GET /realtime/sfida/@id_sfida/status', function($id_sfida) {
//  Flight::needsAuth();
//
//  $sfidaUpdatedAt = Sfide::find($id_sfida)->getAttribute("updated_at");
//
//  while (Sfide::find($id_sfida)->getAttribute("updated_at") == $sfidaUpdatedAt) {
//    sleep(3);
//  }
//
//  Flight::returnSfidaObject($id_sfida);
//});

Flight::route('GET /realtime/sfida/@id_sfida/round/@round_index', function($id_sfida, $round_index) {
  Flight::needsAuth();

  // id_sfidato parte con il tiro

  $sfida = Sfide::find($id_sfida);
  if ($sfida->stato != 1)
    Flight::halt(403, "Don't have permission to get this round");

  $id_user_tiro = $round_index%2 == 0 ? $sfida->id_sfidante : $sfida->id_sfidato;
  $id_user_parata = $round_index%2 == 0 ? $sfida->id_sfidato : $sfida->id_sfidante;
  $index = ceil($round_index/2);

  if ($round_index == "10")
    Flight::finalizeRealtimeSfida($sfida);

  Flight::json(array(
    "round"         => $round_index,
    "match"         => $index,
    $id_user_tiro   => array(
                        "type" => "tiro",
                        "value" => SfideTiri::sfida($id_sfida)->user($id_user_tiro)->get()->first()->{"o{$index}"}
                      ),
    $id_user_parata => array(
                        "type" => "parata",
                        "value" => SfideParate::sfida($id_sfida)->user($id_user_parata)->get()->first()->{"o{$index}"}
                      )
  ));

});

Flight::route('GET /realtime/sfida/@id_sfida/result', function($id_sfida) {
  Flight::needsAuth();
  Flight::needsPermissionToSeeSfida($id_sfida);

  $sfida = Sfide::find($id_sfida);
  if ($sfida == null)
    Flight::halt(404, "Sfida not found");

  if ( $sfida->tipo == "realtime" && $sfida->stato == 2 )
    echo (string)$sfida;
  else
    Flight::halt(403, "Sfida not realtime, not ended yet or dead");
});




// RESOURCES //---------------------------------------------------------------------------------------------------------


Flight::route('GET /realtime/sfida/@id_sfida', function($id_sfida) {
  Flight::needsAuth();

  $sfida = Sfide::find($id_sfida);
  if ($sfida)
    echo (string)$sfida;
  else
    Flight::halt(404, "Sfida not found.");
});

Flight::route('POST /realtime/sfida/@id_sfida', function($id_sfida) {
  Flight::needsAuth();
  Flight::needsPermissionToSfida($id_sfida);

  Sfide::find($id_sfida)->update((array)json_decode(Flight::request()->body));
});

Flight::route('GET /realtime/sfida/@id_sfida/tiri', function($id_sfida) {
  Flight::needsAuth();

  $sfida = Sfide::find($id_sfida);
  if ($sfida)
    echo (string)$sfida->user(Flight::get("auth_id"))->tiri;
  else
    Flight::halt(404, "Tiri not found.");
});

Flight::route('POST /realtime/sfida/@id_sfida/tiri', function($id_sfida) {
  Flight::needsAuth();

  SfideTiri::user(Flight::get("auth_id"))->sfida($id_sfida)->update((array)json_decode(Flight::request()->body));
  Sfide::find($id_sfida)->touch();
});

Flight::route('GET /realtime/sfida/@id_sfida/parate', function($id_sfida) {
  Flight::needsAuth();

  $sfida = Sfide::find($id_sfida);
  if ($sfida)
    echo (string)$sfida->user(Flight::get("auth_id"))->parate;
  else
    Flight::halt(404, "Parate not found.");
});

Flight::route('POST /realtime/sfida/@id_sfida/parate', function($id_sfida) {
  Flight::needsAuth();

  SfideParate::user(Flight::get("auth_id"))->sfida($id_sfida)->update((array)json_decode(Flight::request()->body));
  Sfide::find($id_sfida)->touch();
});