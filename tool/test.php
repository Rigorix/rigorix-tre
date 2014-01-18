<?php

$uri = $HTTP_GET_VARS;

$uri["a"] = "ciccio";
$uri["b"] = "pasticcio";

$uri = http_build_query($uri);
echo $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"] . "?" . $uri;

?>