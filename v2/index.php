<?php

require_once '../App.php';
require_once '../data.php';



if (!$_GET) {
  App::getAll20Products($products_data);
  exit;
}

App::handleGet($products_data);

?>