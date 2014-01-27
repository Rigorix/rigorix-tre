<?php

/*
 * ACTIVITIES.
 * Per startare un'activity si possono usare i seguenti metodi:
 *
 * 1) $_REQUEST['activity'] = "ACTIVITY_NAME"
 *
 *
 * ACTIVITIES
 *
 * #login
 * 		$_POST['username']
 * 		$_POST['password']
 * #logout
 * #reset_session
 *
 * ERRORS
 * Gli errori hanno tutti un codice numerico, in modo da poterli stampare ad hoc in ogni posizione del sito
 *
 */

class activities {
	var $alerts_container = array();
	var $alerts = array (
		"social_registration_to_compleate"		=> "Il tuo account social &egrave; appena stato connesso.<br /><br />Per giocare devi completare la tua scheda cliccando <a href=\"#\">qu&igrave;</a>"
	);

	var $success_container = array();
	var $success = array (
		"100"	=> "Processo di login completato correttamente",
		"102"	=> "Messaggio spedito correttamente",
		"103"	=> "Password ri-mandata correttamente",
		"104"	=> "Messaggio cancellato con successo",
		"105"	=> "Utente aggiunto alla lista ignorata con successo",
		"106"	=> "I messaggi sono stati segnati come letti",
		"110"	=> "Dati utente aggiornati correttamente",
		"115"	=> "Mascotte aggiornata correttamente",
		"120"	=> "Ti &egrave; stata mandata la richiesta di conferma cancellazione<br /><br />Controlla la posta e, se hai realmente effettuato la richiesta, segui il link che ti abbiamo inviato.",
		"125"	=> "Sei stato correttamente disiscritto<br /><br />Ti ricordiamo che il tuo utente restera' attivo finche' le competizioni in cui sei in gioco lo saranno.<br />Tuttavia, potrai entrare in Rigorix ma non lanciare sfide o giocare.",

		"200"	=> "Torneo creato correttamente",
		"250"	=> "Logo del torneo caricato correttamente",
		"260"	=> "Utenti invitati correttamente",
		"265"	=> "Ti sei iscritto al torneo con successo",
		"270"	=> "Hai rifiutato l\invito con successo",

		"300"	=> "Sfida inserita correttamente",

		"400"	=> "Processo di registrazione completato correttamente. In attesa della conferma",
		"430"	=> "Utente creato e pronto a giocare",
		"440"	=> "L\'immagine del tuo profilo &egrave;<br />stata caricata correttamente.",

		"500"	=> "Scheda partita caricata correttamente"
	);

