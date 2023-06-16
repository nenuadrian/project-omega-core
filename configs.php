<?php declare(strict_types=1);

abstract class Configs {
  private static $configs;
  
  public static function init() {
    $file = dirname(__FILE__) . '/../configs/environment.json';

    if (!file_exists($file)) {
        die('Missing environment configs');
    }

    Configs::$configs = json_decode(file_get_contents($file), true);
  }
  
  public static function get($key) {
    if (!$key) return null;
    return Configs::$configs[$key];
  }
}
