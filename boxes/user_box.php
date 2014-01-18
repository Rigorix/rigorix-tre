<?php global $activity; ?>

<div ng-controller="UserBox">

  <div id="boxUtente" ng-show="userLogged">
    <div class="user-row">
      <table class="plain-table flat-table">
      <tr valign="top">
        <td rowspan="2">
          <div class="profile-picture-thumb-container">
            <a href="#" class="user-picture" title="Cambia l\'immagine del tuo profilo" name="add-profile-picture"><img src="{{currentUser.picture}}" width="100%" align="absmiddle" id="profile_picture" /></a>
          </div>
        </td>
        <td>
          Ciao <strong data-toggle="popover" data-content="And here\'s some amazing content. It\'s very engaging. right?">{{currentUser.username}}</strong>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <span class="badge" id="punteggioUtente">{{currentUser.punteggio_totale}}</span> punti
          <span class="row-spacer"></span>
          <a ng-show="currentUser.messages.length > 0" href="/area_personale.php?show_tab=rx-tab-messaggi" class="messages-icon" title="Hai dei messaggi da leggere"><strong class="count-unread-messages">{{currentUser.messages.length}}</strong> <img src="i/ico_messaggi.gif" align="absmiddle" /></a>
        </td>
      </tr>
      </table>
    </div>


    <div id="boxUtenteSfideAperteRow">
      <div class="sfide-row" ng-show="currentUser.sfide_da_giocare.length > 0">
        <div class="sep"></div>
        <p class="content"><img src="i/pallone_small.gif" alt="Pallone" /> &nbsp; Hai <strong id="totSfide" class="tornei_num_sfide">{{currentUser.sfide_da_giocare.length}} </strong> sfide &nbsp;<a href="area_personale.php?show_tab=rx-tab-sfide,rx-tab-sfide-attive" class="btn btn-success btn-small">Gioca</a></p>
      </div>
    </div>

    <div class="sep"></div>
    <p class="content btns">
      <a href="area_personale.php" class="btn btn-small btn-info">Area personale</a> <a href="index.php?activity=logout" class="btn btn-small btn-danger">Esci</a>
    </p>

  </div>



  <div id="boxUtente" ng-show="!userLogged">

    <?php if ( $activity->has_error_range ( 100, 101 ) ) { ?>
      <div class="ui-box ui-box-content ui-corner-all ui-state-error ui-margin">
        <div class="ui-box-content-html">
          <?php echo $activity->print_error_range (100, 101); ?>
        </div>
      </div>
    <?php } ?>

    <!-- SOCIAL LOGIN NEW -->
    <div class="ui-box-content-html" id="login_box">
      <p align="center">Accedi a Rigorix tramite questi social network:<br /><br /></p>
      <div class="row-fluid">
          <div class="span6 text-center">
              <a href="#" onclick="start_auth ('?provider=facebook');"><img src="/i/login_facebook.png" /></a>
          </div>
          <div class="span6 text-center">
              <a href="#" class="btn-twitter" onclick="start_auth ('?provider=twitter');"><img src="/i/login_twitter.png" /></a>
          </div>
      </div>
      <div class="row-fluid">
          <div class="span6 text-center">
              <a href="#" class="btn-google" onclick="start_auth ('?provider=google');"><img src="/i/login_google.png" /></a>
          </div>
          <div class="span6 text-center">
              <a href="#" class="btn-foursquare" onclick="start_auth ('?provider=foursquare');"><img src="/i/login_foursquare.png" /></a>
          </div>
      </div>
    </div>

    <script>
      function start_auth ( params ){
        start_url = "social_login.php" + params + "&return_to=".$env->DOMAIN."?_ts=" + (new Date()).getTime();
        window.open(
          start_url,
          "hybridauth_social_sing_on",
          "location=0,status=0,scrollbars=0,width=800,height=500"
        );
      }
    </script>

  </div>

</div>