	var $error_container = array();
	var $errors = array(
		// OPERAZIONI UTENTE GENERICHE
		"100"	=> "Utente o password errato",
		"101"	=> "Utente o password non inseriti",
		"102"	=> "Errore nel spedire il messaggio. Destinatario errato.",
		"103"	=> "Lo username inserito per il recupero password non esiste.",
		"104"	=> "Impossibile cancellare il messaggio.",
		"105"	=> "Errore nell'ignorare l'utente.",
		"106"	=> "Errore di spedizione. Destinatario o Oggetto mancante.",
		"110"	=> "Errore nell'aggiornare i dati utente",
		"115"	=> "Errore nell'aggiornamento della mascotte",
		"120"	=> "Errore durante il prodesso di cancellazione.<br /><br />Per favore, contatta il nostro tecnico all\'indirizzo <a href=\"mailto:tech@rigorix.com\">tech@rigorix.com</a>",
		"121"	=> "Hai gia\' effettuato la richiesta di cancellazione!",

		// ERRORI TORNEI
		"200"	=> "Ci sono stati dei problemi nella creazione del torneo. Riprova",
		"201"	=> "Si sta cercando di creare un torneo con un id utente diverso",
		"202"	=> "Il numero di partecipanti minimo e massimo<br />dev\'essere pari.",
		"203"	=> "Il numero di partecipanti minimo dev\'essere inferiore a quello massimo.",
		"204"	=> "Il numero di partecipanti non puo\' essere minore di 8.",
		"205"	=> "La data di inizio inserita non e\' valida",
		"206"	=> "Il torneo deve cominciare almeno <br />dopo una settimanda dalla creazione.<br /><br />Cambia la data di inizio.",
		"207"	=> "Se il torneo ha il playoff, &egrave; necessario che il numero minimo e/o massimo dei partecipanti<br />sia una potenza di due. (2, 4, 8, 16, 32, etc)",
		"208"	=> "Il nome del torneo &egrave; obbligatorio",
		"209"	=> "Devi selezionare almeno uno tra -girone all\'italiana- e -playoff-",
		"210"	=> "Errore generico durante la creazione.<br />Controlla di aver inserito tutto in modo corretto.",
		"211"	=> "Il numero di giocatori della zona play-off non &egrave; corretto.<br />Ti ricordiamo che il numero di partecipanti al play off non pu&ograve; <br />superare i 2/3 del totale partecipanti al torneo <br /> e dev\'essere una potenza di 2 (8, 16, 32, etc..)",
		"212"	=> "La restrizione sull\'et&agrave; non pu&ograve; avere <br />l\'et&agrave; minima maggiore dell\'et&agrave; massima.",
		"250"	=> "Peso limite superato",
		"251"	=> "Impossibile copiare sulla cartella specificata",
		"260"	=> "Errore durante l\'operazione di invito utenti.<br /><br />Riprovare pi&ugrave; tardi",
		"261"	=> "L\'utente <strong>{username}</strong> non ha le caratteristiche per partecipare al torneo o &egrave; gi&agrave; stato invitato.",
		"265"	=> "Errore durante l\'iscrizione. <br /><br />Verifica di poter realmente far parte del torneo o di non esserti gi&agrave; iscritto.<br /><br />Ti consigliamo comunque di riprovare o di contattare Rigorix",
		"266"	=> "Impossibile iscriversi al torneo. <br />Mentre stavi effettuando l\'iscrizione il torneo &egrave; partito.",
		"270"	=> "Impossibile rifiutare l\'invito. <br />Contatta i tecnici di Rigorix per sapere il perch&egrave;",

		// ERRORI SFIDE
		"300"	=> "Errore generico nella gestione della sfida",
		"301"	=> "Errore nell\'aggiornare lo stato della sfida",
		"302"	=> "Errore nel controllo inserimento delle parate e dei tiri",

		// ERRORI REGISTRAZIONE
		"410"	=> "Tentativo di registrazione ma valore _POST[\"posted\"] non trovato ",
		"411"	=> "Nome utente obbligatorio",
		"412"	=> "Cognome obbligatorio",
		"413"	=> "yopmail.com non &egrave; un dominio consentito",
		"414"	=> "Questa email e' gia' stata utilizzata",
		"415"	=> "Email non corretta o non confermata",
		"416"	=> "Email obbligatoria",
		"417"	=> "Username gi&agrave; presente nel nostro database",
		"418"	=> "Nel Username sono ammessi solo lettere o numeri. Lunghezza massima: 10 caratteri - lunghezza minima: 3 DEPRECATO",
		"419"	=> "Stai usando uno username non ammesso",
		"420"	=> "Username obbligatorio o formato errato",
		"421"	=> "Il cellulare &egrave; obbligatorio",
		"422"	=> "Il numero di cellulare &egrave; diverso da quello di conferma",
		"423"	=> "Il numero di cellulare può contenere solo numeri",
		"424"	=> "Il numero di cellulare &egrave; troppo corto, minimo 8 numeri",
		"425"	=> "Il cellulare &egrave; gi&agrave; stato usato da un altro utente",
		"426"	=> "Le due password devono essere uguali",
		"427"	=> "Inserire sia la password che la conferma della password",
		"428"	=> "E' necessario accettare i termini per la privacy",
		"430"	=> "Errore generale nel confermare la registrazione",

		"440"	=> "Immagine caricata con successo",

		// ERRORI UPLOAD PROFILE PICTURE
		"440"	=> "Peso limite superato",
		"441"	=> "Impossibile copiare sulla cartella specificata",

		"500"	=> "Errore nel caricamento della scheda partita",

		// ERRORE GENERICO
		"999"	=> "Errore generico"
	);

	function activities ()
	{

	}

	function start ( $ACTIVITY_NAME )
	{
		$ACTIVITY_NAME = "do_" . $ACTIVITY_NAME;
		_log ( "ACTIVITY", $ACTIVITY_NAME);
		$this->{$ACTIVITY_NAME}();
	}

	function do_login ()
	{
		global $core, $user;
		if ( isset($_POST['username']) && isset($_POST['password']) && strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0 ) {
			$core->set_session_properties ( 'user', false );
			$status = $user->do_login ($_POST['username'], $_POST['password']);
			if ( $status !== false ) {
				$core->set_session_properties ( 'user', $status );
        		deb ("do_login");
				//if (strpos($_SERVER['PHP_SELF'], "area_personale") === false )
				  //header ("Location: area_personale.php");
				return $status;
			} else {
				$core->set_session_properties ( 'user', false );
				$this->throw_error ( 100 );
				return false;
			}
		} else {
			$this->throw_error ( 101 );
			return false;
		}
	}

	function do_login_by_id ( )
	{
		global $core, $user, $env;
		$id = $_REQUEST["id"];

//    echo $_SESSION['rigorix_auth_origin'] ."--". $env->DOMAIN;
//    die();
//		if ($_SESSION['rigorix_auth_origin'] != $env->DOMAIN)
//      header ("Location: " . $_SESSION['rigorix_auth_origin'] . "?activity=login_by_id&id=" . $id);

		$status = $user->do_login_by_id ( $id );
		if ( $status !== false ) {
			$core->set_session_properties ( 'user', $status );
			if ( $status->attivo == 0 && $core->not_internal_url() )
				header ("Location: compleate_registration.php");
			else
				return $status;
		} else {
			$core->set_session_properties ( 'user', false );
			$this->throw_error ( 100 );
			return false;
		}
	}

