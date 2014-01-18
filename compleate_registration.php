<?
require_once('classes/core.php');

$user->do_login_by_id ( $user->obj->id_utente );

if ( $user->is_active() )
	header ("Location: index.php?activity=login_by_id&id=" . $user->obj->id_utente );

require_once ('boxes/page_start.php');
?>

	<div class="rx-layout-col-large">

		<!-- Colonna sinistra * corpo pagina -->
		<div class="rx-layout-col-container">

			<div class="ui-box ui-box-content ui-corner-all">
				<div class="ui-box-title">Completa la registrazione</div>
				<div class="ui-box-content-html">

                    <?php if ( count ( $activity->alerts_container ) > 0 ): ?>
                        <div class="callout callout-danger">
                            <h4 class="text-error">ATTENZIONE:</h4>
                            <p class="text-error">
                                <?php $core->render_user_alerts (); ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if (count ($activity->get_error_range( 411, 428 )) > 0): ?>
                        <div class="callout callout-danger">
                            <h3 class="text-error">ATTENZIONE:</h3>
                            <p class="text-error">
                                <ul>
                                <?php foreach ($activity->get_error_range( 410, 428) as $error): ?>
                                    <?php echo "<li class='text-error'>" . $activity->errors[$error] . '</li>'; ?>
                                <?php endforeach; ?>
                            </ul>
                            </p>
                        </div>
                    <?php endif; ?>

                    <div class="row-fluid">
                        <div class="span5">
                            <?php require_once ("boxes/user_badge.php"); ?>
                        </div>
                        <div class="span7">
                            <div class="callout callout-warning">
                                <p class="">
                                    Carissimo <strong><?php echo $user->obj->username; ?></strong>, <br />
                                    non ci risulta ancora nessun utente collegato a questo account <?php echo $user->obj->social_provider; ?>.<br /><br />
                                    Se desideri registrarti, compila il form qui di seguito: bastano 10 secondi!
                                </p>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-info">STAI PER CREARE UN UTENTE RIGORIX TRAMITE L'ACCOUNT DI <?php echo strtoupper($user->obj->social_provider); ?></h5>

                    <div class="row-fluid mtll">
                        <div class="span12">
                            <form action="?activity=complete_registration" method="post" class="form-horizontal">
                                <input type="hidden" name="id_utente" value="<?php echo $user->obj->id_utente; ?>" />
                                <input id="email" type="hidden" name="email" value="<?php echo $user->obj->email ?>" placeholder="E-mail" />
                                <input id="email" type="hidden" name="conf_email" value="<?php echo $user->obj->email ?>" placeholder="E-mail" />

                                <div class="control-group">
                                    <label class="control-label" for="username">Username</label>
                                    <div class="controls">
                                        <input id="username" type="text" name="username" maxlength="20" value="<?php echo str_replace(" ", "_", $user->obj->username) ?>" placeholder="Username" />
                                        <p class="text-warning">(minimo 3 - massimo 20 caratteri alfa/numerici. No spazzi)</p>
                                    </div>
                                </div>

                                <?php if ( $user->obj->email == "" ): ?>
                                    <div class="control-group">
                                        <label class="control-label" for="email">Email</label>
                                        <div class="controls">
                                            <input id="email" type="text" name="email" value="<?php echo $user->obj->email ?>" placeholder="E-mail" />
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="confermaemail">Conferma Email</label>
                                        <div class="controls">
                                            <input id="confermaemail" type="text" name="conf_email" value="<?php echo $user->obj->email ?>" placeholder="Conferma E-mail" />
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="control-group">
                                    <div class="controls">
                                        <button class="btn btn-success mrm">Registrati</button>
                                        <a href="index.php?activity=logout" class="btn btn-danger mrm">Annulla accesso</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

				</div>
			</div>
		</div>
		<!-- Fine colonna sinistra * corpo pagina -->
		<div class="clr"></div>

	</div>

	<div class="rx-layout-col-right">
		<!-- Colonna destra * corpo pagina -->
		<div class="rx-layout-col-container">

			<?php $core->render_box_unpadded ( "classifica_spalla.php", "Ranking utenti" ); ?>

		</div>
		<!-- Fine colonna destra * corpo pagina -->

	</div>
	<div class="rx-layout-col-extreme-right">

			<!-- Colonna destra banner * corpo pagina -->
			<div class="rx-layout-col-container">

				<?php $core->render_banner ("Middle"); ?>

			</div>
			<!-- Fine colonna destra banner * corpo pagina -->

	</div>
	<div class="clr"></div>

<?php
require_once ('boxes/page_end.php');
?>