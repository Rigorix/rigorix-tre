<?php

Flight::route('GET /badges', function() {
  echo Rewards::badges()->active()->get()->toJson();
});

Flight::route('GET /riconoscimenti', function () {
  Flight::json(array(
    "rewards" => json_decode((string)Rewards::badges()->active()->get()),
    "punti"   => json_decode((string)Rewards::punti()->active()->get())
  ));
});