<?php

namespace App\Http\Middleware;

use \App\Utils\Cache\File as CacheFile;

class Cache
{
  private static function isCacheble($request)
  {
    if (getenv('CACHE_TIME') <= 0) {
      return false;
    }

    if ($request->getHttpMethod() != "GET") {
      return false;
    }

    $headers = $request->getHeaders();
    if (isset($headers['Cache-Control']) and $headers['Cache-Control'] === 'no-cache') {
      return false;
    }

    return true;
  }

  /**
   * @param  Request $request
   * @return string
   */
  private function getHash($request)
  {
    $uri = $request->getRouter()->getUri();
    $queryParams = $request->getQueryParams();
    $uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : "";

    $routeHash = preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/'));

    return rtrim('route-' . $routeHash, '-');
  }

  public function handle($request, $next)
  {
    if (!$this->isCacheble($request)) return $next($request);

    $hash = $this->getHash($request);

    return CacheFile::getCache($hash, getenv('CACHE_TIME'), function () use ($request, $next) {
      return $next($request);
    });
  }
}
