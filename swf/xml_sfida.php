<?php
error_reporting(0);
ini_set( 'display_errors',0);

$id_sfida = $_GET['id_sfida'];
chdir ("../");
header ("content-type: text/xml");
error_reporting(E_ALL);
require_once ( "classes/core.php" );

$objUtente = $dm_utente->getObjUtenteById ( $user->obj->id_utente );
$objFullSfida = $dm_sfide->getFullObjSfidaById( $id_sfida );
$objUtenteSfidante = $dm_utente->getObjUtenteById ( $objFullSfida->id_sfidante );
$objUtenteSfidato = $dm_utente->getObjUtenteById( $objFullSfida->id_sfidato );

echo '<?xml version="1.0" encoding="UTF-8"?>
<game>
    <settings delayAfterShoot_time="2000" totalShots="10" shooter="player1" firstShooter="player1" keeper="player2" firstKeeper="player2" transitionTime=".6" currentShoot="1" />
    <players>
        <player name="'.$objUtenteSfidante->username.'" number="'.$objUtenteSfidante->numero_maglietta.'" whatcher="'.(($objUtenteSfidante->id_utente==$objUtente->id_utente) ? "true" : "false").'">
            <skin calzini="'.str_replace("#", "0x",$objUtenteSfidante->colore_calzini).'" maglia="'.str_replace("#", "0x",$objUtenteSfidante->colore_maglietta).'" pantaloni="'.str_replace("#", "0x",$objUtenteSfidante->colore_pantaloncini).'" tipoMaglia="'.$objUtenteSfidante->tipo_maglietta.'"/>
            <shoots>
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o1).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o2).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o3).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o4).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o5).'" />
            </shoots>
            <keeps>
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o1).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o2).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o3).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o4).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o5).'" />
            </keeps>
        </player>
        <player name="'.$objUtenteSfidato->username.'" number="'.$objUtenteSfidato->numero_maglietta.'" whatcher="'.(($objUtenteSfidato->id_utente==$objUtente->id_utente) ? "true" : "false").'">
            <skin calzini="'.str_replace("#", "0x",$objUtenteSfidato->colore_calzini).'" maglia="'.str_replace("#", "0x",$objUtenteSfidato->colore_maglietta).'" pantaloni="'.str_replace("#", "0x",$objUtenteSfidato->colore_pantaloncini).'" tipoMaglia="'.$objUtenteSfidato->tipo_maglietta.'"/>
            <shoots>
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o1).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o2).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o3).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o4).'" />
                <shoot target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o5).'" />
            </shoots>
            <keeps>
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o1).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o2).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o3).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o4).'" />
                <keep target="'.$utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o5).'" />
            </keeps>
        </player>
    </players>
</game>';
?>