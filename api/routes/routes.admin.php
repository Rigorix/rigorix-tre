<?php

Flight::route('GET /logs', function() {
  $directory = $_SERVER['DOCUMENT_ROOT'] . '/log/';
  $scanned_directory = array_diff(scandir($directory), array('..', '.', '.DS_Store'));
  $scanned_directory = array_reverse($scanned_directory);

  Flight::json(array_map(function ($file) {
    $file = str_replace("_", " ", $file);
    $file = explode(" log", $file);
    return $file[0];
  }, array_values($scanned_directory)));
});

Flight::route('GET /logs/@logfile', function ($logfile) {
  $lines = array();
  $file = fopen($_SERVER['DOCUMENT_ROOT']."/log/{$logfile}", "r");
  while(!feof($file)){
    array_push($lines, fgets($file)."
");
  }
  $lines = array_reverse($lines);
  fclose($file);
  echo implode("", $lines);
});

Flight::map("getEloquentObject", function ($name) {
  $name = strtolower($name);
  if ($name == "utente" || $name == "utenti")
    return Users;
  if ($name == "messaggi")
    return Messages;
  if ($name == "rewards")
    return Rewards;
  if ($name == "sfide")
    return Sfide;
  if ($name == "unsubscribe")
    return UsersUnsubscribe;
});

Flight::route('GET /tables/@name/@id', function ($name, $id) {
  $table = Flight::getEloquentObject($name);
  echo (string)$table::find($id);
});

Flight::route('POST /tables/@name/@id', function ($name, $id) {
  $table = Flight::getEloquentObject($name);
  $table::find($id)->update(json_decode(Flight::request()->body));

  echo (String)$table::find($id);
});

Flight::route('GET /tables/@name', function ($name) {
  $table = Flight::getEloquentObject($name);
  echo (string)$table::all();
});

Flight::route('GET /relations/@table/@id_field/@show_field', function ($table, $index, $show) {
  $table = Flight::getEloquentObject($table);
  echo (string)$table::find($index)->getAttribute($show);
});