	function do_complete_registration ()
	{
		global $user, $_POST;
		if ( $user->do_subscription () )
			if ( $user->do_login_by_id ( $_REQUEST["id_utente"]) != false )
				header ("Location: index.php");
			else
				echo "E quindi ?";

	}

	function do_logout ()
	{
		global $user;
		$user->do_logout ();
		header ("Location: index.php");
	}

	function do_update_user_data ()
	{
		global $user, $dm_utente, $utility;
		$_REQUEST["indb_id_utente"] = $user->obj->id_utente;
		$_REQUEST["indb_data_nascita"] = $utility->parseStringDateToDb ($_REQUEST["indb_data_nascita"]);
		$obj_indb = $dm_utente->makeInDbObject($_REQUEST);
	    $id_inserted = $dm_utente->updateObject('utente', $obj_indb, array( "id_utente" => $user->obj->id_utente));
		if ($id_inserted) {
			$user->update_user_object ();
			$this->throw_success( 110 );
		} else
			$this->throw_error( 110 );
	}

	function do_update_mascotte ()
	{
		global $user, $dm_utente, $utility;
		$obj_indb = $dm_utente->makeInDbObject($_REQUEST);
	    $id_inserted = $dm_utente->updateObject('utente', $obj_indb, array( "id_utente" => $user->obj->id_utente));
		if ($id_inserted) {
			$user->update_user_object ();
			$this->throw_success( 115 );
		} else
			$this->throw_error( 115 );
	}

	function do_reset_session ()
	{
		unset ($_SESSION['rigorix']);
    deb ("do_reset_session");
		//header ("Location: index.php");
	}

	function do_upload_profile_picture ()
	{
		global $core, $utility, $user;
		/*
		 * Caricamento immagine utente
		 */
		if ( isset ($_FILES['picture-uploader']) && isset($_FILES['picture-uploader']['name']) && $_FILES['picture-uploader']['name'] != '' ) {
			// E' stata caricata un'immagine
			if($_FILES['picture-uploader']['size'] > $core->settings["MAX_PROFILE_PICTURE_SIZE"]) {
				$this->throw_error( 440 );
				return false;
			}
			$dir = $core->settings["PROFILE_PICTURE_REPOSITORY"];
			$fileName = str_replace(" ", "-", $_FILES['picture-uploader']['name']);
			$fileName = $utility->checkFileName($fileName, $dir);
			$fileNameTemp = $_FILES['picture-uploader']['tmp_name'];
			if(move_uploaded_file($fileNameTemp, $dir.$fileName)) {
				$_SESSION['rigorix']['uploaded_filename'] = $fileName;
				$user->update_profile_picture ($fileName);
				$this->throw_success( 440 );
				return $fileName;
			} else {
				$this->throw_error( 441 );
				return false;
			}
		} else
			$this->throw_error( 441 );
		return false;
	}

	function do_subscribe ()
	{
		global $core, $user;
    	deb ("user logged? " . $user->is_logged);
		if ( $user->is_logged ) {}
		//	header ("Location: index.php");
		else if (isset ($_REQUEST['posted']) && $_REQUEST['posted'] == 1) {
			$result = $user->do_subscription ();
			if ( $result === true)
				$this->throw_success ( 400 );
		} else
			$this->throw_error( 410 );
	}

	function do_subscribe_confirm ()
	{
		global $user;

		if ( $user->do_subscription_confirm () ) {
			$this->throw_success ( 430 );
		} else
			$this->throw_error( 430 );
	}

	function do_write_message ()
	{
		global $dm_utente, $dm_messaggi, $user;

		//$id_inserted = $_GET['id_inserted'];
		if (isset($_POST['destinatario']) && strlen($_POST['destinatario'])>0 && isset ($user->obj))
		{
			$_POST['indb_id_sender'] = $user->obj->id_utente;
			$_POST['indb_id_receiver'] = $dm_utente->getIdUtenteByUsername($_POST['destinatario']);
			if ($_POST['indb_id_receiver']) {
				$_POST['indb_oggetto'] = $_POST['oggetto'];
				$_POST['indb_testo'] = $_POST['testo'];
				$_POST['indb_dta_mess'] ='_V_NOW_';
				$obj_indb = $dm_messaggi->makeInDbObject($_POST);
			    $id_inserted = $dm_messaggi->insertObject('messaggi',$obj_indb);
			    $this->throw_success ( 102 );
			} else
				$this->throw_error( 102 );
		} else
			$this->throw_error( 106 );
	}

