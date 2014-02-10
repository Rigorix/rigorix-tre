<?php

class Users extends Illuminate\Database\Eloquent\Model {
  protected $table        = 'utente';
  protected $primaryKey   = 'id_utente';

  public function messages ()
  {
    return $this->hasMany('Messages', 'id_receiver', 'id_utente');
  }

  public function scopeTop()
  {
//    return $this->where("attivo", "=", 1)->
  }

  public function sentMessages ()
  {
    return $this->hasMany('Messages', 'id_sender', 'id_utente');
  }

  public function scopeActive ()
  {
    return $this->whereRaw ("attivo = 1 and dta_activ>=SUBDATE(NOW(), INTERVAL 3000 SECOND)");
  }

  public function rewards ()
  {
    $sfide = Sfide::user($this->id_utente)->get()->each(function($sfida) {
//      $rewards =
    });
//    $rewards = $sfide->rewards;
//    var_dump($rewards);

//    return $this->hasManyThrough('RewardsSfide', 'Sfide', 'id_sfidante', 'id_sfida');
//    var_dump($this);
//    return [];
  }

}


class UsersUnsubscribe extends Illuminate\Database\Eloquent\Model {
  protected $table        = 'unsubscribe';
  protected $primaryKey   = 'id_unsubscribe';
//  protected $guarded      = array('id', 'id_unsubscribe');

  public function scopeUser ($id_utente)
  {
    return $this->where ("id_utente", "=", $id_utente);
  }

}