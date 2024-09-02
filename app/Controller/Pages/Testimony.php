<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony  as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
{

  public static function getTestimonysItens($request, &$obPagination)
  {
    $itens = '';

    $total = EntityTestimony::getTestimonys(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

    $queryParams = $request->getQueryParams();

    $pageAtual = $queryParams['page'] ?? 1;

    $obPagination = new Pagination($total, $pageAtual, 5);

    $limit = $obPagination->getLimit();

    $results = EntityTestimony::getTestimonys(null, 'id DESC', $limit);

    while ($testimonyItem = $results->fetchObject(EntityTestimony::class)) {
      $content = View::render('pages/testimonys/item', [
        'nome' => $testimonyItem->nome,
        'data' => date('d/m/Y H:i:s', strtotime($testimonyItem->data)),
        'mensagem' => $testimonyItem->mensagem
      ]);

      $itens .= $content;
    }

    return $itens;
  }

  public static function getTestimonys($request)
  {

    $content = View::render('pages/testimonys', [
      'itens' => self::getTestimonysItens($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination)
    ]);


    return parent::getPage("Depoimentos", $content);
  }

  public static  function insertTestimony($request)
  {
    $postVars = $request->getPostVars();

    $entityTestimony = new EntityTestimony();
    $entityTestimony->nome = $postVars['nome'];
    $entityTestimony->mensagem = $postVars['mensagem'];
    $entityTestimony->cadastrar();

    return self::getTestimonys($request);
  }
}