	function do_delete_message ()
	{
		global $dm_messaggi;

		if ( isset($_REQUEST['id_mess']) && $_REQUEST['id_mess'] != '' ) {
			$ids_mess = explode(",", $_REQUEST['id_mess']);
			for ( $i=0; $i < count ($ids_mess); $i++) {
				if ($ids_mess[$i] != '') {
					$dm_messaggi->deleteMessageById ($ids_mess[$i]);
					$this->throw_success ( 104 );
				}
			}
		} else
			$this->throw_error ( 999 );
	}

	function do_delete_multi_message ()
	{
		global $dm_messaggi;

		if ( isset($_REQUEST['ids']) && $_REQUEST['ids'] != '' ) {
			$ids = explode(",", $_REQUEST['ids']);
			foreach ($ids as $id_mess) {
				$dm_messaggi->deleteMessageById ($id_mess);
			}
			$this->throw_success ( 104 );
		} else
			$this->throw_error ( 999 );
	}

	function do_ignore_user_messages ()
	{
		global $dm_messaggi, $user;

		if ( isset($_REQUEST['id_mess']) && $_REQUEST['id_mess'] != '' ) {
			$ignore_obj = array();
			$ignore_obj["indb_id_bannato"] = $dm_messaggi->getSenderByIdMess ($_REQUEST['id_mess']);
			$ignore_obj["indb_id_utente"] = $user->obj->id_utente;
			$obj_indb = $dm_messaggi->makeInDbObject( $ignore_obj );
			$id_inserted = $dm_messaggi->insertObject( 'bannati', $obj_indb );
			$this->throw_success ( 105 );
		} else
			$this->throw_error ( 999 );
	}

	function do_multimark_as_read ()
	{
		global $dm_messaggi;

		if ( isset($_REQUEST['ids']) && $_REQUEST['ids'] != '' ) {
			$ids = explode(",", $_REQUEST['ids']);
			foreach ($ids as $id_mess) {
				$dm_messaggi->markAsReadById ( $id_mess );
			}
			$this->throw_success ( 106 );
		} else
			$this->throw_error ( 999 );
	}

	function do_password_recovery ()
	{
		global $core, $dm_utente;
		if ( isset ($_REQUEST['username']) && $_REQUEST['username'] != '') {
			$objUtente = $dm_utente->getObjUtenteByUsername($_REQUEST['username']);
			if ( isset($objUtente->username) ) {
				$headers    = 	"From: \"RigoriX\" <info@rigorix.com>\r\n";
		   		$headers   .= 	"Reply-To: info@rigorix.com\r\n";
				if ( $core->settings["ALLOW_EMAIL_SEND"] == true)
		   			mail($objUtente->email, "recupero password", "Ciao ".$objUtente->nome."\r\nla tua password su RigoriX &egrave;: ".$objUtente->passwd, $headers);
				$this->throw_success( 103 );
			} else
				$this->throw_error( 103 );
		} else
			$this->throw_error( 103 );
	}

	function do_unsubscribe_user ()
	{
		global $user;
		if ( $user->do_unsubscription() )
			$this->throw_success( 120 );
		else
			$this->throw_error( 120 );
	}

	function do_unsubscribe_confirm ()
	{
		global $user;
		if ( $user->do_unsubscription_confirm() )
			$this->throw_success( 125 );
		else
			$this->throw_error( 120 );
	}

	/*
	 *  SFIDE
	 */

	function create_sfida_obj ( $sfida )
	{
		global $user, $dm_sfide, $utility, $dm_rewards;

		if ( is_numeric( $sfida ))
			$sfida = $dm_sfide->getSfidaById ($sfida);
		$sfida->data_lancio_str = ($sfida->data_lancio != null && $sfida->data_lancio != '' ) ? $utility->parseDbDateToString ($sfida->data_lancio) : false;
		$sfida->data_risposta_str = ($sfida->data_risposta != null && $sfida->data_risposta != '' ) ? $utility->parseDbDateToString ($sfida->data_risposta) : false;
		$sfida->data_chiusura_str = ($sfida->data_chiusura != null && $sfida->data_chiusura != '' ) ? $utility->parseDbDateToString ($sfida->data_chiusura) : false;
		$sfida->vinta = false;
		$sfida->pareggio = false;
		$sfida->persa = false;
        $rewardsObject = $dm_rewards->getRewardPointsByIdSfida ( $user->obj->id_utente, $sfida->id_sfida );
		$sfida->rewardPoints = $rewardsObject["tot_punti"];
		$sfida->rewardIds = $rewardsObject["ids"];
		if ($sfida->risultato != null && $sfida->risultato != '' ) {
			list ( $res1, $res2 ) = explode (",", $sfida->risultato );
			$sfida->risultato_str = "<span>$res1</span> : <span>$res2</span>";
			if (
				($res1 > $res2 && $sfida->id_sfidante == $user->obj->id_utente ) ||
				($res1 < $res2 && $sfida->id_sfidato == $user->obj->id_utente )
			)
				$sfida->vinta = true;
			else if ($res1 == $res2)
				$sfida->pareggio = true;
			else
				$sfida->persa = true;
		}

		return $sfida;
	}


