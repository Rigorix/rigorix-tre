<?php
require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$env = Flight::get("env");

$capsule->addConnection(array(
  'driver'    => 'mysql',
  'host'      => $env->DB->host,
  'database'  => $env->DB->name,
  'username'  => $env->DB->username,
  'password'  => $env->DB->password,
  'charset'   => 'utf8',
  'collation' => 'utf8_unicode_ci',
  'prefix'    => ''
));

$capsule->bootEloquent();