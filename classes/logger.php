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