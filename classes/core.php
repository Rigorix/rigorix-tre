<?php
session_start();
//error_reporting(E_ALL);
//ini_set( 'display_errors','1');

$__DEBUG_SCRIPT = false;

// GET environment conf
$env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env'));

// REQUIRED CLASSES AND CONFIGURATIONS
require_once( __DIR__ . '/fastjson.php' );
require_once( __DIR__ . '/restclient.php' );
require_once( __DIR__ . '/utility.class.php' );
$utility = new utility();
require_once( __DIR__ . '/config.php' );
require_once( __DIR__ . '/../dm/dm_generic_mysql.php' );
require_once( __DIR__ . '/../dm/dm_utente.php' );
require_once( __DIR__ . '/../dm/dm_sfide.php' );
require_once( __DIR__ . '/../dm/dm_messaggi.php' );
require_once( __DIR__ . '/../dm/dm_rewards.php' );
require_once( __DIR__ . '/mailer/class.phpmailer.php' );
require_once( __DIR__ . '/../hybridauth/Hybrid/Auth.php' );

_syslog("Finito caricamento classi e configurazioni");


// MAIN CONTEXTS
require_once( __DIR__ . '/user.context.php' );				_syslog("Caricato user context");
require_once( __DIR__ . '/activities.context.php' );		_syslog("Caricato activity context");

$db		 		    = new dm_generic_mysql( $db_conn, $db_name, $sql_debug );
$dm_utente 		= new dm_utente( $db_conn, $db_name, $sql_debug );
$dm_sfide 		= new dm_sfide( $db_conn, $db_name, $sql_debug );
$dm_messaggi	= new dm_messaggi( $db_conn, $db_name, $sql_debug );
$dm_rewards		= new dm_rewards( $db_conn, $db_name, $sql_debug );

_syslog("Istanziamento DataManager DM: OK");

$activity = new activities();
$core = new core();
$user = new user();
$mail = new PHPMailer(true);
$api = new RestClient(array(
  'base_url' => substr($env->API_DOMAIN, 0, -1)
));

_syslog("Istanziamento CLASSES: OK");

$core->start();

_syslog("Core->start(): OK");

class core {

	function core()
	{
	}

	function start()
	{
		// Prendo le variabili dalla sessione. Sono state precedentemente impostate dal CONFIG
		$this->check_social_login ();
		$this->get_session_properties ();
		$this->developer_tools ();
		$this->get_core_vars ();
		$this->check_activities ();
		$this->user_init ();
		$this->game_start ();
	}

	function not_internal_url ()
	{
		return ( $_SERVER["PHP_SELF"] != "/compleate_registration.php" );
	}

	function check_social_login()
	{
		global $env, $api;

    if ( isset($_GET['id']) && isset($_GET['token']) && $_GET['token'] != ""):

      $result = $api->get("users/bysocial/" . $_GET['id']);
      if ($result->info->http_code == 200 ) {
        $user = $result->decode_response();
        $_SESSION['rigorix_logged_user'] = $user->id_utente;
        $_SESSION['rigorix_logged_user_token'] = $_GET['token'];
        header('Location: /');
      }

    endif;

	}

