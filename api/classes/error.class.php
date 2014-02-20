<?php

Flight::map('error', function(Exception $ex){
  // Handle error
  echo $ex->getTraceAsString();
});
//
//use Illuminate\Database\Eloquent\ModelNotFoundException;
//
//Users::error(function(ModelNotFoundException $e)
//{
//  return Response::make('Not Found', 404);
//});