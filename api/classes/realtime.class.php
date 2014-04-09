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

  public function scopeUnactive ($query, $activityPeriod)
  {
    return $query->whereRaw("updated_at<=SUBDATE(NOW(), INTERVAL {$activityPeriod} SECOND)");
  }

  public function scopeBusyWith($query, $id_sfida)
  {
    return $query->where("busy_with", "=", $id_sfida);
  }

}


class RealtimeSfide extends Illuminate\Database\Eloquent\Model {
  protected $table        = 'realtime_sfide';
  protected $primaryKey   = 'id';
  protected $guarded      = array('id');
  public $timestamps      = true;

  // Accessors
  public function getIdSfidanteAttribute ($attr) { return (int)$attr; }
  public function getIdSfidatoAttribute ($attr) { return (int)$attr; }
  public function getStatoAttribute ($attr) { return (int)$attr; }

  public function scopeBetween($query, $id1, $id2)
  {
    return $query->whereRaw("(id_sfidante = $id1 and id_sfidato = $id2) or (id_sfidante = $id2 and id_sfidato = $id1)");
  }

  public function scopeDead($query, $deadPeriod)
  {
    return $query->whereRaw("stato < 2 and created_at < SUBDATE(NOW(), INTERVAL {$deadPeriod} SECOND)");
  }


}