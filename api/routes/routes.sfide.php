<?php

Flight::route('GET /sfida/@id_sfida/@id_utente/xml', function($id_sfida, $id_utente) {
  header('Content-type: text/xml');

  $sfida = Sfide::find($id_sfida);
  $sfidante = Users::find($sfida->id_sfidante);
  $sfidanteTiri = SfideTiri::sfida($id_sfida)->user($sfida->id_sfidante)->first();
  $sfidanteParate = SfideParate::sfida($id_sfida)->user($sfida->id_sfidante)->first();

  $sfidato = Users::find($sfida->id_sfidato);
  $sfidatoTiri = SfideTiri::sfida($id_sfida)->user($sfida->id_sfidato)->first();
  $sfidatoParate = SfideParate::sfida($id_sfida)->user($sfida->id_sfidato)->first();

  echo '<?xml version="1.0" encoding="UTF-8"?>
    <game>
        <settings delayAfterShoot_time="2000" totalShots="10" shooter="player1" firstShooter="player1" keeper="player2" firstKeeper="player2" transitionTime=".6" currentShoot="1" />
        <players>
            <player name="'.$sfidante->username.'" number="'.$sfidante->numero_maglietta.'" whatcher="'.(($sfidante->id_utente==$id_utente) ? "true" : "false").'">
                <skin calzini="'.str_replace("#", "0x",$sfidante->colore_calzini).'" maglia="'.str_replace("#", "0x",$sfidante->colore_maglietta).'" pantaloni="'.str_replace("#", "0x",$sfidante->colore_pantaloncini).'" tipoMaglia="'.$sfidante->tipo_maglietta.'"/>
                <shoots>
                    <shoot target="'.$sfidanteTiri->o1.'" />
                    <shoot target="'.$sfidanteTiri->o2.'" />
                    <shoot target="'.$sfidanteTiri->o3.'" />
                    <shoot target="'.$sfidanteTiri->o4.'" />
                    <shoot target="'.$sfidanteTiri->o5.'" />
                </shoots>
                <keeps>
                    <keep target="'.$sfidanteParate->o1.'" />
                    <keep target="'.$sfidanteParate->o2.'" />
                    <keep target="'.$sfidanteParate->o3.'" />
                    <keep target="'.$sfidanteParate->o4.'" />
                    <keep target="'.$sfidanteParate->o5.'" />
                </keeps>
            </player>
            <player name="'.$sfidato->username.'" number="'.$sfidato->numero_maglietta.'" whatcher="'.(($sfidato->id_utente==$objUtente->id_utente) ? "true" : "false").'">
                <skin calzini="'.str_replace("#", "0x",$sfidato->colore_calzini).'" maglia="'.str_replace("#", "0x",$sfidato->colore_maglietta).'" pantaloni="'.str_replace("#", "0x",$sfidato->colore_pantaloncini).'" tipoMaglia="'.$sfidato->tipo_maglietta.'"/>
                <shoots>
                    <shoot target="'.$sfidatoTiri->o1.'" />
                    <shoot target="'.$sfidatoTiri->o2.'" />
                    <shoot target="'.$sfidatoTiri->o3.'" />
                    <shoot target="'.$sfidatoTiri->o4.'" />
                    <shoot target="'.$sfidatoTiri->o5.'" />
                </shoots>
                <keeps>
                    <keep target="'.$sfidatoParate->o1.'" />
                    <keep target="'.$sfidatoParate->o2.'" />
                    <keep target="'.$sfidatoParate->o3.'" />
                    <keep target="'.$sfidatoParate->o4.'" />
                    <keep target="'.$sfidatoParate->o5.'" />
                </keeps>
            </player>
        </players>
    </game>';
});

Flight::route('GET /sfide/archivio/@id_utente', function($id_utente) {
  echo (string)Sfide::done()->user($id_utente)->get();
});

Flight::route('GET /sfide/pending/@id_utente', function($id_utente) {
  echo (string)Sfide::pending()->user($id_utente)->get();
});

Flight::route('POST /sfide/set', function() {
  $data = getParams();
  $sfidaMatrix = json_decode($data->sfida_matrix);
  $sfidaObject = $data->sfida;

  $risposta = true;

  if ( !isset($sfidaObject->id_sfida) || $sfidaObject->id_sfida === false ):
    $sfida = Sfide::create(array(
      "id_sfidante" => intval($sfidaObject->id_sfidante),
      "id_sfidato"  => intval($sfidaObject->id_avversario)
    ));
    $id_sfida = $sfida->getAttribute("id_sfida");
    $risposta = false;
  else:
    $id_sfida = $sfidaObject->id_sfida;
  endif;

  $tiri = SfideTiri::create(array(
    "id_sfida"  => $id_sfida,
    "id_utente" => $sfidaObject->id_utente ? $sfidaObject->id_utente : $sfidaObject->id_sfidante,
    "o1"        => $sfidaMatrix->tiro1 + 1,
    "o2"        => $sfidaMatrix->tiro1 + 1,
    "o3"        => $sfidaMatrix->tiro1 + 1,
    "o4"        => $sfidaMatrix->tiro1 + 1,
    "o5"        => $sfidaMatrix->tiro1 + 1
  ));

  $parate = SfideParate::create(array(
    "id_sfida"  => $id_sfida,
    "id_utente" => $sfidaObject->id_utente ? $sfidaObject->id_utente : $sfidaObject->id_sfidante,
    "o1"        => $sfidaMatrix->parata1 + 1,
    "o2"        => $sfidaMatrix->parata1 + 1,
    "o3"        => $sfidaMatrix->parata1 + 1,
    "o4"        => $sfidaMatrix->parata1 + 1,
    "o5"        => $sfidaMatrix->parata1 + 1
  ));

  if ( $tiri->getAttribute("id_tiri") && $parate->getAttribute("id_parate") ):
    $sfidaUpdate = Sfide::find($id_sfida);
    $sfidaUpdate->stato = $risposta == false ? 1 : 2;
    $sfidaUpdate->save();

    echo (string)$sfidaUpdate;
  else:
    Flight::error();
  endif;
});

Flight::route('GET /sfide/@id_sfida', function($id_sfida) {
  $sfida = Sfide::find($id_sfida);
  if ( $sfida->count() > 0)
    echo Sfide::find($id_sfida)->toJson();
  else
    Flight::halt(404, "La sfida non esiste");
});

Flight::route('GET /sfide/@id_sfida/rewards/@id_utente', function($id_sfida, $id_utente) {
  echo (string)RewardsSfide::whereRaw("id_utente = $id_utente and id_sfida = $id_sfida")->get()->load('reward');
});

Flight::route('POST /sfide/@id_sfida/finalize', function ($id_sfida) {
  finalizeSfida($id_sfida);
//  Flight::halt(200, "vai con dios");
});