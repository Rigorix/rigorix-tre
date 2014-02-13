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
  protected $table        = 'sfide_rewards';
  protected $primaryKey   = 'id_sfida_reward';

  public function scopeUser ($query, $id_utente)
  {
    return $query->where("id_utente", "=", $id_utente);
  }

  public function users()
  {
    return $this->belongsToMany('Users');
  }
}

