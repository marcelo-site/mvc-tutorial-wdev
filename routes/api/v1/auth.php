<?php

use \App\Http\Response;
use \App\Controller\Api;

$app->post('/api/v1/users/token', [
  'middlewares' => [
    'api'
  ],
  function ($request) {
    return new Response(201, Api\Auth::generateToken($request), "application/json");
  }
]);
