<?php

/*
 * 	USER obj model
 *  {	[DATABASE]
 *  	id_utente,
 *  	username,
 *  	passwd,
 *  	picture,
 *  	mobile,
 *  	nome,
 *  	cognome,
 *  	data_nascita,
 *  	citta,
 *  	prov,
 *  	nazione,
 *  	email,
 *  	codfisc,
 *  	punteggio,
 *  	punteggio_totale,
 *  	ranking,
 *  	special_ranking,
 *  	gold,
 *  	dollarix,
 *  	id_invitante,
 *  	dta_reg,
 *  	stato,
 *  	colore_maglietta,
 *  	tipo_maglietta,
 *  	numero_maglietta,
 *  	colore_pantaloncini,
 *  	colore_calzini,
 *  	dta_activ,
 *  	sesso,
 *  	hoppy,
 *  	frase,
 *  	giocatore,
 *  	squadra,
 *  	tipo_alert,
 *  	ip,
 *  	ip_last_change,
 *  	last_login,
 *
 *  	[PHP]
 *  	messaggi,
 *  	data_nascita_ita,
 *  	regione,
 *  	has_torneo_attivo,
 *  	my_torneo,
 *  	eta,
 *  	unsubscribing,
 *  	inviti
 *  	tornei (tornei in cui sono iscritto)
 *  	dead,
 *  }
 *
 *  USER ACTIONS
 *
 *  - UPDATE_USER_FIELDS: Manda l'utente ad aggiornare i dati.
 *
 */

class user extends dm_generic_mysql {

	var $is_logged 			= false;
	var $is_active 			= false;
	var $obj 				= false;
	var $user_list			= array();
	var $actions			= array();
	var $mandatory_fields	= array(
		"data_nascita"	=> "0000-00-00",
		"prov"			=> "",
		"sesso"			=> ""
	);

	function user( )
	{
		global $core, $dm_utente;
		if ($_SESSION['rigorix']['user'] !== false) {
			$this->is_logged = true;
			$this->obj = $this->createUserObject( $_SESSION['rigorix']['user'] );
			$this->checkUserMandatoryData ();
		}
		$this->user_list = $dm_utente->getUserList_id_username ();
	}

	function logged_user_checkup ()
	{
		global $dm_utente;

		// L'utente loggato esiste?
		if ( $dm_utente->getObjUtenteById ( $this->obj->id_utente ) === false ):
			return false;
		else:
			return true;
		endif;
	}

	function is_active ()
	{
		if ( $this->is_logged == true ) {
			return ( isset( $this->obj) && $this->obj->attivo == 1 );
		} else
			return false;
	}

	function is_user_active ( $id_utente )
	{
		global $dm_utente;

		$attivo = $dm_utente->getParamUtente ( $id_utente, "attivo" );
		return $attivo == 1;
	}

	function update_user_object ()
	{
		global $dm_utente;
		$userObj = $dm_utente->getUtenteByUsername ($this->obj->username);
		$this->obj = $this->createUserObject( $userObj );
		$_SESSION['rigorix']['user'] = $this->obj;
	}

	function checkUserMandatoryData()
	{
		foreach ( $this->mandatory_fields as $field => $null_value) {
			if ( !isset ($this->obj->$field) || $this->obj->$field == $null_value )
				array_push( $this->actions, "UPDATE_USER_FIELDS");
		}
	}

	function hasAction ( $key )
	{
		return ( in_array ($key, $this->actions) );
	}

