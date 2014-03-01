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

  public function reward ()
  {
    return $this->hasOne('Rewards', 'id_reward');
  }

  public function scopeUnseen ($query)
  {
    return $query->whereRaw("seen = 0");
  }

  public function scopeBadge ($query)
  {
    return $query->whereRaw("tipo = 'badge'");
  }

  public function scopeUser ($query, $id_utente)
  {
    return $query->whereRaw("id_utente = $id_utente");
  }

  public function users()
  {
    return $this->belongsToMany('Users', 'id_utente');
  }
}

