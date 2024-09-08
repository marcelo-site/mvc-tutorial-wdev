<?php

namespace App\Controller\Api;

use Exception;
use \App\Model\Entity\User;
use Firebase\JWT\JWT;


class Auth extends Api
{
  public static function generateToken($request)
  {
    $postVars = $request->getPostVars();

    if (!isset($postVars['senha']) or !isset($postVars['email'])) {
      throw new Exception('Nome e senha são obrigatorios');
    }

    $obUser = User::getUserByEmail($postVars['email']);

    if (!$obUser instanceof User) {
      throw new \Exception("Usuario ou senha invalido(s)");
    }
    if (!password_verify($postVars['senha'], $obUser->senha)) {
      throw new \Exception("Usuario ou senha invalido(s)");
    }

    $payload = [
      'email' => $obUser->email,
      // 'iss' => 'http://example.org',
      // 'aud' => 'http://example.com',
      // 'iat' => 1356999524,
      // 'nbf' => 1357000000
    ];

    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */

    return [
      'token' => JWT::encode($payload, getenv('JWT_KEY'), 'HS256')
    ];
  }
}
