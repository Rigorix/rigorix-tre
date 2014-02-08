<?php

class Messages extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'messaggi';
  protected $primaryKey   = 'id_mes';

  public function scopeReceiver ($query, $id_receiver)
  {
    return $query->where("id_receiver", "=", $id_receiver);
  }

  public function scopeUnread ($query)
  {
    return $query->where("letto", "=", 0);
  }
}
