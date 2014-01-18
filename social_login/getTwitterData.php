<?php
session_start();
require("twitter/twitteroauth.php");
require 'config/twconfig.php';
require 'config/functions.php';

if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {
    // We've got everything we need
    $twitteroauth = new TwitterOAuth(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	// Let's request the access token
    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
	// Save it in a session var
    $_SESSION['access_token'] = $access_token;
	// Let's get the user's info
    $user_info = $twitteroauth->get('account/verify_credentials');
	// Print user's info
    if (isset($user_info->error)) {
        // Something's wrong, go back to square 1
        header('Location: login-twitter.php');
    } else {
        $uid = $user_info->id;
        $username = $user_info->name;
        $user = new User();
        $userdata = $user->checkUser($uid, 'tw', $username);
        if( !empty($userdata) ) {
			header('Location: /index.php?activity=login_by_id&id=' . $userdata["id_utente"]);
        }
    }
} else {
    // Something's missing, go back to square 1
    header('Location: login-twitter.php');
}
?>
