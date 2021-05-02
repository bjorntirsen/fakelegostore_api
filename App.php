<?php

class App
{
  /**
   * Array to store error messages
   */
  private static $errors = [];

  /**
   * The simple "v1" API
   */
  public static function getAll20Products($data)
  {
    self::renderData($data);
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
   * The main function that is called if queries are present in URL
   * The order in which the functions within are carried out is important:
   * 1. shuffle
   * 2. filter by category
   * 3. slice array of products to size of 'show'
   */
  public static function handleGet($data)
  {
    shuffle($data);
    self::$errors = [];
    if (isset($_GET['category'])) {
      try {
        $data = self::handleCategory($data);
      } catch (Exception $error) {
        array_push(self::$errors, ['Category' => $error->getMessage()]);
      }
    }

    if ($_GET['show']) {
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
   * Helper functions to the main function
   * What they handle is described in their names
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
      throw new Exception('Show must be between 1 and 20');
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
