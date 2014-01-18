<?php
$include_path = '../';
chdir($include_path);
require_once ( "core.php" );
//error_reporting(E_ALL);

if ( isset ($_REQUEST['action'])) {
	$return = '';

	switch ($_REQUEST['action']) {

		case 'get_user_points':
			echo $user->get_points ( $user->obj->id_utente );
			break;

        case 'get_xml_sfida':
            header ("content-type: text/xml");

            $objUtente = $dm_utente->getObjUtenteById ( $user->obj->id_utente );
            $objFullSfida = $dm_sfide->getFullObjSfidaById( $_REQUEST['id_sfida'] );
            $objUtenteSfidante = $dm_utente->getObjUtenteById ( $objFullSfida->id_sfidante );
            $objUtenteSfidato = $dm_utente->getObjUtenteById( $objFullSfida->id_sfidato );
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            ?><game>
                <settings delayAfterShoot_time="2000" totalShots="10" shooter="player1" firstShooter="player1" keeper="player2" firstKeeper="player2" transitionTime=".6" currentShoot="1" />
                <players>
                    <player name="<? echo $objUtenteSfidante->username; ?>" number="<? echo $objUtenteSfidante->numero_maglietta; ?>" whatcher="<? if ($objUtenteSfidante->id_utente==$objUtente->id_utente) echo "true"; else echo "false"; ?>">
                        <skin calzini="<? echo str_replace("#", "0x",$objUtenteSfidante->colore_calzini); ?>" maglia="<? echo str_replace("#", "0x",$objUtenteSfidante->colore_maglietta); ?>" pantaloni="<? echo str_replace("#", "0x",$objUtenteSfidante->colore_pantaloncini); ?>" tipoMaglia="<? echo $objUtenteSfidante->tipo_maglietta; ?>"/>
                        <shoots>
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o1); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o2); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o3); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o4); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->tiri->o5); ?>" />
                        </shoots>
                        <keeps>
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o1); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o2); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o3); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o4); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDANTE->parate->o5); ?>" />
                        </keeps>
                    </player>
                    <player name="<? echo $objUtenteSfidato->username; ?>" number="<? echo $objUtenteSfidato->numero_maglietta; ?>" whatcher="<? if ($objUtenteSfidato->id_utente==$objUtente->id_utente) echo "true"; else echo "false"; ?>">
                        <skin calzini="<? echo str_replace("#", "0x",$objUtenteSfidato->colore_calzini); ?>" maglia="<? echo str_replace("#", "0x",$objUtenteSfidato->colore_maglietta); ?>" pantaloni="<? echo str_replace("#", "0x",$objUtenteSfidato->colore_pantaloncini); ?>" tipoMaglia="<? echo $objUtenteSfidato->tipo_maglietta; ?>"/>
                        <shoots>
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o1); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o2); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o3); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o4); ?>" />
                            <shoot target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->tiri->o5); ?>" />
                        </shoots>
                        <keeps>
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o1); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o2); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o3); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o4); ?>" />
                            <keep target="<? echo $utility->retCorrTiroParata($objFullSfida->SFIDATO->parate->o5); ?>" />
                        </keeps>
                    </player>
                </players>
            </game><?
            break;

		case 'checkUnreadMessages':
			$return = count ( $dm_messaggi->getArrObjMessaggiUnread ( $user->obj->id_utente ) );
			break;

		case 'checkOpenSfide':
			$return = count ( $user->obj->sfide_da_giocare );
			break;

		case 'readMessage':
			$dm_messaggi->markAsReadById ($_REQUEST['id_mess']);
			$return = utf8_encode ($dm_messaggi->getMessaggioById ($_REQUEST['id_mess'])->testo);
			break;

		case 'getUserList':
			$return = "[";
			foreach ($user->user_list as $obj) {
				if ( strpos (strtolower($obj->username), strtolower($_REQUEST['term'])) !== false)
					$return .= '"' . $obj->username . '",';
			}
			$return = substr($return, 0, strlen($return)-1);
			$return .= "]";
			break;

		case 'get_filtered_messages':
			$messaggi = $dm_messaggi->getFilteredUnbannedMessaggi ( $_REQUEST["start"], $_REQUEST["end"] );
			echo $user->print_messages_row ($messaggi);
			break;

		case 'get_page':
			if ( isset ($_REQUEST['key'])) {
				$content = $core->loag_page_content ($_REQUEST['key']);
				print_r(json_encode ($content));
			}
			break;

		case 'getProvinceByRegione':
			echo '<option value="*">-- tutte le province --</option>';
			foreach ( $utility->get_province_by_region($_REQUEST['reg']) as $provincia ) {
				echo '<option value="'.$provincia->sigla.'">'.$provincia->nome.'</option>';
			}
			break;

		case 'get_user_by_fb_id':
			$fbid = $_REQUEST["fbid"];
			$uobj = $user->get_user_by_fbid ( $fbid );
			if ( count ($uobj) > 0 && $uobj != false )
				echo $uobj->id_utente;
			else
				echo "KO";
			break;

		case 'register_user_session':
			$fbid = $_REQUEST["fbid"];
			if ( $user->do_login_by_id ($fbid) !== false )
				echo "OK";
			else
				echo "KO";
			break;

		case 'reset-session-log':
			$_SESSION['rigorix']['log'] = '';
			break;

		case 'reset-session':
			$_SESSION['rigorix'] = array();
			break;

		case 'put-in-session':
			if ( $_SESSION['rigorix'][$_REQUEST["session-var-name"]] = $_REQUEST["session-var-value"] )
				echo "OK";
			else
				echo "KO";
			break;

		case 'get_sfida_reward':
			if ( $activity->has_error_range (500, 510) ):
				$return = $activity->activity_response_handler ( 500, 510 );
			else:
				$sfida = $dm_sfide->getFullObjSfidaById ($_REQUEST["id_sfida"]);
				$rewards_sfide = $dm_rewards->getUserRewardByIdSfida ( $user->obj->id_utente, $sfida->id_sfida );
                $utente_sfidante = $user->createUserObject ($sfida->id_sfidante);
                $utente_sfidato = $user->createUserObject ($sfida->id_sfidato);
				if ( $sfida->VINCITORE == 0 )
					$txt_result = "pareggio";
				else if ( $sfida->VINCITORE == $user->obj->id_utente )
					$txt_result = "vittoria";
				else
					$txt_result = "sconfitta";
				if ( $txt_result == "pareggio" )
					$punti_game = 1;
				else if ( $txt_result == "vittoria")
					$punti_game = 3;
				else
					$punti_game = 0;
				$totale_punti = $punti_game;
				?>

				<style type="text/css">
				@import "/bootstrap/css/old.bootstrap.min.css";
				@import "/css/common.css";
                @import url(http://fonts.googleapis.com/css?family=Press+Start+2P);

				.game-result { width: 445px !important; overflow: hidden; background: #fff url(/i/bg_result_view.gif) repeat-x left bottom; }
				.game-result .game-result-image { background: #fff url(/i/result_win.jpg) no-repeat center 1px; height: 170px; position: relative; }
				.game-result.pareggio .game-result-image { background-image: url(/i/result_draw.jpg); }
				.game-result.sconfitta .game-result-image { background-image: url(/i/result_lose.jpg); }
                .game-result-vedi { position: absolute; bottom: 0; right: 0; background: #000; padding: 4px 8px; }
                .game-result-vedi a { color: #fff; font-size: 10px; text-transform: uppercase; }
                .game-result-vedi a:hover { color: #2e95bd; }
				.game-result .user { width: 100%; height: 120px; overflow: hidden; margin: 0; padding: 10px 0 0 0; background: #f3f3f3; }

                .game-result .user-score-box { position: relative; height: 110px; background: url(/i/star.gif) no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; }
                .game-result .user-score-box .user-score-box-username { overflow: hidden; display: block; width: 100%; position: absolute; bottom: 0; left: 0; padding: 3px 0; text-align: center; background: rgba(0,0,0,.5); color: #fff; }
                .game-result .user-score-box .user-score-box-username span { padding: 3px 8px; }
                .game-result .sfida-score-box { background: #000; height: 110px; color: #4ed803; font-size: 45px; text-align: center; line-height: 110px; font-family: 'Press Start 2P',cursive;  }
                .game-result .sfida-score-box .sfida-score-sep { color: #ffea00; margin-left: 4px; font-size: 30px; text-align: center; line-height: 110px; font-family: 'Press Start 2P',cursive; }

				.rewards { background: #fff; padding: 10px;  }
				.reward-item { text-align: left; padding: 3px 0 6px 0px; }
				.reward-item.punto { background: url(/i/star.gif) no-repeat left top; }
			    </style>
                <script>
                    function vediSfidaGiocata (id_sfida) {
                        Game.loadDialog ({
                            w: 635,
                            h: 508,
                            buttons: {
                                "CHIUDI": function() {
                                    Game.unloadDialog ();
                                }
                            },
                            dialogClass: 'game-view-dialog',
                            src: '/boxes/dialog_sfida_vedi.php?id_sfida=' + id_sfida,
                            title: "Vedi la sfida giocata"
                        });
                        return false;
                    }
                </script>

				<div class="game-result <?php echo $txt_result; ?>">
					<div class="game-result-image">
                        <div class="game-result-vedi">
                            <a href="javascript:vediSfidaGiocata('<?=$sfida->id_sfida; ?>');">Guarda la sfida!!</a>
                        </div>
					</div>
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="user-score-box" style="background-image: url(<?php echo $user->get_user_picture_uri ($utente_sfidante); ?>)">
                                <p class="user-score-box-username">
                                    <span text-fit-horizontal="true" text-max-size="20"><?php echo $utente_sfidante->username; ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="sfida-score-box">
                                <?php echo str_replace(",", "<span class='sfida-score-sep'>:</span>", $sfida->risultato); ?>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="user-score-box" style="background-image: url(<?php echo $user->get_user_picture_uri ($utente_sfidato); ?>)">
                                <p class="user-score-box-username">
                                    <span text-fit-horizontal="true" text-max-size="20"><?php echo $utente_sfidato->username; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
					<div class="rewards">
						<div class="reward-item <?php echo $txt_result; ?>">
							<span class="label label-warning"><?php echo strtoupper($txt_result); ?>: </span> &nbsp;<strong>Ricevi <?php echo $punti_game; ?> <?php echo ( $punti_game == 1 ) ? " punto" : " punti"; ?></strong>
						</div>
						<?php foreach ( $rewards_sfide as $reward_sfida ): $reward = $dm_rewards->getRewardById ( $reward_sfida->id_reward ); $totale_punti += $reward->score; ?>

							<div class="reward-item <?php echo $reward->tipo; ?>">
							<?php if ( $reward->tipo == "punto" ): ?>

								<span class="label label-warning"><?php echo $reward->nome; ?>: </span> <strong>Ricevi <?php echo $reward->score; ?> punti</strong>

							<?php elseif ( $reward->tipo == "badge" ): ?>

								<table width="100%">
									<tr>
										<td><?php $user->print_user_badge ( $reward ); ?></td>
										<td>
											 <h4><?php echo $reward->nome; ?></h4>
											 <p><?php echo $reward->descrizione; ?></p>
										</td>
									</tr>
								</table>

							<?php endif; ?>
							</div>

						<?php endforeach; ?>
						<div class="reward-item <?php echo $txt_result; ?>">
							<span class="reward-name"><strong>TOTALE: <span class="badge badge-success"><?php echo $totale_punti; ?></span> punti</strong>
						</div>
					</div>
				</div>
                <script>activity.ui.set_custom_elements(); </script>
				<?php
			endif;

		case null:
			// SKIP
			break;

		default:
			break;

	}

	echo utf8_encode ($return);
}

if ( isset ($_REQUEST['activity'])) {
	$return = '';

	switch ($_REQUEST['activity']) {

		/*
		 * 	Queste request passano attraverso l'activity context, quindi il metodo migliore
		 *  è vedere se c'è il success o l'error relativo al tipo di attività
		 */

		case 'write_message':
			$return = $activity->activity_response_handler ( 102 );
			break;

		case 'password_recovery':
			$return = $activity->activity_response_handler ( 103 );
			break;

		case 'delete_message':
			$return = $activity->activity_response_handler ( 104 );
			break;

		case 'delete_multi_message':
			$return = $activity->activity_response_handler ( 104 );
			break;

		case 'ignore_user_messages':
			$return = $activity->activity_response_handler ( 105 );
			break;

		case 'multimark_as_read':
			$return = $activity->activity_response_handler ( 106 );
			break;

		case 'upload_profile_picture':
			$return = $activity->activity_response_handler ( 440, 441 );
			break;

		case 'update_user_data':
			$return = $activity->activity_response_handler ( 110 );
			break;

		case 'update_mascotte':
			$return = $activity->activity_response_handler ( 115 );
			break;

		case 'unsubscribe_user':
			$return = $activity->activity_response_handler ( 120, 121 );
			break;

		case 'lancia_sfida':
			$return = $activity->activity_response_handler ( 300, 310 );
			break;

		case 'rispondi_sfida':
			$return = $activity->activity_response_handler ( 300, 310 );
			break;

		default:
			$return = '{ "status": "OK", "type": "undefined-activity", "activity-name": "' . $_REQUEST['activity'] . '" }';
			_log ("UNHANDLED ACTIVITY", $return);
			break;
	}

	echo utf8_encode ($return);

}

?>