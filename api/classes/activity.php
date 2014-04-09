<?php

/*
 * Periodic actions - crons
 */
Flight::map("checkPeriodicActions", function () {

  Flight::doBotActions();
  Flight::updateRealtimeMembers();
  Flight::clearDeadRealtimeSfide();

});

Flight::map("clearDeadRealtimeSfide", function() {
  RealtimeSfide::dead(Flight::get("realtime_dead_sfida_time"))->get()->each(function ($deadSfida) {
    RealtimeRegistrations::busyWith($deadSfida->getAttribute("id"))->get()->each(function ($user) {
      $user->update(array(
        "busy_with" => 0
      ));
    });
    $deadSfida->delete();
  });
});

Flight::route("GET /testdead", function() {
  Flight::clearDeadRealtimeSfide();
});

Flight::map("updateRealtimeMembers", function() {
  $unactiveUsers = RealtimeRegistrations::unactive(Flight::get("realtime_members_expire_time"))->get();
  foreach ($unactiveUsers as $unactiveUser) {
    $unactiveUser->delete();
  }
});

Flight::map("doBotActions", function() {
  $sfideBot = Sfide::receivedBy(Flight::get("BOT_ID"))->pending()->get();
  if ($sfideBot->count() > 0) {
    foreach ($sfideBot as $sfidaBot) {

      SfideTiri::create(array(
        "id_sfida"  => $sfidaBot->getAttribute("id_sfida"),
        "id_utente" => Flight::get("BOT_ID"),
        "o1"        => rand(0, 2),
        "o2"        => rand(0, 2),
        "o3"        => rand(0, 2),
        "o4"        => rand(0, 2),
        "o5"        => rand(0, 2)
      ));
      SfideParate::create(array(
        "id_sfida"  => $sfidaBot->id_sfida,
        "id_utente" => Flight::get("BOT_ID"),
        "o1"        => rand(0, 2),
        "o2"        => rand(0, 2),
        "o3"        => rand(0, 2),
        "o4"        => rand(0, 2),
        "o5"        => rand(0, 2)
      ));
      finalizeSfida($sfidaBot);
      $sfidaBot->stato = 2;
      $sfidaBot->save();

    }
  }

  $messaggiBot = Messages::receiver(Flight::get("BOT_ID"))->unread()->get();
  if ($messaggiBot->count() > 0 ) {
    foreach ($messaggiBot as $messaggioBot) {
      Messages::create(array(
        "id_sender"   => Flight::get("BOT_ID"),
        "id_receiver" => $messaggioBot->id_sender,
        "oggetto"     => "RE: " . $messaggioBot->oggetto,
        "testo"       => "hey! sono un automa, una macchina, non ho anima, io non rispondo, io mando solo questo messaggio!",
        "letto"       => 0,
        "report"      => 0
      ));

      $messaggioBot->update(array(
        "letto" => 1
      ));
    }

  }
});

function finalizeSfida ($sfida)
{
  $sfidante = $sfida->sfidante;
  $sfidato = $sfida->sfidato;

  $punti_sfidante = 0;
  $punti_sfidato = 0;

  // Aggiorno la data di chiusura
  $sfida->update(array(
    "dta_conclusa" => new \DateTime,
  ));

  // Trovo il risultato
  $result = getSfidaResult ($sfida);

  // Trovo il vincitore
  $resultArray = explode(",", $result);
  if ((int)$resultArray[0] == (int)$resultArray[1]) {
    $vincitore = 0;
    $punti_sfidante += 1;
    $punti_sfidato += 1;
  } else if ((int)$resultArray[0] > (int)$resultArray[1]) {
    $vincitore = $sfida->getAttribute('id_sfidante');
    $punti_sfidante += 3;
  } else {
    $vincitore = $sfida->getAttribute('id_sfidato');
    $punti_sfidato += 3;
  }

  // Getting rewards
  $rewards = Rewards::all();

  foreach($rewards as $reward) {
    $apply_sfidante = false;
    $apply_sfidato = false;

    $rewardStrategyFile = __DIR__ . "/rewards/{$reward->getAttribute("key_id")}.php";
    if (file_exists($rewardStrategyFile))
      require_once $rewardStrategyFile;

    if ( $apply_sfidante === true ) {
      if ($sfidante->rewards->find($reward->getAttribute("id_reward")) === null) {
        RewardsSfide::create(array(
          'id_reward'   => $reward->getAttribute("id_reward"),
          'id_sfida'    => $sfida->getAttribute("id_sfida"),
          'id_utente'   => $sfidante->getAttribute("id_utente")
        ));
        $punti_sfidante += $reward->getAttribute("score");
      }
    }
    if ( $apply_sfidato === true ) {
      if ($sfidato->rewards->find($reward->getAttribute("id_reward")) === null) {
        RewardsSfide::create(array(
          'id_reward'   => $reward->getAttribute("id_reward"),
          'id_sfida'    => $sfida->getAttribute("id_sfida"),
          'id_utente'   => $sfidato->getAttribute("id_utente")
        ));
        $punti_sfidato += $reward->getAttribute("score");
      }
    }
  }

  $sfidante->update(array(
    "punteggio_totale" => $sfidante->getAttribute("punteggio_totale") + $punti_sfidante
  ));
  $sfidato->update(array(
    "punteggio_totale" => $sfidato->getAttribute("punteggio_totale") + $punti_sfidato
  ));
  $sfida->update(array(
    "punti_sfidante"  => $punti_sfidante,
    "punti_sfidato"   => $punti_sfidato,
    "dta_conclusa"    => new \DateTime,
    "risultato"       => getSfidaResult ($sfida),
    "id_vincitore"    => $vincitore
  ));
  return $sfida;

}

function getSfidaResult ($sfida)
{
  $sfidanteTiri   = $sfida->tiri()->where("id_utente", "=", $sfida->getAttribute('id_sfidante'))->first();
  $sfidanteParate = $sfida->parate()->where("id_utente", "=", $sfida->getAttribute('id_sfidante'))->first();

  $sfidatoTiri    = $sfida->tiri()->where("id_utente", "=", $sfida->getAttribute('id_sfidato'))->first();
  $sfidatoParate  = $sfida->parate()->where("id_utente", "=", $sfida->getAttribute('id_sfidato'))->first();

  $risSfidante = 0;
  $risSfidato = 0;

  for ( $i=1; $i<=5; $i++ ) {
    $k = 'o'.$i;
    if ($sfidanteTiri->{$k} != $sfidatoParate->{$k})
      $risSfidante++;
    if ($sfidatoTiri->{$k} != $sfidanteParate->{$k})
      $risSfidato++;
  }
  return $risSfidante . "," . $risSfidato;
}