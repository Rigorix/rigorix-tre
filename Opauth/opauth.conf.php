<?php
/**
 * Opauth basic configuration file to quickly get you started
 * ==========================================================
 * To use: rename to opauth.conf.php and tweak as you like
 * If you require advanced configuration options, refer to opauth.conf.php.advanced
 */

$config = array(
/**
 * Path where Opauth is accessed.
 *  - Begins and ends with /
 *  - eg. if Opauth is reached via http://example.org/auth/, path is '/auth/'
 *  - if Opauth is reached via http://auth.example.org/, path is '/'
 */
	'path' => '/Opauth/',

  'callback_transport' => 'post',

/**
 * Callback URL: redirected to after authentication, successful or otherwise
 */
//	'callback_url' => '{path}callback.php',
	
/**
 * A random string used for signing of $auth response.
 * 
 * NOTE: PLEASE CHANGE THIS INTO SOME OTHER RANDOM STRING
 */
	'security_salt' => 't837oq4rwyfpr938cwry87iw34tyr8iw7ertycfgh',
		
/**
 * Strategy
 * Refer to individual strategy's documentation on configuration requirements.
 * 
 * eg.
 * 'Strategy' => array(
 * 
 *   'Facebook' => array(
 *      'app_id' => 'APP ID',
 *      'app_secret' => 'APP_SECRET'
 *    ),
 * 
 * )
 *
 */
	'Strategy' => array(
		// Define strategies and their respective configs here
		
		'Facebook' => array(
			'app_id' => '208222219208475',
			'app_secret' => 'f1f51dbd33c7d16697d08cfad39fb87d'
		),
		
		'Google' => array(
			'client_id' => '373679399934-oev7grpsq0es9ucgegjnfo1ckl62khs5.apps.googleusercontent.com',
			'client_secret' => 'tQqYjf28D3F6f3PuXs9rtTxQ'
		),

    'Twitter' => array(
      'key' => 'v2L3r7wXvYTTNLNonWgg',
      'secret' => 'sEokdAWWYSaVlFrZfcdvL6AxthJf79TmoknTNsTHA'
    ),

    'Foursquare' => array(
      'key' => 'V5UUIHGHLIAGQRDFJ5MRYFXH3ASMGW2INKPBOPR0IR0CKMD4',
      'secret' => 'SQQWL0HVQKMUONMDT50KXT2ZAXV2KEWZISRBK3EJDJI51BZS'
    ),
				
	),
);