<?php



Flight::route('POST /messages', function() {
  $data = getParams();
  Messages::create(get_object_vars($data->message));
});

Flight::route('POST /messages/reply', function() {
  $data = getParams();
  Messages::create(get_object_vars($data->message));
});


/*
 * Resource Object for Messages
 */

Flight::route('GET /messages/@id_message', function($id_message) {
  echo Messages::find($id_message)->toJson();
});

Flight::route('POST /messages/@id_message', function($id_message) {
  $postdata = getParams();
  Messages::find($id_message)->update(get_object_vars($postdata));
});

Flight::route('DELETE /message/@id_message', function($id_message) {
  Messages::find($id_message)->delete();
});