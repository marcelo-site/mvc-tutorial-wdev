<?php

namespace App\Utils\Cache;

class File
{

  private static function  getContentCache($hash, $expiration)
  {
    $cacheFile = self::getCacheFile($hash);

    if (!file_exists($cacheFile)) {
      return false;
    }

    $createTime = filemtime($cacheFile);
    $diffTime = time() - $createTime;

    if ($diffTime > (int)$expiration) {
      return false;
    }

    $serialize = file_get_contents($cacheFile);
    return unserialize($serialize);
  }

  private static function getCacheFile($hash)
  {
    $dir = ROOT_PATH . getenv('CACHE_DIR');

    if (!file_exists($dir)) {
      mkdir($dir, 0755, true);
    }


    return $dir . DIRECTORY_SEPARATOR . $hash;
  }

  private static function storedCache($hash, $content)
  {
    $serialize = serialize($content);

    $cacheFile = self::getCacheFile($hash);

    return file_put_contents($cacheFile, $serialize);
  }

  public static function getCache($hash, $expiration, $function)


  {
    $content = self::getContentCache($hash, $expiration);

    if ($content) {
      return $content;
    }

    // echo '<pre>';
    // print_r(1);
    // print_r($content);
    // echo '</pre>';
    // exit;


    $content = $function();

    self::storedCache($hash, $content);

    return $content;
  }
}
