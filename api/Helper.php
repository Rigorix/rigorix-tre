<?php

class ApiHelper {

  var $dm_utente, $dm_messaggi, $dm_sfide, $dm_rewards;

  function ApiHelper ( $dm_utente, $dm_messaggi, $dm_sfide, $dm_rewards )
  {
    $this->dm_utente    = $dm_utente;
    $this->dm_messaggi  = $dm_messaggi;
    $this->dm_sfide     = $dm_sfide;
    $this->dm_rewards   = $dm_rewards;
  }

  function getUserObjectById( $id_utente )
  {
    $UserObject = $this->dm_utente->getObjUtenteById($id_utente);
    $UserObject->messages = $this->dm_messaggi->getArrObjMessaggiUnread ($id_utente);
    $UserObject->badges = $this->dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
    $UserObject->sfide_da_giocare = $this->dm_sfide->getSfideDaGiocareByUtente ( $id_utente );
    $UserObject->rewards = $this->dm_rewards->getRewardsObjectByIdUtente ( $id_utente );
    $UserObject->picture = sanitizeUserPicture($UserObject->picture);

    return $UserObject;
  }

}
?>