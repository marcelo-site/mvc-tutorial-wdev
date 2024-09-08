<?php

namespace App\Controller\Api;

class Api
{
  public static function getDetails($request)
  {
    return [
      'name'   => 'API - MVC',
      'versao' => 'v1.0.0',
      'autor'  => 'Marcelo',
      'email'  => 'franiscomarcelo@gmail.com',
    ];
  }

  protected static function getPagination($request, $obPagiantion)
  {
    $queryParams = $request->getQueryParams();
    $pages = $obPagiantion->getPages();

    return [
      'paginaAtual' => isset($queryParams['page']) ? $queryParams['page'] : 1,
      'quantidadesPaginas' => !empty($pages) ? count($pages) : 1
    ];
  }
}
