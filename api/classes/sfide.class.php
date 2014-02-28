<?php

class Sfide extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'sfida';
  protected $primaryKey   = 'id_sfida';
  protected $guarded      = array("id_sfida");

  public function tiri()
  {
    return $this->hasMany('SfideTiri', 'id_sfida');
  }

  public function sfidato()
  {
    return $this->hasOne('Users', 'id_utente', 'id_sfidato');
  }

  public function sfidante()
  {
    return $this->hasOne('Users', 'id_utente', 'id_sfidante');
  }

  public function parate()
  {
    return $this->hasMany('SfideParate', 'id_sfida');
  }

  public function rewards()
  {
    return $this->hasManyThrough('Rewards', 'RewardsSfide', 'id_sfida', 'id_reward');
  }

  public function scopeUser($query, $id_utente)
  {
    return $query->where("id_sfidante", "=", $id_utente)->orWhere("id_sfidato", "=", $id_utente);
  }

  public function scopeBetween($query, $id_utente_f, $id_utente_s)
  {
    return $query->whereRaw("(id_sfidante = $id_utente_f and id_sfidato = $id_utente_s) or (id_sfidante = $id_utente_s and id_sfidato = $id_utente_f)");
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
    return $query->whereRaw("dta_conclusa >= CURDATE()");
  }

  public function scopeDone($query)
  {
    return $query->whereStato(2);
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

}



class SfideTiri extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'tiri';
  protected $primaryKey   = 'id_tiri';
  protected $fillable     = array("id_sfida", "id_utente", "o1", "o2","o3", "o4","o5");
  protected $guarded      = array("id_tiri");
  public $timestamps      = false;

  public function scopeUser ($query, $id_utente)
  {
    return $query->where("id_utente", "=", $id_utente);
  }

  public function scopeSfida ($query, $id_sfida)
  {
    return $query->where("id_sfida", "=", $id_sfida);
  }



}



class SfideParate extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'parate';
  protected $primaryKey   = 'id_parate';
  protected $fillable     = array("id_sfida", "id_utente", "o1", "o2","o3", "o4","o5");
  protected $guarded      = array("id_parate");
  public $timestamps      = false;

  public function scopeUser ($query, $id_utente)
  {
    return $query->where("id_utente", "=", $id_utente);
  }

  public function scopeSfida ($query, $id_sfida)
  {
    return $query->where("id_sfida", "=", $id_sfida);
  }
}