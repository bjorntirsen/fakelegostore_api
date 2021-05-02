<?php

class App
{
  private static function sendHeaders(){
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header(
      'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept'
    );
    header('Referrer-Policy: no-referrer');
  }

  public static function getAll20Products($data){
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    self::sendHeaders();
    echo $json;
  }
}