<?php

class Users extends Illuminate\Database\Eloquent\Model {
  protected $table        = 'utente';
  protected $primaryKey   = 'id_utente';
  protected $guarded      = array('id', 'id_utente');

//  RELATIONS
  public function sfide ()
  {
    return $this->hasMany('Sfide', 'id_sender', 'id_utente');
  }

  public function messages ()
  {
    return $this->hasMany('Messages', 'id_receiver', 'id_utente');
  }

  public function rewards ()
  {
    return $this->hasManyThrough('Rewards', 'RewardsSfide', 'id_utente', 'id_reward');
  }

  public function sentMessages ()
  {
    return $this->hasMany('Messages', 'id_sender', 'id_utente');
  }

  public function badges()
  {
    $badges = $this->hasManyThrough('Rewards', 'RewardsSfide', 'id_utente', 'id_reward')->get()->filter(function ($reward) {
      if ( $reward->getAttribute("tipo") == "badge")
        return $reward;
    })->values();
    return $badges;
  }

  public function unseenBadges()
  {
    $badges = $this->badges();
    var_dump($badges);
  }



//  METHODS

  public function scopeTop()
  {
//    return $this->where("attivo", "=", 1)->
  }

  public function scopeActive ()
  {
    return $this->whereRaw ("attivo = 1 and dta_activ>=SUBDATE(NOW(), INTERVAL 3000 SECOND)");
  }

  public function scopeFindBySocialId ($query, $uid)
  {
    return $query->where ("social_uid", "=", $uid);
  }

  public function scopeSearchAttribute ($query, $attr, $q)
  {
    return $this->whereRaw("$attr like '%$q%'");
  }


  public function scopeHasReward($query, $id_reward)
  {
    return $query->where("id_reward", "=", $id_reward);
  }

}


class UsersUnsubscribe extends Illuminate\Database\Eloquent\Model {
  protected $table        = 'unsubscribe';
  protected $primaryKey   = 'id_unsubscribe';

  public function scopeUser ($query, $id_utente)
  {
    return $query->where ("id_utente", "=", $id_utente);
  }

}