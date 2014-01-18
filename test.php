<?
$__DEBUG_SCRIPT = true;
require_once ("classes/core.php");

if ( isset ($_REQUEST["id_utente"]) && $_REQUEST["id_utente"] != "" ): $id_utente = $_REQUEST["id_utente"]; ?>

	<h2>User testing</h2>
	<a href="#$user->get_badges">$user->get_badges</a> - <a href="#$User->has_badge_by_key">$User->has_badge_by_key</a> - <a href="#$User->get_badge_by_key">$User->get_badge_by_key</a>

	<h3>Test badges</h3>
	<ul>
		<li>
			<h5><a name="$user->get_badges"></a>$user->get_badges</h5>
			<pre><?php var_dump ( $user->get_badges ( $id_utente)); ?></pre>
		</li>
		<li>
			<h5><a name="$User->has_badge_by_key"></a>$User->has_badge_by_key </h5>
			<pre><?php var_dump ( $user->has_badge_by_key ( $id_utente, $_REQUEST["reward_key"] )); ?></pre>
		</li>
		<li>
			<h5><a name="$User->get_badge_by_key"></a>$User->get_badge_by_key </h5>
			<pre><?php var_dump ( $user->get_badge_by_key ( $id_utente, $_REQUEST["reward_key"] )); ?></pre>
		</li>
	</ul>
	<hr />


<?php endif; ?>