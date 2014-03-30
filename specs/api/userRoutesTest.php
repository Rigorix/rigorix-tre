<?php

//require_once __DIR__ . '/../../api/database.php';

class UsersTest extends \PHPUnit_Framework_TestCase
{

  public function testGetBadges() { global $api;
    $result = $api->get("badges");

    $this->assertTrue(is_string($result->response));
  }
}