	function get_username ( $ref )
	{
		// Questa funzione stampa lo username a seconda del contesto in cui viene chiamata
		global $dm_utente;
		$username = '';

		if ( $ref == 0) {
			// Rigorix
			return '<span class="rigorix-username">Rigorix Staff</span>';
		} else {
			if ( is_numeric($ref) )
				$player = $dm_utente->getObjUtenteById ($ref);
			else
				$player = $dm_utente->getObjUtenteByUsername ($ref);
			if ( is_object ($player) ) {
				$username = $player->username;

				if ( strpos($username, DELETED_USER_PREFIX) !== false ) {
					// Questo utente è stato cancellato
					$username = '<span class="deleted-user" title="ATTENZIONE!! Utente disiscritto">' . str_replace(DELETED_USER_PREFIX, "", $username) . '</span>';
				}

				if ( $this->is_user_sfidabile ($player))
					$username = '<a class="user-link" name="user-link" id_utente="'.$player->id_utente.'">'.$username.'</a>';
				else
					$username = '<strike>'.$username.'</strike>';
			} else
				$username = '[utente cancellato]';
			return $username;
		}
	}

    function exists ( $id_utente )
    {
        global $dm_utente;
        return $dm_utente->getObjUtenteById ($id_utente);
    }

	function print_username ( $ref )
	{
		// Questa funzione stampa lo username a seconda del contesto in cui viene chiamata
		echo $this->get_username ( $ref );
	}

	function print_user_list ( $user_list )
	{
		echo '<ul class="list-element user-list">';
		for ( $i=0; $i<count($user_list); $i++ ) {
		    $player = $user_list[$i];
			$this->print_user_row($player);
		}
		echo '</ul>';
	}

	function print_user_row ( $player )
	{
		if ( is_numeric($player))
			$player = $this->get_user_by_id ($player);
		if ( is_string($player))
			$player = $this->get_user_by_username($player);
		if ( is_object($player) && !isset ($player->id_utente )) {
			foreach ( $player as $prop => $val ) {
				if ( $prop == "username") {
					$player = $this->get_user_by_username($val);
					break;
				}
				if ( $prop == "id_utente") {
					$player = $this->get_user_by_id($val);
					break;
				}
			}
		}

		if ( isset ( $player->id_utente ) ) {
		    $username = $this->get_smart_username ($player->id_utente, true);

			if ( $this->is_logged ):
				if ( $player->id_utente != $this->obj->id_utente ):
			    	echo '<li><div class="user-link" name="user-link" id_utente="'.$player->id_utente.'">'.$username.' '. $this->print_user_score ($player).'</div></li>';
				else:
			    	echo '<li class="its-me"><em>'.$username.'</em>'. $this->print_user_score ($player).'</li>';
				endif;
			else:
				echo '<li>'.$username.'</li>';
			endif;
		}
	}

	function print_smart_username ( $ref )
	{
        $user = $this->createUserObject( $ref );
        print ($user->username);
	}

	function print_user_score ( $UserObject )
	{
		$ret = '<span class="punteggio-utente-container label">';
		$ret .= $UserObject->punteggio_totale;
		$ret .= '</span>';
		return $ret;
	}

	function get_smart_username ( $ref, $thumb = true )
	{
		global $core;

        $user = $this->createUserObject( $ref );
        $da_sfidare = $this->is_user_da_sfidare ( $user );
        $return = "";
        if ( $thumb === true ) {
			$picture_uri = $this->get_user_picture_uri ($user);
        	$return = '<img src="'. $picture_uri . '" class="username-picture" align="middle" />';
        } else
        	$return = '<img src="/i/default-user-picture.png" align="middle" class="username-picture" />';
        $return .= '<span class="ui-username">' . $user->username . '</span>';
        if ($da_sfidare !== false)
            $return = '<a class="user-sfida-attiva" name="lancia-sfida-utente" id_sfida="'.$da_sfidare.'">' . $return . '</a>';
        return $return;
	}

	function get_user_picture_uri ( $UserObject )
	{
		global $core;

		if ( $UserObject->picture == "" )
			$picture_uri = '/i/default-user-picture.png';
		else if ( strpos($UserObject->picture, "http") === 0 )
			$picture_uri = $UserObject->picture;
		else
			$picture_uri = $core->settings["PROFILE_PICTURE_ROOT"] . $UserObject->picture;

		return $picture_uri;
	}

