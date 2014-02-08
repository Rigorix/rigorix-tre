<?
class dm_utente extends dm_generic_mysql {

	function dm_utente($db_conn, $db_name, $debug=0) {
		$this->dm_generic_mysql($db_conn, $db_name, $debug);
	}

	function getObjUtenteById($id_utente) {
		$user = $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE id_utente=$id_utente");
		if (is_object($user) && isset($user->id_utente)) {
			return $user;
		} else
			return false;
	}

	function addScore ( $utente, $score ) {
		$act_score = $this->getPunteggioTot ( $utente );
		$obj = array ( "indb_punteggio_totale" => ( $act_score + $score) );
    	$obj_indb = $this->makeInDbObject($obj);
		$this->updateObject('utente', $obj_indb, array( "id_utente" => $utente->id_utente ));
		return $obj;
	}

	function getPunteggioTot ( $utente ) {
		$id_utente = (is_object($utente) && isset($utente->id_utente)) ? $utente->id_utente : $utente;
		$p = $this->getSingleObjectQueryCustom("SELECT punteggio_totale FROM utente WHERE id_utente = " . $id_utente );
		return $p->punteggio_totale;
	}

	function getRegioneByProv( $prov ) {
		if ($prov != '')
			return $this->getSingleObjectQueryCustom("SELECT regione FROM provincia WHERE sigla='$prov'")->regione;
		else
			return false;
	}

	function update_data_activ ( $id_utente )
	{
		$obj = array ( "indb_dta_activ" => "_V_NOW_" );
    	$obj_indb = $this->makeInDbObject($obj);
		$this->updateObject('utente', $obj_indb, array( "id_utente" => $id_utente));
	}

	function get_data_activ ( $id_utente )
	{
		return $this->getSingleObjectQueryCustom("SELECT dta_activ FROM utente WHERE id_utente = $id_utente");
	}

  function getRankingUtenti( $limit = false )
  {
    return $this->getArrayObjectQueryCustom ( "select * from utente where attivo = 1 order by punteggio_totale desc " . (( $limit == false ) ? "" : " limit 0, $limit") );
  }

	function getUtente($username, $pass) {
		$user = $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE username='$username' AND passwd='$pass' and stato = 1");
		if (is_object($user) && isset($user->id_utente)) {
			return $user;
		} else
			return false;
	}

	function getEtaUtente ( $id_utente )
	{
		$dta_nascita = $this->getSingleObjectQueryCustom("SELECT data_nascita FROM utente WHERE id_utente=$id_utente")->data_nascita;
		$year = date ("Y");
		list ($year_b, $month_b, $day_b) = explode ("-", $dta_nascita);
		return ($year - $year_b);
	}

	function getParamUtente ( $id_utente, $param )
	{
		$ret = $this->getSingleObjectQueryCustom("SELECT $param FROM utente WHERE id_utente=$id_utente");
		return $ret->$param;
	}

	function getUtenteByEmail ( $email ) {
		$user = $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE email='$email'");
		if (is_object($user) && isset($user->id_utente))
			return $user;
		else
			return false;
	}

	function getUtenteAttivoByEmail ( $email ) {
		$user = $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE attivo != 0 and email='$email'");
		if (is_object($user) && isset($user->id_utente))
			return $user;
		else
			return false;
	}

	function getUtenteAttivoByUsername ( $username ) {
		$user = $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE attivo != 0 and username='$username'");
		if (is_object($user) && isset($user->id_utente))
			return $user;
		else
			return false;
	}

	function getUtenteByUsername ( $username ) {
		$user = $this->getSingleObjectQueryCustom( "SELECT * FROM utente WHERE username='$username'" );
		if (is_object($user) && isset($user->id_utente))
			return $user;
		else
			return false;
	}

	function getUtenteByMobile ( $mobile ) {
		$user = $this->getSingleObjectQueryCustom( "SELECT * FROM utente WHERE mobile='$mobile'" );
		if (is_object($user) && isset($user->id_utente))
			return $user;
		else
			return false;
	}

	function getObjUtenteByUsername($username) {
		return $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE username='$username'");
	}

	function getIdUtenteByUsername($username)
	{
		$user = $this->getSingleObjectQueryCustom("SELECT id_utente FROM utente WHERE username='$username'");
		if (is_object($user) && isset($user->id_utente)) {
			return $user->id_utente;
		} else {
			return 0;
		}
	}

	function getSubscriptionById ( $id_utente )
	{
		return $this->getSingleObjectQueryCustom ("SELECT * FROM utente WHERE id_utente='$id_utente' and stato = 0");
	}

