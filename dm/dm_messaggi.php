<?php
class dm_messaggi extends dm_generic_mysql {
	
	function dm_messaggi($db_conn, $db_name, $debug=0) {
		$this->dm_generic_mysql($db_conn, $db_name, $debug);
	}

  function pushMessage($message)
  {
    return $this->insertObject("messaggi", $message);
  }
	
	function getArrObjMessaggiToUserId($id_utente)	{
		$this->cleanStr($id_utente);
		return $this->getArrayObjectQueryCustom("SELECT * FROM messaggi WHERE id_receiver=$id_utente ORDER BY dta_mess DESC");
	}
	
	function getArrObjUnbannedMessaggiToUserId ($id_utente)	{
		$this->cleanStr($id_utente);
		return $this->getArrayObjectQueryCustom( "select * from messaggi where (select count(*) as tot from bannati where id_utente = messaggi.id_receiver and id_bannato = messaggi.id_sender) = 0 and id_receiver = $id_utente order by dta_mess desc" );
	}
	
	function getFilteredUnbannedMessaggi ( $start, $end ) {
		global $user; 
		return $this->getArrayObjectQueryCustom( "select * from messaggi where (select count(*) as tot from bannati where id_utente = messaggi.id_receiver and id_bannato = messaggi.id_sender) = 0 and id_receiver = ".$user->obj->id_utente." order by dta_mess desc limit $start, " . ($end - $start) );
	}

  function getFilteredUserUnbannedMessaggi ( $id_utente, $start, $end ) {
    global $user;
    return $this->getArrayObjectQueryCustom( "select * from messaggi where (select count(*) as tot from bannati where id_utente = messaggi.id_receiver and id_bannato = messaggi.id_sender) = 0 and id_receiver = ".$id_utente." order by dta_mess desc limit $start, " . ($end - $start) );
  }

	function getUsernameSenderByIdMess ($id_mess) 
	{
		return $this->getSingleObjectQueryCustom("select (select username from utente where id_utente = messaggi.id_sender) as username from messaggi where id_mess = $id_mess")->username;
	}
	
	function getSubjectByIdMess ($id_mess) 
	{
		return $this->getSingleObjectQueryCustom("select oggetto from messaggi where id_mess = $id_mess")->oggetto;
	}
	
	function getSenderByIdMess ($id_mess)
	{
		return $this->getSingleObjectQueryCustom("select id_sender from messaggi where id_mess = $id_mess")->id_sender;
	}
	
	function deleteMessageById ($id_mess) 
	{
		$this->executeQuery("delete from messaggi where id_mess = $id_mess");
		return true;
	}
	
	function getArrObjMessaggiUnread($id_utente){
		$this->cleanStr($id_utente);
		return $this->getArrayObjectQueryCustom("SELECT * FROM messaggi WHERE id_receiver=$id_utente and letto = 0 ORDER BY dta_mess DESC");
	}
	
	function getArrObjUnbannedMessaggiUnread($id_utente){
		$this->cleanStr($id_utente);
		return $this->getArrayObjectQueryCustom( "SELECT * FROM messaggi WHERE (select count(*) as tot from bannati where id_utente = messaggi.id_receiver and id_bannato = messaggi.id_sender) = 0 and id_receiver=$id_utente and letto = 0 ORDER BY dta_mess DESC" );
	}
	
	function getMessaggioById($id_mess){
		return $this->getSingleObjectQueryCustom("SELECT * FROM messaggi WHERE id_mess=$id_mess");
	}
	
	function markAsReadById($id_mess){
		return $this->executeQuery ("update messaggi set letto = 1 where id_mess=$id_mess");
	}
	
	function getCountUnreadMessages ($id_utente) {
		$this->cleanStr($id_utente);
		$ret = $this->getSingleObjectQueryCustom("SELECT count(*) as tot FROM messaggi WHERE id_receiver=$id_utente and letto = 0");
		return $ret->tot;
	}
	
	function getCountMessages ($id_utente) {
		$this->cleanStr($id_utente);
		$ret = $this->getSingleObjectQueryCustom("SELECT count(*) as tot FROM messaggi WHERE id_receiver=$id_utente");
		return $ret->tot;
	}
	
	function getCountUnbannedMessages ($id_utente) {
		$this->cleanStr($id_utente);
		$ret = $this->getSingleObjectQueryCustom("SELECT count(*) as tot FROM messaggi WHERE (select count(*) as tot from bannati where id_utente = messaggi.id_receiver and id_bannato = messaggi.id_sender) = 0 and  id_receiver=$id_utente");
		return $ret->tot;
	}
	
	function getArrMessaggiOrderedByDta()	{
		return $this->getArrayObjectQueryCustom("SELECT * FROM messaggi ORDER BY dta_mess DESC limit 5000");
	}
	
	function getArrReportedMessaggiOrderedByDta()	{
		return $this->getArrayObjectQueryCustom("SELECT * FROM messaggi WHERE report = 1 ORDER BY dta_mess DESC");
	}
	
	function removeMessaggio($id_messaggio)	{
		$this->cleanStr($id_messaggio);
		$this->executeQuery("DELETE FROM messaggi WHERE id_mess=$id_messaggio");
	}
	
	function deleteMessInArrayId($arr_id)	{
		$str_in = "0";
		foreach ($arr_id as $key=>$val) {
			$str_in .= ",$val";
		}
		$this->executeQuery("DELETE FROM messaggi WHERE id_mess IN ($str_in)");
	}
	
	function getObjMessaggioById($id_messaggio)	{
		$this->cleanStr($id_messaggio);
		return $this->getSingleObjectQueryCustom("SELECT * FROM messaggi WHERE id_mess=$id_messaggio");
	}
	
	function getIdMessBeforeId($id_utente, $id_curr)	{
		$this->cleanStr($id_utente);
		$this->cleanStr($id_curr);
		$arr = $this->getArrayObjectQueryCustom("SELECT * FROM messaggi WHERE id_receiver=$id_utente AND dta_mess<(SELECT dta_mess FROM messaggi WHERE id_mess=$id_curr) ORDER BY dta_mess DESC");
		if (count($arr)>0) {
			return $arr[0]->id_mess;
		} else {
			return 0;
		}
	}
	
	function getIdMessAfterId($id_utente, $id_curr)	{
		$this->cleanStr($id_utente);
		$this->cleanStr($id_curr);
		$arr = $this->getArrayObjectQueryCustom("SELECT * FROM messaggi WHERE id_receiver=$id_utente AND dta_mess>(SELECT dta_mess FROM messaggi WHERE id_mess=$id_curr) ORDER BY dta_mess ASC");
		if (count($arr)>0)
		{
			return $arr[0]->id_mess;
		} else {
			return 0;
		}
	}
	
	function setMessRead($id_mess)	{
		$this->cleanStr($id_mess);
		$this->executeQuery("UPDATE messaggi SET letto=1 WHERE id_mess=$id_mess");
	}
	
	function reportMessById($id_mess) {
		$this->cleanStr($id_mess);
		$this->executeQuery("UPDATE messaggi SET report=1 WHERE id_mess=$id_mess");	
		return "OK";
	}

}
?>