    function update_user_ranking ( $id_utente, $count )
    {
        global $dm_utente;

        $user = $this->createUserObject ( $id_utente );
        if ( is_object ($user) ) {
            $update = new stdClass ();
            $update->id_utente = $user->id_utente;
            $update->ranking = $user->ranking + $count;
            $dm_utente->updateObject ( "utente", $update, array ("id_utente" => $update->id_utente ) );
        }
    }

    function is_user_da_sfidare ( $user )
    {
        global $dm_sfide;

        $user = ( is_numeric($user) ) ? $this->createUserObject($userObj) : $user;
        if ( count ($user->sfide_attive) > 0 ) {
            foreach ( $user->sfide_attive as $sfida ) {
                if (
                    (isset ($this->obj) && $this->is_logged && is_object( $user )) &&
                    (($sfida->id_sfidante == $this->obj->id_utente || $sfida->id_sfidato == $this->obj->id_utente) && $sfida->stato < 2) &&
                    $user->id_utente != $this->obj->id_utente
                   )
                    return $dm_sfide->findReverseSfidaId ( $sfida->id_sfida );
            }
            return false;
        } else
            return false;
    }

	function print_messages_row ( $messaggi )
	{
		global $utility;
		foreach ($messaggi as $messaggio) {
			if ( is_object($messaggio)) { ?>
			<tr valign="top" id_mess="<?php echo $messaggio->id_mess; ?>" staff="<?=($messaggio->id_sender == 0) ? 'true' : 'false';?>" <?php if ( $messaggio->letto == 0) echo ' class="unread"'; ?>>
				<td width="5%"><input type="checkbox" /></td>
				<td width="15%"><?php echo $utility->normalize_db_datetime ($messaggio->dta_mess); ?></td>
				<td width="13%" style="padding-right: 7px;"><?php echo $this->print_username ( $messaggio->id_sender ); ?> &nbsp;</td>
				<td width="67%"><a class="message-opener" id="msg_<?php echo $messaggio->id_mess; ?>"><?php echo ($messaggio->oggetto != '') ? utf8_encode ($messaggio->oggetto) : "<em>Nessun oggetto</em>"; ?></a></td>
			</tr>
		<?php }}
	}

	function get_best_user ()
	{
		global $dm_sfide;
		$id_utente = $dm_sfide->getIdUtenteBest ();
		if ( $id_utente != null ) {
			$best = $this->get_user_by_id ($id_utente);
			return $best;
		} else
			return false;
	}

	function get_best_week_user ()
	{
        global $dm_sfide;
		return $dm_sfide->getIdUtenteWeekBest ();
	}

	function get_unread_messages ( $id_utente = null )
	{
		global $dm_messaggi;
		return $dm_messaggi->getArrObjUnbannedMessaggiUnread ( (isset($this->obj->id_utente)) ? $this->obj->id_utente : $id_utente );
	}

	function get_sfide_da_giocare ( $id_utente )
	{
		global $dm_sfide;
		return $dm_sfide->getSfideDaGiocareByUtente ( $id_utente );
	}

	function get_count_sfide_da_giocare ( $id_utente )
	{
		global $dm_sfide;
		return $dm_sfide->getCountSfideDaGiocareByUtente ( $id_utente );
	}

	function get_points ( $id_utente )
	{
		global $dm_utente;
		return $dm_utente->getPunteggioTot ( $id_utente );
	}

	function get_sfide_aperte ( $id_utente = null )
	{
		global $dm_sfide;
		return $dm_sfide->getSfideAttiveUtente ( ( $id_utente == null ) ? $this->obj->id_utente : $id_utente );
	}

	function get_sfide_lanciate_penfing ( $id_utente = null )
	{
		global $dm_sfide;
		return $dm_sfide->getSfideAttiveUtente ( ( $id_utente == null ) ? $this->obj->id_utente : $id_utente );
	}