	function get_core_vars()
	{
		// Prendo tutte le variabili che serviranno in generale in giro per il sito
		// es: NUMERO_UTENTI_REGISTRATI, NUMERO_UTENTI_ONLINE

		global $user;
		$this->storage['NUMERO_UTENTI_REGISTRATI'] = $user->get_num_utenti_registrati ();
		$this->storage['NUMERO_UTENTI_ONLINE'] = $user->get_num_utenti_online ();
		$this->storage['NUMERO_USERNAME_ONLINE'] = $user->get_username_online ();
		$page_array = explode (".", substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1));
		$this->page_key = $page_array[0];
	}

	function db_connection()
	{
		$this->set_session_properties( "db=>DB_CONN", mysql_pconnect($this->db['DB_HOST'], $this->db['DB_USER'], $this->db['DB_PWD']));
		mysql_select_db($this->db['DB_NAME']);
	}

	function check_activities ()
	{
		global $activity, $user;

		if ( isset ($_REQUEST['activity']) && $_REQUEST['activity'] != '') {
			$activity->start ( $_REQUEST['activity'] );
			$user->update_data_activ ();
		}

		$activity->run_crones ();
	}

	function user_init()
	{
		// Inizializzazione utente
		global $user, $request_login;

		if ( !$user->is_logged && $this->is_restriction_page() && ( !isset ($request_login) || $request_login == true ) && strpos($_SERVER['PHP_SELF'], "registrazione") === false ) {
			header("Location: index.php");
			exit();
		}
		if ( $user->is_logged ) {
			// Check completo dell'utente loggato per assicurarmi che sia tutto ok.
			if ( $user->logged_user_checkup () ):
				if ( !$user->is_active() && !strpos( $_SERVER['PHP_SELF'], "compleate_registration.php")):
					header ("Location: compleate_registration.php");
				endif;
			else:
				$user->do_logout ();
			endif;
		}
	}

	function game_start()
	{
		// Parte all'avvio del gioco, dopo che tutto è stato caricato
	}

	function is_restriction_page ()
	{
		return strpos( $_SERVER['PHP_SELF'], "area_personale.php");
	}

	function loag_page_content ( $key )
	{
		global $db;

		return $db->getSingleArrayQueryCustom ( "select * from static_pages where context = '$key'");
	}

	function get_session_properties()
	{
//    var_dump($_SESSION['rigorix_logged_user']);
    if (isset($_SESSION['rigorix_logged_user']) && $_SESSION['rigorix_logged_user'] != null)
      $loggedUserObject = Users::find($_SESSION['rigorix_logged_user']);
    else
      $loggedUserObject = false;

		foreach ( $_SESSION['rigorix'] as $setting => $setting_val ) {
			$this->$setting = $setting_val;
		}
	}

	function set_session_properties( $key, $value )
	{

	}

	function render_banner ( $position )
	{
    global $env;

    if ( $env->ADV === true ):

      echo '<script type="text/javascript">google_ad_client = "ca-pub-8716025678520095";';
      switch ( $position ) {
        case "Top":
          echo '// Rigorix_728x90
          google_ad_slot = "7188606148";
          google_ad_width = 728;
          google_ad_height = 90;
          </script>';
        break;

        case "Square":
          echo '// Rigorix_250x250
          google_ad_slot = "5475978463";
          google_ad_width = 250;
          google_ad_height = 250;
          </script>';
        break;

        case "Middle":
          echo '// Rigorix_120x600
          google_ad_slot = "4856957565";
          google_ad_width = 120;
          google_ad_height = 600;
          </script>';
        break;
      }
      echo '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';

    endif;
	}

	function render_box( $box_content, $title = false )
	{
		echo '<div class="ui-box ui-box-content ui-corner-all">';
			echo ($title != false ? '<div class="ui-box-title">' . $title . '</div>' : "");
			echo '<div class="ui-box-content-html">';
			include ( "boxes/" . $box_content );
			echo '</div>';
		echo '</div>';
	}

	function render_flat_box( $box_content )
	{
		include ( "boxes/" . $box_content );
	}

	function render_db_static_page( $page_context )
	{
		global $db;
		$page = $db->getSingleObjectQueryCustom("SELECT * FROM static_pages WHERE context = '$page_context'");
		echo '<div class="ui-box ui-box-content ui-corner-all">';
			echo '<div class="ui-box-title">' . $page->title . '</div>';
			echo '<div class="ui-box-content-html">';
			echo $page->content;
			echo '</div>';
		echo '</div>';
	}

	function render_box_unpadded( $box_content, $title = false )
	{
		echo '<div class="ui-box ui-box-content ui-corner-all">';
			echo ($title != false ? '<div class="ui-box-title">' . $title . '</div>' : "");
			include ( "boxes/" . $box_content );
		echo '</div>';
	}

	function render_box_highlight( $box_content, $title = false )
	{
		echo '<div class="ui-box-highlight ui-box-highlight-content ui-corner-all">';
			echo ($title != false ? '<div class="ui-box-highlight-title">' . $title . '</div>' : "");
			echo '<div class="ui-box-highlight-content-html">';
			include ( "./boxes/" . $box_content);
			echo '</div>';
		echo '</div>';
	}

	function render_user_alerts ( )
	{
		global $activity;
		if ( count ( $activity->alerts_container ) > 0 ):
            echo "<ul>";
			foreach ( $activity->alerts_container as $error_code )
				echo "<li>" . $activity->alerts[$error_code] . "</li>";
            echo "</ul>";
		endif;
	}

	function setup ( $key )
	{
		return $this->$key;
	}

	function print_session_var ( $context, $key )
	{
		echo $this->{$context}[$key];
	}

	function send_mail ( $to, $subject, $text )
	{
		global $mail;
		try {
			$mail->IsSMTP();            // send via SMTP
			$mail->SMTPDebug= 1;
			$mail->Host     = "ml.rigorix.com"; 	// SMTP servers
			// $mail->Host     = "mail.rigorix.com";	// SMTP servers
			$mail->SMTPAuth = true;     // turn on SMTP authentication
			$mail->Username = "newsletter@ml.rigorix.com";  // SMTP username
			$mail->Password = "!newsletter00"; // SMTP password

			$mail->From     = 'noreply@rigorix.com';
			$mail->FromName = 'Rigorix';

			$mail->AddAddress("contact@rigorix.com", "RigoriX");               // optional name
			$mail->AddReplyTo('contact@rigorix.com');
			$mail->WordWrap = 60;           // set word wrap
			$mail->IsHTML(true);                               // send as HTML
			$mail->Subject  =  $subject;

			$mail->MsgHTML( $text );

			$mail->AddAddress( $to);
			return $mail->Send();

		} catch (phpmailerException $e) {
			echo $e->errorMessage();
		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}

	function send_message ( $id_utente, $subject, $text )
	{
		global $dm_messaggi, $activity;

		$msg = array (
			"indb_id_sender" => 0,
			"indb_id_receiver" => $id_utente,
			"indb_oggetto" => $subject,
			"indb_testo" => $text,
			"indb_dta_mess" => "_V_NOW_",
		);
		$msg = $dm_messaggi->makeInDbObject ($msg);
		if ($dm_messaggi->insertObject( 'messaggi', $msg )) {
			$activity->throw_success ( 102 );
			return true;
		} else {
			$activity->throw_error( 102 );
			return false;
		}
	}


	function developer_tools ()
	{
		// Metodo attivato in modalità sviluppatore
	}
}

function deb ( $message, $key = '' )
{
	global $core;

	if ( $core->settings["INLINE_DEBUG"] == true )
		echo "$key> $message<br />";
	else
		$_SESSION['rigorix']['log'] .= "$key> $message<br />";
}

function _log ( $context, $log )
{
	global $core, $user;

	if ( $core->settings["LOG_FILE"] == "" )
		$core->settings["LOG_FILE"] = "log_generic.txt";

	if ( !is_file($core->settings["LOG_FILE"]) )
		touch($core->settings["LOG_FILE"]);

	$fc = fopen($core->settings["LOG_FILE"], 'a') or die ("can't open errorlog file (".$core->settings["LOG_FILE"].")");
	fwrite($fc, '
'.date("H:i:s").' '.($user->is_logged ? "[U ".$user->obj->id_utente."]" : "").' '.$context.'> ' . $log);
	fclose($fc);
}

function _syslog ( $log )
{
	global $__DEBUG_SCRIPT;
	if ( $__DEBUG_SCRIPT === true )
		print_r( "<br>->" . $log);
}
?>