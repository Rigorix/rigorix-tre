<?php

class Rewards extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'rewards';
  protected $primaryKey   = 'id_reward';

  // Accessors
  public function getIdRewardAttribute ($attr) { return (int)$attr; }
  public function getScoreAttribute ($attr) { return (int)$attr; }
  public function getActiveAttribute ($attr) { return (int)$attr; }

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

  // Accessors
  public function getIdAttribute ($attr) { return (int)$attr; }
  public function getIdRewardAttribute ($attr) { return (int)$attr; }
  public function getIdSfidaAttribute ($attr) { return (int)$attr; }
  public function getIdUtenteAttribute ($attr) { return (int)$attr; }
  public function getSeenAttribute ($attr) { return (int)$attr; }

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

