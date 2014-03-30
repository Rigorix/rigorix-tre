<?php
/**
 * Created by PhpStorm.
 * User: paolo
 * Date: 30/03/2014
 * Time: 03:13
 */

$env = json_decode(file_get_contents(__DIR__ . '/../../.env'));
require_once __DIR__ . '/../../classes/restclient.php';

$api = new RestClient(array(
  'base_url' => substr($env->API_DOMAIN, 0, -1)
));