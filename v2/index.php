<?php

require_once '../App.php';

if (!$_GET) {
  App::getAll20Products();
  exit;
}

App::getFilteredProducts();

?>