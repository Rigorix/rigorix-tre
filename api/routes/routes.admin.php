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

Flight::route('GET /tables/@name', function ($name) {
  if ($name == "utente") {
    echo (string)Users::all();
  }

});