	function get_sfide_chiuse_by_date_range ( $start_date, $end_date )
	{
		global $dm_sfide, $utility;
		$start_date = $utility->parseStringDateToDb ($start_date);
		$end_date = $utility->parseStringDateToDb ($end_date);
		return $dm_sfide->getArrayObjectQueryCustom ( "select * from sfida where stato >= 2 and stato != 3 and (id_sfidante = ".$this->obj->id_utente." or id_sfidato = ".$this->obj->id_utente.") and ( dta_conclusa >= '$start_date' and dta_conclusa <= '$end_date' ) order by dta_conclusa DESC");
	}

	function get_last_ten_sfide_chiuse ( )
	{
		global $dm_sfide, $utility;
		return $dm_sfide->getArrayObjectQueryCustom ( "select * from sfida where stato >= 2 and stato != 3 and (id_sfidante = ".$this->obj->id_utente." or id_sfidato = ".$this->obj->id_utente.") order by dta_conclusa DESC limit 10 ");
	}

	function get_count_unread_messages ()
	{
		global $dm_messaggi;
		return $dm_messaggi->getCountUnbannedMessages ( $this->obj->id_utente );
	}

	function get_messages ()
	{
		global $dm_messaggi;
		return $dm_messaggi->getArrObjUnbannedMessaggiToUserId ( $this->obj->id_utente );
	}

	function get_filtered_messages ( $start, $end )
	{
		global $dm_messaggi;
		return $dm_messaggi->getFilteredUnbannedMessaggi (  $start, $end  );
	}

	function get_unplayed_sfide ()
	{
		global $dm_sfide;
		return $dm_sfide->getOpenSfideByUtente ( $this->obj->id_utente );
	}

	function is_user_sfidabile ( $obj )
	{
		return ( $this->is_user_active ( $obj->id_utente) && $this->exists ( $obj->id_utente ) );
	}

	function do_login_by_id ( $id_utente )
	{
		global $dm_utente;
		$userObj = $dm_utente->getObjUtenteById ($id_utente);
		if ( $userObj !== false):
			$this->is_logged = true;
			$this->obj = $this->createUserObject( $userObj );
			$this->store_user_info ();
			return $userObj;
		else:
			return false;
		endif;
	}

	function do_login ( $username, $password )
	{
		global $core, $dm_utente;
		if ( isset ($username) && isset($password) ) {
			// Do login
			$userObj = $dm_utente->getUtente ($username, $password);
			if ( $userObj !== false && $userObj->stato != 0) {
				$this->is_logged = true;
				$this->obj = $this->createUserObject( $userObj );
				$this->store_user_info ();
				$this->update_data_activ ();
			}
			return $userObj;
		} else {
			$core->log ( "Tentativo di login con ID $id inesistente");
			return false;
		}
	}

	function store_user_info ()
	{
		//$this->update_data_activ ();
		/*
		$user_info = new stdClass();
		$user_info->ip = ( isset ($_SERVER['HTTP_X_FORWARD_FOR']) ) ? $_SERVER['HTTP_X_FORWARD_FOR'] : $_SERVER['REMOTE_ADDR'];
		$fp = file_get_contents ("http://network-tools.com/default.asp?prog=network&host=" . $user_info->ip);
		$fp = explode ('<div style="width:680px; float:left; margin-top:25px;">', $fp);
		$fp = explode ('</div>', $fp[1]);
		$user_info->details = $fp[0];
		deb ("IP: " . $user_info->ip);
		deb ("DETAILS: " . $user_info->details);
		 * */
	}

	function update_data_activ ()
	{
		global $dm_utente;
		if ( isset ( $this->obj ) && isset ( $this->obj->id_utente )) {
			$dm_utente->update_data_activ ( $this->obj->id_utente );
			$_SESSION["rigorix"]["user"]->dta_activ = $dm_utente->get_data_activ ( $this->obj->id_utente )->dta_activ;
		}
	}

	function do_logout ()
	{
		global $core;
		$this->is_logged = false;
		$this->obj = false;
		$_SESSION['rigorix']['user'] = false;
		$core->set_session_properties ("user", false);
		_log ("User class", "is logged = false, obj destroyed, session destroyed, going with social unlogin");
		$hybridauth = new Hybrid_Auth( "/home2/rigorix/tre/hybridauth/config.php" );
		$hybridauth->logoutAllProviders ();
		_log ("User class", "logoutAllProviders. END!");
	}

