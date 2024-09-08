<?php

use \App\Http\Response;
use \App\Controller\Admin;

$app->get("/admin/testimonies", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Testimony::getTestimonies($request));
  }
]);

$app->get("/admin/testimonies/new", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Testimony::getNewTestimony($request));
  }
]);

$app->post("/admin/testimonies/new", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request) {
    return new Response(200, Admin\Testimony::setNewTestimony($request));
  }
]);

$app->get("/admin/testimonies/{id}/edit", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Testimony::getEditTestimony($request, $id));
  }
]);

$app->post("/admin/testimonies/{id}/edit", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Testimony::setEditTestimony($request, $id));
  }
]);


$app->get("/admin/testimonies/{id}/delete", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Testimony::getDeleteTestimony($request, $id));
  }
]);

$app->post("/admin/testimonies/{id}/delete", [
  'middlewares' => [
    'required-admin-login'
  ],
  function ($request, $id) {
    return new Response(200, Admin\Testimony::setDeleteTestimony($request, $id));
  }
]);
