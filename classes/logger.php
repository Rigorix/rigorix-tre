<?php
function _log ( $context, $log = '' )
{

  $logfile = $_SERVER['DOCUMENT_ROOT'] . "/log/" . date("Y_M_d") . "_log.txt";

  if ( !is_file($logfile) )
    touch($logfile);

  $fc = fopen($logfile, 'a') or die ("can't open errorlog file (".$logfile.")");
  fwrite($fc, '
'.date("d-m-Y H:i:s").' '.$context.'> ' . $log);
  fclose($fc);
}

function _on_page_log() { global $api; ?>

  <div class="logger">
  <?php

  echo "<h4>Session</h4>";
  var_dump($_SESSION);

  echo "<h4>Request</h4>";
  var_dump($_REQUEST);

  echo "<h4>Logged user</h4>";
  if ( isset($_SESSION['rigorix_logged_user'])) {
    $loggedUser = $api->get("users/{$_SESSION['rigorix_logged_user']}");
    if ($loggedUser->info->http_code == 200)
      var_dump($loggedUser->response, 200);
    else
      var_dump($loggedUser->info);
  }

  ?>
  </div>
<? } ?>