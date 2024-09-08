<?php

use \App\Http\Response;
use \App\Controller\Api;

$app->get('/api/v1/testimonies', [
  'middlewares' => [
    'api',
    'cache'
  ],
  function ($request) {
    return new Response(200, Api\Testimony::getTestimonies($request), "application/json");
  }
]);

$app->get('/api/v1/testimonies/{id}', [
  'middlewares' => [
    'api',
    'cache'
  ],
  function ($request, $id) {
    return new Response(200, Api\Testimony::getTestimony($request, $id), "application/json");
  }
]);

$app->post('/api/v1/testimonies', [
  'middlewares' => [
    'api',
    'jwt-auth',
  ],
  function ($request) {
    return new Response(201, Api\Testimony::setNewTestimony($request), "application/json");
  }
]);

$app->put('/api/v1/testimonies/{id}', [
  'middlewares' => [
    'api',
    'jwt-auth'
  ],
  function ($request, $id) {
    return new Response(200, Api\Testimony::seEditTestimony($request, $id), "application/json");
  }
]);

$app->delete('/api/v1/testimonies/{id}', [
  'middlewares' => [
    'api',
    'jwt-auth'
  ],
  function ($request, $id) {
    return new Response(200, Api\Testimony::setDeleteTestimony($request, $id), "application/json");
  }
]);
