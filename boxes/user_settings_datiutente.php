<?php
chdir("../");
require_once ( "classes/core.php");
?>
<div class="ui-box-content-html main-pane">
	<!-- p>Da questa pagina puoi aggiornare i tuoi dati personali.</p>
	<br /-->

	<form name="aggiorna_utente" class="skinned" onsubmit="return false;" style="display: inline">

	<div class="ui-box ui-box-content ui-corner-all ui-box-unpadded">
		<div class="ui-box-title">Informazioni generali</div>

			<table class="form-table">
				<tr valign="top">
					<td valign="top" nowrap="nowrap" rowspan="20" width="200px">
						<img id="profile_picture_big" src="<?php echo $user->get_user_picture_uri ($user->obj); ?>" width="100%" vspace="3" />
						<div align="center">
							<a href="#" class="user-picture link" title="Cambia l\'immagine del tuo profilo" name="add-profile-picture">Cambia foto</a>
						</div>
					</td>
					<td colspan="2"></td>
				</tr>
				<tr class="alt">
					<td nowrap="nowrap"><label>Username</label></td>
					<td><strong><?php echo $user->obj->username; ?></strong></td>
				</tr>
				<tr>
					<td nowrap="nowrap"><label>Social</label></td>
					<td><a class="link" target="_blank" href="<?=$user->obj->social_url?>"><?=$user->obj->social_provider?></a></td>
				</tr>
				<tr class="alt">
					<td nowrap="nowrap"><label>Nome <sup>*</sup></label></td>
					<td><input validate_as="mandatory" type="text" name="indb_nome" value="<?=$user->obj->nome?>" /></td>
				</tr>
				<tr>
					<td nowrap="nowrap"><label>Cognome <sup>*</sup></label></td>
					<td><input validate_as="mandatory" type="text" name="indb_cognome" value="<?=$user->obj->cognome?>" /></td>
				</tr>
				<!-- tr>
					<td nowrap="nowrap"><label>Provincia <sup>*</sup></label></td>
					<td><select validate_as="mandatory" name="indb_prov" style="width: 130px;">
					<option value="">-- tutte le province --</option>
					<?php
					foreach ( $utility->get_province_list() as $prov ) {
						if ( $user->obj->prov == $prov->sigla)
							echo '<option value="'.$prov->sigla.'" selected="selected">'.$prov->nome.'</option>';
						else
							echo '<option value="'.$prov->sigla.'">'.$prov->nome.'</option>';
					}
					?>
					</select></td>
					<td nowrap="nowrap"><label>Nazione</label></td>
					<td><select name="indb_nazione" style="width: 130px;">
					<option>-- tutte le nazioni --</option>
					<?php
					foreach ( $utility->get_nazioni_list() as $naz ) {
						if ( $user->obj->nazione == $naz->sigla)
							echo '<option value="'.$naz->sigla.'" selected="selected">'.$naz->nome.'</option>';
						else
							echo '<option value="'.$naz->sigla.'">'.$naz->nome.'</option>';
					}
					?>
					</select></td>
				</tr -->
				<tr class="alt">
					<td nowrap="nowrap"><label>Email <sup>*</sup></label></td>
					<td colspan="3"><input validate_as="email" type="text" size="40" name="indb_email_utente" value="<?= ( $user->obj->email_utente != "") ? $user->obj->email_utente : $user->obj->email?>" /></td>
				</tr>
				<tr>
					<td nowrap="nowrap"><label>Sesso <sup>*</sup></label></td>
					<td><select name="indb_sesso">
						<option value="M" <? echo ($user->obj->sesso == "M") ? 'selected="selected"' : ''; ?>>Maschio</option>
						<option value="F" <? echo ($user->obj->sesso == "F") ? 'selected="selected"' : ""; ?>>Femmina</option>
					</select></td>
				</tr>
				<tr class="alt">
					<td nowrap="nowrap"><label>Data di nascita <sup>*</sup></label></td>
					<td>
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                            <input class="span2" style="font-size: 12px" type="text" name="indb_data_nascita" value="<?=$utility->parseDbDateToString ($user->obj->data_nascita)?>" />
                        </div>

                    </td>
				</tr>
			</table>
        <div class="text-center mal">
            <button name="aggiornamento-dati-utente" class="btn btn-success"><i class="icon-ok"></i> AGGIORNA DATI</button>
        </div>
        <!--
        <div class="row-fluid">
                <div class="span4">
                    <img id="profile_picture_big" src="<?php echo $user->get_user_picture_uri ($user->obj); ?>" width="100%" vspace="3" />
                    <div align="center">
                        <a href="#" class="user-picture link" title="Cambia l\'immagine del tuo profilo" name="add-profile-picture">Cambia foto</a>
                    </div>
                </div>
                <div class="span8">
                    <div class="form-horizontal">
                        <fieldset disabled>
                            <label class="span3">Username</label>
                            <div class="span8">
                                <input type="text" data- value="<?php echo $user->obj->username; ?>">
                            </div>
                        </fieldset>
                        <div class="row-spacer"></div>
                        <fieldset class="">
                            <label class="span3">Social</label>
                            <div class="span8">
                                <label>
                                    <a class="link" target="_blank" href="<?=$user->obj->social_url?>"><?=$user->obj->social_provider?></a>
                                </label>
                            </div>
                        </fieldset>
                        <div class="row-spacer"></div>
                        <fieldset>
                            <label class="span3">Nome *</label>
                            <div class="span8">
                                <input validate_as="mandatory" type="text" name="indb_nome" value="<?=$user->obj->nome?>" />
                            </div>
                        </fieldset>
                        <div class="row-spacer"></div>
                        <fieldset>
                            <label class="span3">Cognome *</label>
                            <div class="span8">
                                <input validate_as="mandatory" type="text" name="indb_cognome" value="<?=$user->obj->cognome?>" />
                            </div>
                        </fieldset>
                        <div class="row-spacer"></div>
                        <fieldset>
                            <label class="span3">Email *</label>
                            <div class="span8">
                                <input validate_as="email" type="text" size="40" name="indb_email" value="<?=$user->obj->email?>" />
                            </div>
                        </fieldset>
                        <div class="row-spacer"></div>
                        <fieldset>
                            <label class="span3">Sesso *</label>
                            <div class="span8">
                                <select name="indb_sesso">
                                    <option value="M" <? echo ($user->obj->sesso == "M") ? 'selected="selected"' : ''; ?>>Maschio</option>
                                    <option value="F" <? echo ($user->obj->sesso == "F") ? 'selected="selected"' : ""; ?>>Femmina</option>
                                </select>
                            </div>
                        </fieldset>
                        <div class="row-spacer"></div>
                        <fieldset>
                            <label class="span3">Data di nascita *</label>
                            <div class="span8">
                                <input validate_as="date" style="width: 110px;" type="text" name="indb_data_nascita" value="<?=$utility->parseDbDateToString ($user->obj->data_nascita)?>" /><a name="data-nascita-picker" class="ui-icon ui-icon-calendar floating">a</a>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="span12 text-center">
                    <button name="aggiornamento-dati-utente" class="btn"><i class="icon-ok"></i> AGGIORNA DATI</button>
                </div>
            </div>
        -->

	</div>
	</form>
</div>
<script>
activity.settings.init_update_data_form ();
</script>