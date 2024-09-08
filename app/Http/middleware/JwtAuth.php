<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
  private function getJWTAuthUser($request)
  {
    try {
      $headers = $request->getHeaders();

      $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : "";

      $key = getenv('JWT_KEY');
      $decoded = (array) JWT::decode($jwt, new Key($key, 'HS256'));

      $email = $decoded['email'] ?? "";
      $obUser = User::getUserByEmail($email);
    } catch (\Exception $e) {
      throw new \Exception("Token invalido ", 403);
    }

    return ($obUser instanceof User) ? $obUser : null;
  }

  private function auth($request)
  {

    if ($obUser = $this->getJWTAuthUser($request)) {
      $request->user = $obUser;
      return true;
    }

    throw new \Exception('Acesso inválido', 403);
  }

  public function handle($request, $next)
  {

    $this->auth($request);

    return $next($request);
  }
}
