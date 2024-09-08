<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;


class UserBasicAuth
{
  private function getBasicAuthUser()
  {

    if (!$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_PW']) {
      return false;
    }

    $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);

    if (!$obUser instanceof User) {
      return false;
    }

    return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false;
  }

  private function basicAuth($request)
  {

    if ($obUser = $this->getBasicAuthUser()) {
      $request->user = $obUser;
      return true;
    }

    throw new \Exception('Usuário ou senha inválido', 403);
  }

  public function handle($request, $next)
  {

    $this->basicAuth($request);

    return $next($request);
  }
}