	function do_subscription ()
	{
		global $activity, $dm_utente, $core, $_REQUEST;
		$error_msg = $headers = '';
		$eol = "\n\r";
		if ( isset ($_REQUEST['email']) && strlen($_REQUEST['email']) > 0) {
            if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_REQUEST['email']) && $_REQUEST['email'] == $_REQUEST['conf_email'])
                ( $dm_utente->getUtenteAttivoByEmail($_REQUEST['email']) === false) ? $_REQUEST['indb_email'] = $_REQUEST['email'] : $activity->throw_error( 414 );
            else
                $activity->throw_error ( 415 );
		} else
			$activity->throw_error ( 416 );
		if ( isset ($_REQUEST['username']) && strlen($_REQUEST['username']) > 3 && strlen($_REQUEST['username']) <= 20) {
		    if (ereg("^[a-zA-Z0-9_]{3,25}$", $_REQUEST['username'])) {
			    $_REQUEST['nickname'] = str_replace(" ", "_", $_REQUEST['username']);
				if ( $dm_utente->getUtenteAttivoByUsername($_REQUEST['nickname']) === false  )
					$_REQUEST['indb_username'] = $_REQUEST['nickname'];
				else
				    $activity->throw_error( 417 );
			} else {
			    $activity->throw_error( 419 );
			}
		} else
			$activity->throw_error( 420 );

		/*
		if (!isset ($_POST['mobile']) || strlen($_POST['mobile']) == 0)
			$activity->throw_error( 421 );
		else {
			if ($_POST['mobile'] != $_POST['conferma_mobile'])
				$activity->throw_error( 422 );
			else if (!is_numeric($_POST['mobile']))
				$activity->throw_error( 423 );
			else if (strlen($_POST['mobile']) < 8)
				$activity->throw_error( 424 );
			else {
			    if ( $dm_utente->getUtenteByMobile($_POST['mobile']) !== false) {
			        $activity->throw_error( 425 );
			    	$error_msg .= "Il cellulare &egrave; gi&agrave; stato usato da un altro utente.<br />";
			    	$mail_msg = $_POST['nickname'].' ha cercato di registrarsi con il cellulare di un altro utente ('.$_POST['mobile'].')';
					$headers .= "From: Rigorix Robot<tech@rigorix.com>".$eol;
					$core->send_mail ('massimo@rigorix.com', 'Tentativo di registrazione con stesso cellulare', $mail_msg, $headers);
				} else
					$_POST['indb_mobile'] = $_POST['mobile'];
			}
		}
		if ( isset ($_POST['password']) && strlen($_POST['password']) > 0 && isset ($_POST['confpassword']) && strlen($_POST['confpassword']) > 0)
		    ($_POST['password'] == $_POST['confpassword']) ? $_POST['indb_passwd'] = $_POST['password'] : $activity->throw_error( 426 );
		else
			$activity->throw_error( 427 );
		if ( !isset ($_POST['privacy']) || $_POST['privacy'] == "no")
			$activity->throw_error( 428 );
		*/
		if ( $activity->check_error_range( 411, 428 ) )
			return false;
		else {
			/*
			 * Registrazione andata a buon fine, lancio la procedura di verifica
			 */

			$_REQUEST['indb_dta_reg'] = '_V_NOW_';
			$_REQUEST['indb_stato'] = 0;
			$_REQUEST['indb_attivo'] = 1;
			$obj_indb = $dm_utente->makeInDbObject($_REQUEST);
			// echo "Aggiorno utente: <br />";
			// var_dump ( $_REQUEST );
			$ret_update = $dm_utente->updateObject('utente', $obj_indb, array ( "id_utente" => $_REQUEST["id_utente"]));

			/*
			$params = "activity=subscribe_confirm&id_utente=$id_inserted&cod=".md5("RIGORIX_REGISTRATION" . $id_inserted . "TEST_MD5_REGISTRATION" );
			if ( $_SESSION['rigorix']['storage']['send_invite_on_subscription'] == "true" )
				$params .= "&acion_after_reg=subscribe_torneo&id_torneo=" . $_SESSION['rigorix']['storage']['torneo_attivo'];

			$eol = "\r\n";
			$fromname = "RigoriX";
			$fromaddress = "registrazione@rigorix.com";
			# Common Headers
			$headers .= "From: ".$fromname."<".$fromaddress.">".$eol;
			$headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;
			$mail_subject = "conferma registrazione";
			$mail_text = 'Per confermare la tua registrazione a RigoriX clicca questo link: http://'.$_SERVER["HTTP_HOST"].'/automatic_actions.php?' . $params.'. **** Se non funziona, copialo e incollalo sulla barra del browser. *** Rigorix Staff ***';
			$core->send_mail ($_POST['indb_email'], $mail_subject, $mail_text );
			*/
			return true;
		}

	}

	function do_subscription_confirm()
	{
		global $dm_utente;

		$obj = $_REQUEST;
		$subscription = $dm_utente->getSubscriptionById ( $obj["id_utente"] );
		if ( $obj["cod"] == md5("RIGORIX_REGISTRATION" . $subscription->id_utente . "TEST_MD5_REGISTRATION" ) ) {
			// Codice corretto, procedo
			$update = array ( "indb_stato" => 1 );
			$id_inserted = $dm_utente->updateObject ('utente', $dm_utente->makeInDbObject ($update), array ( "id_utente" => $subscription->id_utente) );
			$this->do_login ( $subscription->username, $subscription->passwd );
			return true;
		} else
			return false;
	}

	function do_unsubscription ($id_utente)
	{
		global $dm_utente, $activity, $core;

    if ( UsersUnsubscribe::find($id_utente)->count() == 0 ):

      $unsubscribe = new UsersUnsubscribe;
      $unsubscribe->id_utente = $id_utente;
      $unsubscribe->stato = 0;
      $unsubscribe->dta_richiesta = date('Y-m-a H:m:s');
      $unsubscribe->conf_code = md5( $id_utente . Users::find($id_utente)->username . "unsubscribe_utente_key" );

      $unsubscribe->save();

      $activity->throw_success ( 120 );

      return true;
//
//
//      $unsub["indb_stato"] = 0;
//      $unsub["indb_data_richiesta"] = "_V_NOW_";
//      $unsub["indb_conf_code"] = md5 ( $this->obj->id_utente.$this->obj->username."unsubscribe_utente_key" );
//      $obj_indb = $dm_utente->makeInDbObject($unsub);
//        if ($dm_utente->insertObject('unsubscribe', $obj_indb)) {
//          $mail_text = "Ciao " . $this->obj->username . ", <br />ci e' arrivata una richiesta di cancellazione.<br /><br />Per confermarla, vai a questo indirizzo: <a href='http://www.rigorix.com/automatic_actions.php?activity=unsubscribe_confirm&code=" . $unsub["indb_conf_code"] . "'>http://www.rigorix.com/automatic_actions.php?activity=unsubscribe_confirm&code=" . $unsub["indb_conf_code"] . "</a><br /><br />Se invece non hai intenzione di disiscriverti, ignora questa mail.<br /><br /><strong>Rigorix Staff</strong>";
//          $core->send_mail ( $this->obj->email, "Richiesta conferma cancellazione utente", $mail_text );
//        _log ("UNSUBSCRIBE", $mail_text);
//        _log ("UNSUBSCRIBE", "Mail mandata, aspetto ora la conferma del click");
//          $this->do_logout ();
//        $activity->throw_success ( 120 );
//        return true;
//        }

    endif;
	}

	function do_unsubscription_confirm ()
	{
		global $dm_utente;
		_log ("UNSUBSCRIBE", "Disiscrivo codice: " . $_REQUEST['code']);
		$request = $dm_utente->getUnsubscriptionByCode ( $_REQUEST['code'] );
		if ( $request !== false && $request->stato == 0 ) {
			_log ("UNSUBSCRIBE", "Trovato l'unsubscribe, aggiorno la tabella, cancello utente id: " . $request->id_utente );
			_log ("UNSUBSCRIBE", "Eseguo la query: ");

			$deaduser = array( "indb_attivo" => 0 );
			$deaduser_indb = $dm_utente->makeInDbObject($deaduser);
			$dm_utente->updateObject('utente', $deaduser_indb, array ( "id_utente" => $request->id_utente));

			$dm_utente->executeQuery ('update utente set username = CONCAT("'.DELETED_USER_PREFIX.'", utente.username), social_provider = CONCAT("'.DELETED_USER_PREFIX.'", utente.social_provider), social_uid = CONCAT("'.DELETED_USER_PREFIX.'", utente.social_uid), email = CONCAT("'.DELETED_USER_PREFIX.'", utente.email) where id_utente = ' . $request->id_utente);

			$unsub = array();
			$unsub["indb_stato"] = 2;
			if ($dm_utente->updateObject('unsubscribe', $dm_utente->makeInDbObject($unsub), array ( "id_unsubscribe" => $request->id_unsubscribe)))
				return true;
			else
				return false;
		} else
			return false;
	}

	function update_profile_picture ( $filename )
	{
		global $dm_utente;

		$obj = array ( "indb_picture" => $filename );
		$indbObj = $dm_utente->makeInDbObject($obj);
		$dm_utente->updateObject ( "utente", $indbObj, array ("id_utente" => $this->obj->id_utente) );
		$this->update_user_object ();
	}

	function createUserObject( $userObj )
	{
		global $dm_utente, $dm_rewards;
		if ( is_numeric( $userObj) )
			$userObj = $dm_utente->getObjUtenteById ( $userObj );

		$userObj->messaggi = $this->get_unread_messages ( $userObj->id_utente );
		/*
		if ( isset ($userObj->data_nascita) && $userObj->data_nascita != '') {
			list ($y, $m, $d) = explode ( "-", $userObj->data_nascita);
			$userObj->data_nascita_ita = $d . "/" . $m . "/" . $y;
		} else
			$userObj->data_nascita_ita = '';
		$userObj->has_torneo_attivo = $dm_tornei->hasUserTorneoAttivo ( $userObj->id_utente );
		$userObj->my_torneo = ( $userObj->has_torneo_attivo > 0 ? $this->get_torneo_utente( $userObj->id_utente ) : false );
		$userObj->eta = $dm_utente->getEtaUtente ( $userObj->id_utente );
		$userObj->inviti = $dm_utente->getInviti ( $userObj->id_utente );
		$userObj->tornei = $dm_utente->getTorneiSottoscritti ( $userObj->id_utente );
		*/
		$userObj->punteggio_totale = $this->get_points ( $userObj->id_utente );
		$userObj->badges = $this->get_badges ( $userObj->id_utente );
		$userObj->sfide_da_giocare = $this->get_sfide_da_giocare( $userObj->id_utente );
		$userObj->dead = $dm_utente->getDeadStatus ( $userObj->id_utente );
		$userObj->sfide_attive = $this->get_sfide_aperte ( $userObj->id_utente );
		$userObj->unsubscribing = $dm_utente->getUnsubscribeRequest ( $userObj->id_utente );
		$userObj->rewards = $dm_rewards->getRewardsObjectByIdUtente ( $userObj->id_utente );
		return $userObj;
	}

	function add_score ( $id_utente, $score )
	{
		global $dm_utente;
	}

	function get_num_utenti_registrati ()
	{
		global $dm_utente;
		return $dm_utente->getNumUtentiRegistrati ();
	}

	function get_num_utenti_online ()
	{
		global $dm_utente;
		return $dm_utente->getCountLastPresences ();
	}

	function get_username_online ()
	{
		global $dm_utente;
		return $dm_utente->getUsernameOnline ();
	}

	function get_won_matches ( $id_utente )
	{
		global $dm_utente;
		return $dm_utente->getArrayObjectQueryCustom ("select * from sfida where id_vincitore = $id_utente");
	}

	function get_reward_picture ( $key_id, $type = "big", $suffix = "" )
	{
		if ( is_file( BADGE_PICTURE_REPOSITORY . $key_id . "__$type$suffix.png") )
			return BADGE_PICTURE_PATH . $key_id . "__$type$suffix.png";
		else
			return BADGE_PICTURE_PATH . "default__$type$suffix.png";
	}

    function print_rewards_popover ( $ids )
    {
        global $dm_rewards;

        echo "<h5 class='mbs'>Dettaglio Rewards</h5>";
        echo "<table class='table table-condensed table-striped'>";
        foreach ( $ids as $reward_id ):
            $reward = $dm_rewards->getRewardById($reward_id);
            echo "<tr>";
            echo "<td><span class='badge badge-".(($reward->score > 0) ? "success" : "inverse" )."'>" . $reward->score . "</span></td>";
            echo "<td><strong>" . $reward->nome . "</strong><p>" . $reward->descrizione . "</p></td>";
            echo "</tr>";
        endforeach;
        echo "</table>";
    }

	function get_badges ( $id_utente )
	{
		global $dm_utente;
		return $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.tipo = 'badge'");
	}

	function has_badge_by_key ( $id_utente, $reward_key )
	{
		global $dm_utente;

		$badges = $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.key_id = '$reward_key' and rewards.tipo = 'badge'");
		return ( count ($badges) > 0 ? true : false);
	}

	function get_badge_by_key ( $id_utente, $reward_key )
	{
		global $dm_utente;

		return $dm_utente->getArrayObjectQueryCustom ("select * from rewards, sfide_rewards where sfide_rewards.id_utente = $id_utente and rewards.id_reward = sfide_rewards.id_reward and rewards.key_id = '$reward_key' and rewards.tipo = 'badge'");
	}

	function print_user_badge ( $reward )
	{
		echo '<div class="badge-icon ' . $reward->key_id . '" title="'.$reward->nome.'"></div>';
	}

	function get_ranking_utenti( $limit = false )
	{
		global $dm_utente;
		return $dm_utente->getRankingUtenti ( $limit );
	}

	function get_user_list_by_filter ( $filter )
	{
		global $dm_utente;

		$query = "select * from utente where ";
		if ( $filter["username"] != '' ) {
		    $query .= " username LIKE '%".$filter["username"]."%' AND ";
		}
		if ( $filter["sesso"] != 'X' ) {
		    $query .= " sesso = '".$filter["sesso"]."' AND ";
		}
		if ( $filter["eta_minima"] != '' )
		    $query .= " ( year(NOW()) - year(data_nascita)) > ".$filter["eta_minima"]." AND ";
		if ( $filter["eta_massima"] != '' )
		    $query .= " ( year(NOW()) - year(data_nascita)) < ".$filter["eta_massima"]." AND ";
		if ( $filter["prov"] != 'false' )
		    $query .= " prov = '".$filter["prov"]."' AND ";
		if ( $filter["naz"] != 'false' )
		    $query .= " nazione = '".$filter["naz"]."' AND ";
		$query .= "1 = 1";
		return $dm_utente->getArrayObjectQueryCustom ( $query );
	}

	function get_user_by_fbid ( $fbid )
	{
	    global $dm_utente;

	    $query = "select * from utente where fbid = $fbid";
	    return $dm_utente->getSingleObjectQueryCustom ( $query );
	}

	function get_user_by_username ( $username )
	{
	    global $dm_utente;

	    $query = "select * from utente where username = '$username'";
	    return $dm_utente->getSingleObjectQueryCustom ( $query );
	}

	function get_user_by_id ( $id )
	{
	    global $dm_utente;

        if ( $id != 0 )
	        return $dm_utente->getSingleObjectQueryCustom ( "select * from utente where id_utente = $id" );
        else
            return false;
	}

}

?>