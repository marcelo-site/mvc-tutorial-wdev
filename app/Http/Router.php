<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router
{
  private $url = "";
  private $prefix = "";
  private $routes = [];
  private $request;

  public function __construct($url)
  {
    $this->request = new Request($this);
    $this->url = $url;
    $this->setPrefix();
  }

  private function addRoute($method, $route, $params = [])
  {
    foreach ($params as $key => $value) {
      if ($value  instanceof Closure) {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }

    $params['middlewares'] = $params['middlewares'] ?? [];

    $params['variables'] = [];

    $patternVariable = '/{(.*?)}/';
    if (preg_match_all($patternVariable, $route, $matchies)) {
      $route = preg_replace($patternVariable, '(.*?)', $route);
      $params['variables'] = $matchies[1];
    }

    $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

    $this->routes[$patternRoute][$method] = $params;
  }

  private function setPrefix()
  {
    $parseUrl = parse_url($this->url);
    $this->prefix = $parseUrl['path'] or "";
  }

  public function get($route, $params = [])
  {
    return $this->addRoute("GET", $route, $params);
  }

  public function post($route, $params = [])
  {
    return $this->addRoute("POST", $route, $params);
  }

  private function getUri()
  {
    $uri = $this->request->getUri();

    $xUri = strlen($this->prefix)  > 0 ? explode($this->prefix, $uri) : [$uri];

    return end($xUri);
  }

  private function getRoute()
  {
    $uri = $this->getUri();
    $httpMethod = $this->request->getHttpMethod();

    foreach ($this->routes as $patternRoute => $method) {

      if (preg_match_all($patternRoute, $uri, $matchies)) {
        if (isset($method[$httpMethod])) {
          unset($matchies[0]);

          // Variáveis processadas
          $keys = $method[$httpMethod]['variables'];
          $method[$httpMethod]['variables'] = array_combine($keys, $matchies);
          $method[$httpMethod]['variables']['request'] = $this->request;

          return $method[$httpMethod];
        }

        throw new Exception("Método não permitido", 405);
      }
    }
    throw new Exception("URL não encontrada", 404);
  }

  public function run()
  {
    try {
      $route = $this->getRoute();

      if (!isset($route['controller'])) {
        throw new Exception("A URL não pode ser processada!", 404);
      }

      $args = [];

      $reflexion = new ReflectionFunction($route['controller']);
      foreach ($reflexion->getParameters() as $parameters) {
        $name = $parameters->getName();
        $args[$name] = is_array($route['variables'][$name]) ? $route['variables'][$name][0] : $route['variables'][$name] ?? "";
      }

      // Fila de middlewares
      $middlewareQueue = new MiddlewareQueue($route['middlewares'], $route['controller'], $args);


      return $middlewareQueue->next($this->request);
    } catch (Exception $e) {
      return new Response($e->getCode(), $e->getMessage());
    }
  }

  public function getCurrentUrl()
  {
    return $this->url . $this->getUri();
  }

  public function redirect($route)
  {
    $url = $this->url . $route;

    header('Location: ' . $url);
    exit;
  }
}
