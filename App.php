<?php

class App
{
  /**
   * Array to store error messages
   */
  private static $errors = [];

  /**
   * The simple "v1" API
   * Main method:
   */
  public static function getAll20Products()
  {
    $data = self::getData();
    self::renderData($data);
  }

  /**
   * And helper methods:
   */

  private static function getData()
  {
    require 'data.php';
    return $products_data;
  }

  private static function renderData($data)
  {
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header(
      'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept'
    );
    header('Referrer-Policy: no-referrer');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  /**
   * Additions to handle "v2" API
   *
   * The main method that is called if queries are present in URL:
   * The order in which the code within are carried out is important:
   * 1. Shuffle
   * 2. Filter by category
   * 3. Slice array of products to size of 'show'
   * Otherwise the desired result will not be achieved.
   */
  public static function getFilteredProducts()
  {
    $data = self::getData();
    shuffle($data);

    if (isset($_GET['category'])) {
      try {
        $data = self::handleCategory($data);
      } catch (Exception $error) {
        array_push(self::$errors, ['Category' => $error->getMessage()]);
      }
    }

    if (isset($_GET['show'])) {
      try {
        $data = self::handleShow($data);
      } catch (Exception $error) {
        array_push(self::$errors, ['Category' => $error->getMessage()]);
      }
    }

    if (self::$errors) {
      self::renderData(self::$errors);
      exit();
    }

    self::renderData($data);
  }

  /**
   * Additional helper methods for V2:
   */

  private static function handleCategory($data)
  {
    $category = self::getQuery('category');
    if (
      $category !== 'creator' &&
      $category !== 'technic' &&
      $category !== 'mindstorms'
    ) {
      throw new Exception('Category not found');
    }
    return self::getFilterdArray($data);
  }

  private static function getFilterdArray($array)
  {
    $filtered = array_filter($array, function ($product) {
      return $product['category'] === self::getQuery('category');
    });
    return $filtered;
  }

  private static function handleShow($data)
  {
    $show = self::getQuery('show');
    if ($show < 1 || $show > 20 || $show % 1 !== 0) {
      throw new Exception('Show must be a whole number between 1 and 20');
    }
    return array_slice($data, 0, $show);
  }

  private static function getQuery($var)
  {
    if (isset($_GET[$var])) {
      $query = filter_var($_GET[$var], FILTER_SANITIZE_STRING);
      return $query;
    }
  }
}
