<?php

class StackTest extends \PHPUnit_Framework_TestCase
{

  public function testSpecsRunning()
  {
    $this->assertTrue(true);
  }

  public function testUserEndpoints() { global $api;
    $result = $api->get("test");

    $this->assertEquals($result->response, "Api works!!");
  }
}