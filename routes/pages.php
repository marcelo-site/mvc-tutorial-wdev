<?php

use \App\Http\Response;
use \App\Controller\Pages;

$app->get("/", [
  function () {
    return new Response(200, Pages\Home::getHome());
  }
]);

$app->get("/about", [
  function () {
    return new Response(200, Pages\About::getAbout());
  }
]);

$app->get("/depoimentos", [
  function ($request) {
    return new Response(200, Pages\Testimony::getTestimonys($request));
  }
]);


$app->post("/depoimentos", [
  function ($request) {
    return new Response(200, Pages\Testimony::insertTestimony($request));
  }
]);

// $app->get("/page/{page}/{limit}", [
//   function ($page, $limit) {
//     return new Response(200, "Pagina: " . $page . " - " . "Limite: " . $limit);
//   }
// ]);
