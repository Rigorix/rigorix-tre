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

Flight::route('GET /tables/@name/@id', function ($name, $id) { global $capsule;
  $table = Flight::getEloquentObject($name);
  if ($table::find($id) !== null)
    echo (string)$table::find($id);
  else {
    $fields = $capsule->getDatabaseManager()->select("SHOW COLUMNS FROM {$name}");
    $fieldsModel = array();
    foreach ($fields as $field) {
      if ($field["Key"] != "PRI" && $field["Field"] != "created_at" && $field["Field"] != "updated_at")
        $fieldsModel[$field["Field"]] = "";
    }
    Flight::json($fieldsModel);
  }
});

Flight::route('POST /tables/@name/@id', function ($name, $id) {
  $table = Flight::getEloquentObject($name);
  if ($table::find($id)) {
    $table::find($id)->update(json_decode(Flight::request()->body));
    echo (String)$table::find($id);
  } else {
    $newEntry = (array)json_decode(Flight::request()->body);
    try {
      $inserted = $table::create($newEntry);
    } catch(Exception $e) {
      throw new Exception( 'Something really gone wrong', 0, $e);
    }
    echo (string)$inserted;
  }

});

Flight::route('DELETE /tables/@name/@id', function ($name, $id) {
  $table = Flight::getEloquentObject($name);
  $table::find($id)->delete();
});

Flight::route('GET /tables/@name', function ($name) {
  $table = Flight::getEloquentObject($name);
  echo (string)$table::all();
});

Flight::route('GET /relations/@table/@id_field/@show_field', function ($table, $index, $show) {
  $table = Flight::getEloquentObject($table);
  echo (string)$table::find($index)->getAttribute($show);
});