<?php

class ReportManager {

   function ReportManager($pre)
   {
      $this->pre           = $pre;
      $this->usciteKey     = 12;
      $this->entrateKey    = 11;
      $this->nonSaldatoKey = 7;
      $this->saldatoKey    = 6;
      $this->rappDiretto   = 1;
      $this->rappFiltrato  = 2;
      $this->sep           = '***';
	  // Rapporto->tipoRapporto = 1: diretto. 2: filtrato dal rapporto
   }
   
   function createPersoneObj()
   {
      $res = array();
      $Persone = $this->getPersone();
      foreach($Persone as $Persona) {
         $res[$Persona['id']] = array(
            'Saldo'     => 0,
            'ID'        => $Persona['id']
         );
		 $res[$Persona['id']]['Debiti'] = array();
      }
      return $res;
   }
   
   function getMultipleValue($multi)
   {
      if(strpos($multi, $this->sep) != -1)
         return explode($this->sep, $multi);
      else
         return $multi;
   }
   
   function getPersone()
   {
      global $_DB;
      $res = array();
      
      $query = "select * from ".$this->pre."_persone";
      $result = mysql_query($query, $_DB->Conn);
      while($row = mysql_fetch_array($result)) {
         array_push($res, $row);
      }
      return $res;
   }
   
   function getNomePersonaById($id)
   {
      global $_DB;
      
      $query = "select nome from ".$this->pre."_persone where id = " . $id;
      $result = mysql_query($query, $_DB->Conn);
      $row = mysql_fetch_array($result);
      return $row['nome'];
   }
   
   function getIdPersoneAttive()
   {
      global $_DB;
      $res = array();
      
      $query = "select ".$this->pre."_persone.id from ".$this->pre."_movimenti, ".$this->pre."_persone where ".$this->pre."_movimenti.personaIniziale = ".$this->pre."_persone.id group by ".$this->pre."_movimenti.personaIniziale";
      $result = mysql_query($query, $_DB->Conn);
      while($row = mysql_fetch_array($result)) {
         array_push($res, $row);
      }
      return $res;
   }
   
   function getEntrateById($id)
   {
      global $_DB;
      
      $query = "select nome from ".$this->pre."_persone where id = " . $id;
      $result = mysql_query($query, $_DB->Conn);
      $row = mysql_fetch_array($result);
      return $row['nome'];
   }
   
   function getPersonaFinaleById($id)
   {
      global $_DB;
      
      $query = "select * from ".$this->pre."_personefinali where id = '" . $id . "'";
      $result = mysql_query($query, $_DB->Conn);
      $row = mysql_fetch_array($result);
      return $row;
   }
   
   function getRapportoById($id)
   {
      global $_DB;
      
      $query = "select * from ".$this->pre."_rapporti where id = " . $id;
      $result = mysql_query($query, $_DB->Conn);
      $row = mysql_fetch_array($result);
      return $row;
   }
   
   function getMovimenti()
   {
      global $_DB;
      $res = array();
      
      $query = "select * from ".$this->pre."_movimenti where stato = '".$this->nonSaldatoKey."'";
      $result = mysql_query($query, $_DB->Conn);
      while($row = mysql_fetch_array($result)) {
         array_push($res, $row);
      }
      return $res;
   }
   
}

?>