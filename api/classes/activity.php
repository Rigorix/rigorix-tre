<?php


function finalizeSfida ($id_sfida)
{
  $sfida = Sfide::find($id_sfida)->first();
  $sfidante = $sfida->sfidante;
  $sfidato = $sfida->sfidato;

  $punti_sfidante = 0;
  $punti_sfidato = 0;

  echo "Trovata sfida ({$id_sfida})? {$sfida->count()}\n";
  echo "Sfidante: {$sfidante->getAttribute('username')} ({$sfidante->getKey()})\n";
  echo "Sfidato: {$sfidato->getAttribute('username')} ({$sfidato->getKey()})\n";

  if ( $sfida->count() > 0 ):

    // Aggiorno la data di chiusura
//    $sfida->setAttribute("dta_conlusa", time());
    echo "Conclusa il ". time() . "\n";
    $sfida->update(array(
      "dta_conclusa"    => new \DateTime,
      "stato"           => 2
    ));

    // Trovo il risultato
    $result = getSfidaResult ($sfida);
    echo "Risultato: $result\n";
//    $sfida->setAttribute("risultato", $result);

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

    echo "Vincitore: {$vincitore}\n";
    $sfida->setAttribute("id_vincitore", $vincitore);

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
          echo "apply sfidante {$reward->getAttribute("key_id")} --> {$reward->getAttribute("descrizione")}\n";
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

    echo "Aggiorno i punteggi\n";
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
      "risultato"       => getSfidaResult ($sfida)
    ));
    return true;
  else:
    return false;
  endif;

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