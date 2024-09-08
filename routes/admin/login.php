<?php

use \App\Http\Response;
use \App\Controller\Admin;

$app->get("/admin/login", [
  'middlewares' => [
    'required-admin-lougout'
  ],
  function ($request) {
    return new Response(200, Admin\Login::getLogin($request));
  }
]);

$app->post("/admin/login", [
  'middlewares' => [
    'required-admin-lougout'
  ],
  function ($request) {
    return new Response(200, Admin\Login::setLogin($request));
  }
]);

$app->get("/admin/logout", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Login::setLogout($request));
  }
]);
