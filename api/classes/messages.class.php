<?php

class Messages extends  Illuminate\Database\Eloquent\Model {
  protected $table        = 'messaggi';
  protected $primaryKey   = 'id_mess';
  protected $guarded      = array("id_mess");

  // Accessors
  public function getIdMessAttribute ($attr) { return (int)$attr; }
  public function getIdSenderAttribute ($attr) { return (int)$attr; }
  public function getIdReceiverAttribute ($attr) { return (int)$attr; }
  public function getLettoAttribute ($attr) { return (int)$attr; }
  public function getIdReportAttribute ($attr) { return (int)$attr; }


  // Methods

  public function scopeReceiver ($query, $id_receiver)
  {
    return $query->where("id_receiver", "=", $id_receiver);
  }

  public function scopeUnread ($query)
  {
    return $query->where("letto", "=", 0);
  }
}