	/*
	 *  SFIDE
	 */

  function do_lancia_sfida ( $params = null, $sfida = null ) {
    global $dm_sfide;

    if ( isset($params) && isset($sfida) ) {

      $risposta = true;
      if ( !isset ($sfida->id_sfida) || $sfida->id_sfida === false ):
        $obj = array (
          "indb_tipo_sfida"     => 0,
          "indb_id_sfidante"		=> $sfida->id_sfidante,
          "indb_id_sfidato"			=> $sfida->id_avversario,
          "indb_dta_sfida"			=> "NOW()",
          "indb_stato"					=> 0
        );
        $id_sfida = $dm_sfide->insertObject ( "sfida", $dm_sfide->makeInDbObject ($obj));
        $risposta = false;
      else:
        $id_sfida = $sfida->id_sfida;
      endif;

      $obj = new stdClass();
      $obj->id_sfida = $id_sfida;
      $obj->id_utente = $sfida->id_utente ? $sfida->id_utente : $sfida->id_sfidante;
      $obj->o1 = $params->tiro1 + 1;
      $obj->o2 = $params->tiro2 + 1;
      $obj->o3 = $params->tiro3 + 1;
      $obj->o4 = $params->tiro4 + 1;
      $obj->o5 = $params->tiro5 + 1;
      $res_id_tiri = $dm_sfide->insertObject ( "tiri", $obj );

      $obj->o1 = $params->parata1 + 1;
      $obj->o2 = $params->parata2 + 1;
      $obj->o3 = $params->parata3 + 1;
      $obj->o4 = $params->parata4 + 1;
      $obj->o5 = $params->parata5 + 1;
      $res_id_parate = $dm_sfide->insertObject ( "parate", $obj );

      if ( $res_id_tiri !== false && $res_id_parate !== false ):
        $sfidaObj = new stdClass();
        $sfidaObj->id_sfida = $id_sfida;
        $sfidaObj->tipo_sfida = 0;

        if ( $risposta == false ):
          $sfidaObj->stato = 1;
          $sfidaObj->id_sfidante = $sfida->id_sfidante;
          $sfidaObj->id_sfidato = $sfida->id_avversario;
          $sfidaObj->dta_sfida = "NOW()";
        else:
          $sfidaObj->stato = 2;
          $sfidaObj->dta_conclusa = "NOW()";
        endif;

        if ($dm_sfide->updateObject ( "sfida", $sfidaObj, array ( "id_sfida" => $id_sfida ) ) ):

          if ( $sfidaObj->stato == 2 )
            $this->crone__chiudi_sfida ( $sfida );

          $this->throw_success ( 300 );
        endif;

      else:
        $this->throw_error ( 302 );
      endif;

    } else {
      $this->throw_error ( 300 );
    }

    return [300, 302];
  }





	/*
	 * Generic
	 */

	function activity_response_handler ( $activity_key, $activity_range = null, $custom_json_response = null )
	{
		if ( $activity_range != null && !is_numeric($activity_range)) {
			$custom_json_response = $activity_range;
			$activity_range = null;
		}
		if ( $this->check_success( $activity_key )) {
			$res = '{"status": "OK", "text": "'.$this->success[$activity_key].'"';
			if ( isset ($_SESSION['rigorix']['uploaded_filename']) && $_SESSION['rigorix']['uploaded_filename'] != '' )
				$res .= ', "filename": "'.$_SESSION['rigorix']['uploaded_filename'].'"';
			$res .= ( $custom_json_response != null ? "," . $custom_json_response : '') . "}";
			return $res;
		} else {
			if ( $activity_range != null && $activity_range != false ) {
				if ($this->check_error_range ($activity_key, $activity_range)) {
					$errors = $this->print_error_range ($activity_key, $activity_range);
					$ret = '{"status": "KO", "error_code": "'.$activity_key.'", text: "';
					$ret .= $errors;
					$ret .= ( $custom_json_response != null ? "," . $custom_json_response : '') . '"}';
					return $ret;
				}
			}
			else if ($this->check_error( $activity_key )) {
				return '{"status": "KO", "error_code": "'.$activity_key.'", "text": "'.$this->errors[$activity_key].'"'.( $custom_json_response != null ? "," . $custom_json_response : '') .'}';
			} else
				return '{"status": "KO-generic", "text": "Errore generico dell\'applicazione" '.( $custom_json_response != null ? "," . $custom_json_response : '') .'}';
		}
	}


	function check_error ( $type )
	{
		return in_array( $type, $this->error_container);
	}

