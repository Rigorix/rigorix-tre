<?php

Flight::map("membersListChanged", function($count) {
  return $count != RealtimeRegistrations::all()->count();
});


Flight::route('POST /realtime/register/@id_utente', function($id_utente) {
  Flight::needsAuth();

  if (RealtimeRegistrations::user($id_utente)->get()->isEmpty())
    RealtimeRegistrations::create(array(
      "id_utente" => $id_utente
    ));

  echo (string)RealtimeRegistrations::all();
});

Flight::route('GET /realtime/members', function() {
  Flight::needsAuth();

  $userCount = RealtimeRegistrations::all()->count();
  $attempts = 0;
  $maxAttempts = 20;

  while (!Flight::membersListChanged($userCount) && $maxAttempts > $attempts) {
    $attempts++;
    sleep(3);
  }

  echo (string)RealtimeRegistrations::all();
});
