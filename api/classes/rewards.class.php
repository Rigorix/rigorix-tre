<?php

class Rewards extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'rewards';
  protected $primaryKey   = 'id_reward';

  public function scopeBadges ($query)
  {
    return $query->where("tipo", "=", "badge");
  }

  public function scopeActive ($query)
  {
    return $query->where("active", "=", 1);
  }

}

class RewardsSfide extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'sfida_reward';
  protected $primaryKey   = 'id_reward';
  protected $guarded      = array('id');

  public function scopeUser ($query, $id_utente)
  {
    return $query->where("id_utente", "=", $id_utente);
  }

  public function users()
  {
    return $this->belongsToMany('Users', 'id_utente');
  }
}