	function print_error ( $type )
	{
		foreach ($type as $error_key) {
			if ( in_array($error_key, $this->error_container) ) {
				include ("boxes/error_" . $error_key .".php");
			}
		}
	}

	function print_error_text ( $type )
	{
		if ( in_array( $type, $this->error_container ))
			echo $this->errors[$type];
		else
			return false;
	}

	function throw_alert ( $type )
	{
		_log ("ACTIVITY", "Alert $type [".$this->alerts[$type]."]");
		array_push( $this->alerts_container, $type );
	}

	function throw_error ( $type )
	{
		_log ("ACTIVITY", "Error $type [".$this->errors[$type]."]");
		array_push( $this->error_container, $type );
	}

	function throw_error_template ( $type, $vars )
	{
		// Override error message
		$message = $this->errors[$type];
		foreach ( $vars as $k => $v ) {
			$message = str_replace( "{".$k."}", $v, $message );
		}
		$this->errors[$type] = $message;
		array_push( $this->error_container, $type );
	}

	function check_error_range ( $start, $end )
	{
		for ( $i=$start; $i<=$end; $i++) {
			if ( in_array( $i, $this->error_container ))
				return true;
		}
		return false;
	}

	function has_error ( $type )
	{
		return in_array( $type, $this->error_container );
	}

	function has_error_range ( $start, $end )
	{
		for ( $i=$start; $i<=$end; $i++) {
			if ( in_array( $i, $this->error_container ))
        return $i;
		}
    return false;
	}

	function get_error_range ( $start, $end )
	{
		$ret = array();
		for ( $i=$start; $i<=$end; $i++) {
			if ( in_array( $i, $this->error_container ))
				array_push($ret, $i);
		}
		return $ret;
	}

	function throw_success ( $type )
	{
		_log ("ACTIVITY", "Success $type [".$this->success[$type]."]");
		array_push( $this->success_container, $type );
	}

	function check_success ( $type )
	{
		return in_array( $type, $this->success_container);
	}


	function print_error_range ( $start, $end )
	{
		$ret = '';
		for ( $i=$start; $i<=$end; $i++) {
			if ( in_array( $i, $this->error_container ))
				$ret .= $this->errors[$i] . "<br /><br />";
		}
		return $ret;
	}

	function print_success_text ( $type )
	{
		if ( in_array( $type, $this->success_container ))
			echo $this->success[$type];
		else
			return false;
	}





	/*
	 *  CRONES
	 */


	function run_crones ()
	{
		/*
		 * 	Methodo che, a seconda della data, lancia delle attività
		 */
		$this->crone__unsubscribe_players_attending ();
		$this->crone__check_user_actions ();
	}

	function crone__check_user_actions ()
	{
		global $user;

		//if ( in_array ( "UPDATE_USER_FIELDS", $user->actions) && strpos($_SERVER['PHP_SELF'], "area_personale") === false )
		//	header ("Location area_personale.php");
	}

	function crone__unsubscribe_players_attending ()
	{
		/*
		 * 	Questo metodo cicla tutti gli utenti con disiscrizioni in atto e li disiscrive del tutto
		 */
	}

	function crone__chiudi_sfida ( $sfida )
	{
		global $dm_sfide, $dm_utente, $dm_rewards;

		_log ("CRONE__chiudi_sfida", "************************************************** [ID  ".$sfida->id_sfida."]");
		$sfida = $this->create_sfida_obj ( $sfida->id_sfida );

		if ( $sfida->stato == 0 ) {
			// Sfida non lanciata. Faccio vincere l'utente più anziano
			deb ( "chiudo sfida " . $sfida->id_sfida . " in stato 0 tra " . $sfida->id_sfidante . " e " . $sfida->id_sfidato);
			$id_vincitore = $dm_utente->getIdUtenteAnziano ( $sfida->id_sfidante, $sfida->id_sfidato);
			$update =  new stdClass ();
			$update->id_vincitore = $id_vincitore;
			$update->id_sfida = $sfida->id_sfida;
			$update->a_tavolino = 1;
			$update->data_lancio = 'NOW()';
			$update->data_risposta = 'NOW()';
			$update->risultato = "'x,x'";
			$update->stato = ( $sfida->id_sfidante != $id_vincitore ) ? 4 : 5;

            // id_sfida on Update object is required
            $dm_sfide->updateObject ( "sfida", $update, array ("id_sfida" => $update->id_sfida ) );
		}

		if ( $sfida->stato == 1 ) {
			// Sfida lanciata. Faccio vincere id_sfidante
			_log ("CRONE__chiudi_sfida", "Stato 1 => Sfida lanciata ma non risposta, vince " . $dm_utente->getUsernameById( $sfida->id_sfidante ));
			$update =  new stdClass ();
			$update->id_vincitore = $sfida->id_sfidante;
            $update->id_sfida = $sfida->id_sfida;
			$update->a_tavolino = 1;
			$update->data_lancio = 'NOW()';
			$update->data_risposta = 'NOW()';
			$update->risultato = "'x,x'";
			$update->stato = 5;

			 // id_sfida on Update object is required
           $dm_sfide->updateObject ( "sfida", $update, array ("id_sfida" => $update->id_sfida ) );
		}

		if ( $sfida->stato == 2 ) {
			// Sfida risposta, aggiorno gli stati
			// Calcolo il risultato
			_log ("CRONE__chiudi_sfida", "Stato 2 => Sfida risposta, aggiorno stati e calcolo risultato");
			$match = $dm_sfide->getFullObjSfidaById ( $sfida->id_sfida );
			_log ("SFIDA::", "id sfidante: " . $match->id_sfidante . " (".$dm_utente->getUsernameById($match->id_sfidante)."), id_sfidato: " . $match->id_sfidato. " (".$dm_utente->getUsernameById($match->id_sfidato).")");
			_log ("SFIDA::", "id vincitore: " . $match->VINCITORE);
			$update =  new stdClass ();
			$update->risultato = "'" . $match->SFIDANTE->risultato . "," . $match->SFIDATO->risultato ."'";
			$update->id_vincitore = $match->VINCITORE;
            $update->id_sfida = $sfida->id_sfida;

            _log ("CRONE__chiudi_sfida", "Risultato: " . $update->risultato . ", Vincitore: " . $update->id_vincitore);
			$sfida->VINCITORE_REWARD = $match->VINCITORE;

			$this->reward_users ( $sfida );

            // id_sfida on Update object is required
            $dm_sfide->updateObject ( "sfida", $update, array ("id_sfida" => $update->id_sfida ) );
            //$this->crone__chiudi_sfida_actions ( $update );
		}
	}


