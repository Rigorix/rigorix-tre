<?php

class Sfide extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'sfida';
  protected $primaryKey   = 'id_sfida';

  public function scopeUser($query, $id_utente)
  {
    $this->id_utente = $id_utente;
    return $query->whereRaw("id_sfidante = $id_utente or id_sfidato = $id_utente");
  }

  public function scopeLastWeek($query)
  {
    return $query->whereRaw("dta_conclusa >= DATE_SUB( CURDATE() , INTERVAL 7 DAY )");
  }

  public function scopeLastMonth($query)
  {
    return $query->whereRaw("MONTH( dta_conclusa ) = Month(NOW())");
  }

  public function scopeToday($query)
  {
    return $query->where("dta_conclusa", ">=", "CURDATE()");
  }

  public function scopeDone($query)
  {
    return $query->where("stato", "=", "2");
  }

  public function scopePending($query)
  {
    return $query->where("stato", "<", "2");
  }

  public function scopeReceivedBy($query, $id_utente)
  {
    $this->id_utente = $id_utente;
    return $query->whereRaw("id_sfidato = $id_utente");
  }

  public function scopeUnplayed($query)
  {
    return $query->where("stato", "<", 2);
  }

  public function sentMessages ()
  {
    return $this->hasMany('Messages', 'id_sender', 'id_utente');
  }
}