	function getIdUtenteByEmail($email)
	{
		$user = $this->getSingleObjectQueryCustom("SELECT id_utente FROM utente WHERE email='$email'");
		if (is_object($user) && isset($user->id_utente)) {
			return $user->id_utente;
		} else {
			return 0;
		}
	}

	function getUserList_id_username()
	{
		return $this->getArrayObjectQueryCustom("select id_utente,username from utente order by username asc");
	}

	function getUsernameById($id) {
		$username = $this->getSingleObjectQueryCustom("SELECT username FROM utente WHERE id_utente='$id'");
		if (is_object($username))
			return $username->username;
		else
			return '';
	}

	function getUnsubscribeRequest($id) {
		if ($this->getSingleObjectQueryCustom("SELECT count(*) as tot FROM unsubscribe WHERE id_utente='$id'")->tot > 0) {
			$stato = $this->getSingleObjectQueryCustom("SELECT stato FROM unsubscribe WHERE id_utente='$id'")->stato;
			return $stato;
		} else
			return false;
	}

	function getIdUtenteWeekBest()
      {
          $obj = $this->getArrayObjectQueryCustom("SELECT id_sfidante, id_sfidato, punti_sfidante, punti_sfidato
                                                      FROM sfida
                                                      WHERE dta_conclusa >= DATE_SUB( CURDATE( ) , INTERVAL 7
                                                      DAY )
                                                      AND stato =2");
          $usersPoints = array();

          foreach ( $obj as $sfida ) {
              if ( !array_key_exists($sfida->id_sfidante, $usersPoints))
                  $usersPoints[$sfida->id_sfidante] = 0;
              $usersPoints[$sfida->id_sfidante] += $sfida->punti_sfidante;

              if ( !array_key_exists($sfida->id_sfidato, $usersPoints))
                  $usersPoints[$sfida->id_sfidato] = 0;
              $usersPoints[$sfida->id_sfidato] += $sfida->punti_sfidato;
          }

          $bestUser = 0;
          $bestUserPunteggio = 0;

          foreach ( $usersPoints as $id_utente => $punteggio ) {
              if ( $punteggio > $bestUserPunteggio ) {
                  $bestUser = $id_utente;
                  $bestUserPunteggio = $punteggio;
              }
          }
          return json_encode( array( "id" => $bestUser, "punteggio" => $bestUserPunteggio ));
      }

    function getUserBySocialProvider ($provider, $id) {
    	$user = $this->getSingleObjectQueryCustom("SELECT * FROM utente WHERE social_provider = '$provider' and social_uid = '$id'");
    	if (is_object($user) && isset($user->id_utente))
			return $user;
		else
			return false;
    }

    

	function getDeadStatus($id) {
		if ($this->getUnsubscribeRequest ($id) != false) {
			$unsubcribe = $this->getSingleObjectQueryCustom("SELECT stato FROM unsubscribe WHERE id_utente='$id'")->stato;
			if ($unsubcribe == 1 || $unsubcribe == 2)
				return true;
			else
				return false;
		} else
			return false;
	}

	function getUnsubscriptionByCode( $code )
	{
		$stato = $this->getSingleObjectQueryCustom("SELECT * FROM unsubscribe WHERE conf_code='$code'");
		return $stato;
	}

	function getIdUtenteAnziano ( $id_utente1, $id_utente2 )
	{
		return ( $id_utente1 < $id_utente2 ? $id_utente1 : $id_utente2);
		/*
		$user1 = $this->getObjUtenteById ($id_utente1);
		$user2 = $this->getObjUtenteById ($id_utente2);
		if ( strtotime( $user1->dta_reg ) > strtotime( $user2->dta_reg ) )
			return $user1->id_utente;
		else
			return $user2->id_utente;
		*/
	}

    function getNumUtentiRegistrati ()
    {
        return $this->getSingleObjectQueryCustom ( "select count(*) as tot from utente where attivo = 1")->tot;
    }

	function getCountLastPresences()
	{
		$arr = $this->getSingleObjectQueryCustom("SELECT COUNT(*) AS tt FROM utente WHERE dta_activ>=SUBDATE(NOW(), INTERVAL 300 SECOND)");
		return $arr->tt;
	}

	function getUsernameOnline ()
	{
		return $this->getArrayObjectQueryCustom("SELECT username FROM utente WHERE attivo = 1 and dta_activ>=SUBDATE(NOW(), INTERVAL 3000 SECOND)");
	}
}
?>