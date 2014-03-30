<?php

$env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/.env'));
require_once '/classes/fastjson.php';
require_once '/classes/restclient.php';

$api = new RestClient(array(
  'base_url' => substr($env->API_DOMAIN, 0, -1)
));

class StackTest extends \PHPUnit_Framework_TestCase
{

  public function testSpecsRunning()
  {
    $this->assertTrue(true);
  }

//  public function testUserEndpoints()
//  {
//    $result = $api->get("users/0");
//
//    $this->assertObjectHasAttribute("info", $result);
//  }
}