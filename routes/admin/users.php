<?php

use \App\Http\Response;
use \App\Controller\Admin;

$app->get("/admin/users", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Users::getUsers($request));
  }
]);

$app->get("/admin/users/new", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Users::getNewUser($request));
  }
]);

$app->post("/admin/users/new", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Users::setNewUser($request));
  }
]);

$app->get("/admin/users/{id}/edit", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Users::getEditUser($request, $id));
  }
]);

$app->post("/admin/users/{id}/edit", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Users::setEditUser($request, $id));
  }
]);


$app->get("/admin/users/{id}/delete", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Users::getDeleteUser($request, $id));
  }
]);

$app->post("/admin/users/{id}/delete", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Users::setDeleteUser($request, $id));
  }
]);
