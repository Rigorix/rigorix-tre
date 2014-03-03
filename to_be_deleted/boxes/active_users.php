<?php
//global $core, $user, $activity, $facebook;
//$utenti_attivi = $core->storage['NUMERO_USERNAME_ONLINE'];
?>

<ul class="list-element user-list">
  <li ng-repeat="user in activeUsers" ng-class="{true: 'its-me'}[User.id_utente == user.id_utente]" ng-include="'app/templates/username.html'"></li>
</ul>


<?php //$user->print_user_list ($utenti_attivi); ?>

