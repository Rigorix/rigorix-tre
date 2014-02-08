<?php

class Users extends Illuminate\Database\Eloquent\Model {
  protected $table        = 'utente';
  protected $primaryKey   = 'id_utente';

  public function messages ()
  {
    return $this->hasMany('Messages', 'id_receiver', 'id_utente');
  }

  public function sentMessages ()
  {
    return $this->hasMany('Messages', 'id_sender', 'id_utente');
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