	function reward_users ( $sfida ) {
		global $dm_sfide, $dm_rewards, $dm_utente, $user;

		// Estraggo tutte le variabili che mi possono servire
		if ( is_numeric($sfida) )
			$sfida = $dm_sfide->getSfidaById ($sfida);
		$sfidante = $dm_utente->getObjUtenteById ( $sfida->id_sfidante );
		$sfidato = $dm_utente->getObjUtenteById ( $sfida->id_sfidato );
		$sfidaUtenteSfidanteToday = $dm_sfide->getSfideUtenteToday ( $sfidante->id_utente );
		$sfidaUtenteSfidatoToday = $dm_sfide->getSfideUtenteToday ( $sfidato->id_utente );
		$sfideGiornaliereConcluseTraStessiUtenti = $dm_sfide->getSfideGiornaliereConcluseTraStessiUtenti ( $sfidante->id_utente, $sfidato->id_utente );
		$sfideSfidante = $dm_sfide->getSfideConcluseByIdUtente ( $sfida->id_sfidante );
		$sfideSfidato = $dm_sfide->getSfideConcluseByIdUtente ( $sfida->id_sfidato );

		$totale_sfidante = 0;
		$totale_sfidato = 0;

		if ( $sfida->VINCITORE_REWARD == $sfidante->id_utente ) $totale_sfidante += 3;
		if ( $sfida->VINCITORE_REWARD == $sfidato->id_utente ) $totale_sfidato += 3;
		if ( $sfida->VINCITORE_REWARD == 0 ) {
			$totale_sfidante += 1;
			$totale_sfidato += 1;
		}

		_log ("reward_users", "Sfidante: " . $sfidante->username );
		_log ("reward_users", "Sfidato: " . $sfidato->username );

		// Controllo punti e coppe da assegnare
		foreach ( $dm_rewards->getRewards () as $reward ) {

			$apply_sfidante = false;
			$apply_sfidato = false;

			switch ( $reward->key_id ) {

				/*
				 * Punti
				 */
				case "p_new_user":
					$apply_sfidante = ( time() - strtotime($sfidato->dta_reg)  < 7 * 24 * 60 * 60 );
					break;

				case "p_20_matches":
					$apply_sfidante = ( count ( $sfidaUtenteSfidanteToday ) >= 20 );
					$apply_sfidato = ( count ( $sfidaUtenteSfidatoToday ) >= 20 );
					break;

				case "p_first_day_match":
					$apply_sfidante = ( count ( $sfidaUtenteSfidanteToday ) == 1 );
					$apply_sfidato = ( count ( $sfidaUtenteSfidatoToday ) == 1 );
					break;

				case "p_10_matches_sameuser":
					$apply_sfidante = $apply_sfidato = ( count ( $sfideGiornaliereConcluseTraStessiUtenti ) >= 10 && count ( $sfideGiornaliereConcluseTraStessiUtenti ) < 20 );
					_log ("REWARD",  "Sfide giornaliere tra stessi user: " . count ( $sfideGiornaliereConcluseTraStessiUtenti ));
					break;

				case "p_20_matches_sameuser":
					$apply_sfidante = $apply_sfidato = ( count ( $sfideGiornaliereConcluseTraStessiUtenti ) >= 20 );
					_log ("REWARD",  "Sfide giornaliere tra stessi user: " . count ( $sfideGiornaliereConcluseTraStessiUtenti ));
					break;

				case "p_match_5xscore_user":
					$apply_sfidato = false;
					$apply_sfidante = ( $sfidato->punteggio_totale >= (5 * $sfidante->punteggio_totale ) );
					break;

				case "p_match_every_h":
					$h = 0;
					$apply_sfidante = true;
					if ( count ( $dm_sfide->get8HoursSfide ( $sfidante->id_utente ) ) >= 8 ) {
						for ( $i=0; $i<8; $i++ ) {
							if ( $i == 0 ) $h = $hour8_sfide_inrow[$i]->ORA + 1;
							if ( $h - $hour8_sfide_inrow[$i]->ORA != 1 )
								$apply_sfidante = false;
						}
					} else
						$apply_sfidante = false;
					$h = 0;
					$apply_sfidato = true;
					if ( count ( $dm_sfide->get8HoursSfide ( $sfidato->id_utente ) ) >= 8 ) {
						for ( $i=0; $i<8; $i++ ) {
							if ( $i == 0 ) $h = $hour8_sfide_inrow[$i]->ORA + 1;
							if ( $h - $hour8_sfide_inrow[$i]->ORA != 1 )
								$apply_sfidante = false;
						}
					} else
						$apply_sfidato = false;
					break;

				case "p_same_reg":
					$apply_sfidante = $apply_sfidato = ( $sfidante->regione != "" && $sfidante->regione != NULL && $sfidante->regione == $sfidato->regione );
					break;

				case "p_same_age":
					$apply_sfidante = $apply_sfidato = (
						$sfidante->data_nascita != "" && $sfidante->data_nascita != NULL &&
						$sfidato->data_nascita != "" && $sfidato->data_nascita != NULL &&
						date ("Y", strtotime($sfidante->data_nascita)) == date ("Y", strtotime ($sfidato->data_nascita))
					);
					break;


				/*
				 * Badges
				 */
				case "b_first_game":
					$apply_sfidante = ( !$user->has_badge_by_key ( $sfidante->id_utente, "b_first_game") && count ( $sfideSfidante ) >= 1 );
					$apply_sfidato = ( !$user->has_badge_by_key ( $sfidato->id_utente, "b_first_game") && count ( $sfideSfidato ) >= 1 );
					break;

				case "b_match_5xscore_user":
					$apply_sfidante = ( !$user->has_badge_by_key ( $sfidante->id_utente, "b_match_5xscore_user") && $sfidato->punteggio_totale >= $sfidante->punteggio_totale * 5);
					break;

				case "b_match_25xscore_user":
					$apply_sfidante = ( !$user->has_badge_by_key ( $sfidante->id_utente, "b_match_5xscore_user") && $sfidato->punteggio_totale >= $sfidante->punteggio_totale * 25);
					break;

				case "b_nofear":
					$apply_sfidante = ( !$user->has_badge_by_key ( $sfidante->id_utente, "b_nofear") && $sfidato->punteggio_totale > $sfidante->punteggio_totale );
					break;

				default;
			}

			if ( $apply_sfidante === true ) {
				_log ("reward_users", "Sfidante: applico " . $reward->nome );
				$this->apply_reward ( $sfida, $reward, $sfidante );
				$totale_sfidante += $reward->score;
			}

			if ( $apply_sfidato === true ) {
				_log ("reward_users", "Sfidato: applico " . $reward->nome );
				$this->apply_reward ( $sfida, $reward, $sfidato );
				$totale_sfidato += $reward->score;
			}

		}

		$dm_utente->addScore ( $sfidante, $totale_sfidante );
		$dm_utente->addScore ( $sfidato, $totale_sfidato );
        $dm_sfide->updatePunteggiVinti ($sfida, $totale_sfidante, $totale_sfidato);
	}

	function apply_reward ( $sfida, $reward, $user_r ) {
		global $dm_utente, $dm_sfide, $dm_rewards;

		_log ("REWARD",  "*** APPLICO {$reward->nome} per ". $user_r->id_utente."***");
		if ( !$dm_rewards->rewardExists ( $sfida->id_sfida, $reward->id_reward, $user_r->id_utente ) )
			if ( $dm_rewards->applyReward ( $sfida, $user_r, $reward ) )
				_log ("REWARD",  "[DONE]");
			else
				_log ("REWARD",  "[ERROR APPLYing]");
		else {
			_log ("REWARD", "[SKIP, ALREADY PRESENT]");
			$this->skip_reward ( $reward );
		}
	}

	function skip_reward ( $reward ) {
		global $dm_rewards;

		_log ("REWARD",  "*** SKIP ***");
	}

}


?>