<?php

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




//Flight::route('GET /realtime/members', function() {
//  Flight::needsAuth();
//
//  $userCount = RealtimeRegistrations::all()->count();
//  $attempts = 0;
//  $maxAttempts = 20;
//
//  while (!Flight::membersListChanged($userCount) && $maxAttempts > $attempts) {
//    $attempts++;
//    sleep(3);
//  }
//
//  echo (string)RealtimeRegistrations::all();
//});

Flight::route('GET /realtime/member', function() {
  Flight::needsAuth();

  $userCount = RealtimeRegistrations::all()->count();
  $lastMemberUpdate = RealtimeRegistrations::user(Flight::get("auth_id"))->first()->updated_at;

  while (!Flight::membersListChanged($userCount) && !Flight::memberHasUpdated(Flight::get("auth_id"), $lastMemberUpdate)) {
    sleep(3);
  }

  Flight::returnRealtimeResponse();
});

Flight::route('GET /realtime/sfida/@id_avversario', function($id_avversario) {
  Flight::needsAuth();

  echo (string)RealtimeSfide::between(Flight::get("auth_id"),$id_avversario)->first();
});

Flight::route('GET /realtime/game/@id_sfida', function($id_sfida) {
  Flight::needsAuth();

  $sfida = RealtimeSfide::find($id_sfida);

});




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
    RealtimeRegistrations::user(Flight::get("auth_id"))->touch();
  }
});

Flight::route('POST /realtime/accept/@id_avversario', function($id_avversario) {
  Flight::needsAuth();

  $loggedUser = RealtimeRegistrations::user(Flight::get("auth_id"))->first();
  if ($loggedUser->has_request_from == $id_avversario) {

    $loggedUser->update(array(
      "busy_with"         => $id_avversario,
      "has_request_from"  => 0
    ));
    RealtimeRegistrations::user($id_avversario)->first()->update(array(
      "busy_with"         => Flight::get("auth_id"),
      "has_request_from"  => 0
    ));

    $newSfida = RealtimeSfide::create(array(
      "id_sfidante"   => $id_avversario,
      "id_sfidato"    => Flight::get("auth_id"),
      "stato"         => 0
    ));

    Flight::json(array(
      "id_sfida" => $newSfida->getAttribute("id")
    ));
  }

});