<?php

class RealtimeRegistrations extends Illuminate\Database\Eloquent\Model {
  protected $table        = 'realtime_registrations';
  protected $primaryKey   = 'id';
  protected $guarded      = array('id');

  // Accessors
  public function getIdUtenteAttribute ($attr) { return (int)$attr; }

  public function scopeUser ($query, $id_utente)
  {
    return $query->where("id_utente", "=", $id_utente);
  }

}