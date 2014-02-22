<?php

require_once "classes/new.core.php";

$temp = 0;

function diff () { global $temp;
  $now = new DateTime();
  $diff = $now->getTimestamp() - $temp;
  $temp = $now->getTimestamp();
  return $diff . "sec <br>";
}


echo "START: " . diff();

$api->get("users/5824");

echo "GET USER: " . diff();

$api->get("users/5824");

echo "GET USER: " . diff();