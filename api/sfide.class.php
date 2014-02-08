<?php

class Sfide extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'sfida';
  protected $primaryKey   = 'id_sfida';

  public function scopeUser($query, $id_utente)
  {
    return $query->whereRaw("id_sfidante = $id_utente or id_sfidato = $id_utente");
  }



  public function sentMessages ()
  {
    return $this->hasMany('Messages', 'id_sender', 'id_utente');
  }
}