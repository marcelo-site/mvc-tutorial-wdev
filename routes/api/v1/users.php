<?php

use \App\Http\Response;
use \App\Controller\Api;



$app->get('/api/v1/users', [
  'middlewares' => [
    'api',
    'jwt-auth',
    'cache'
  ],
  function ($request) {
    return new Response(200, Api\User::getUsers($request), "application/json");
  }
]);

$app->get('/api/v1/users/me', [
  'middlewares' => [
    'api',
    'jwt-auth',
    'cache'
  ],
  function ($request) {
    return new Response(200, Api\User::getCurrentUser($request), "application/json");
  }
]);

$app->get('/api/v1/users/{id}', [
  'middlewares' => [
    'api',
    'jwt-auth',
    'cache'
  ],
  function ($request, $id) {
    return new Response(200, Api\User::getUser($request, $id), "application/json");
  }
]);

$app->post('/api/v1/users', [
  'middlewares' => [
    'api',
    'jwt-auth'
  ],
  function ($request) {
    return new Response(201, Api\User::setNewUser($request), "application/json");
  }
]);

$app->put('/api/v1/users/{id}', [
  'middlewares' => [
    'api',
    'jwt-auth'
  ],
  function ($request, $id) {
    return new Response(200, Api\User::seEditUser($request, $id), "application/json");
  }
]);

$app->delete('/api/v1/users/{id}', [
  'middlewares' => [
    'api',
    'jwt-auth'
  ],
  function ($request, $id) {
    return new Response(200, Api\User::setDeleteUser($request, $id), "application/json");
  }
]);
