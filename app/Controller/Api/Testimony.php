<?php

namespace App\Controller\Api;

use \App\Model\Entity\Testimony  as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api
{
  public static function getTestimonysItens($request, &$obPagination)
  {
    $itens = [];

    $total = EntityTestimony::getTestimonies(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

    $queryParams = $request->getQueryParams();

    $pageAtual = $queryParams['page'] ?? 1;
    $limit = $queryParams['limit'] ?? 10;

    $obPagination = new Pagination($total, $pageAtual, $limit);

    $limit = $obPagination->getLimit();

    $results = EntityTestimony::getTestimonies(null, 'id DESC', $limit);

    while ($testimonyItem = $results->fetchObject(EntityTestimony::class)) {
      $itens[] =  [
        'id' => $testimonyItem->id,
        'nome' => $testimonyItem->nome,
        'data' => $testimonyItem->data,
        'mensagem' => $testimonyItem->mensagem
      ];
    }

    return $itens;
  }

  public static function getTestimonies($request)
  {
    return [
      'depoimentos' => self::getTestimonysItens($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
    ];
  }

  public static function getTestimony($request, $id)
  {
    if (!is_numeric($id)) {
      throw new \Exception('O id ' . $id . ' não é valido', 400);
    }
    $obTestimony = EntityTestimony::getTestimonyById($id);

    if (!$obTestimony instanceof EntityTestimony) {
      throw new \Exception('O depoimento ' . $id . ' não foi encontrado', 404);
    }

    return [
      'id' => $obTestimony->id,
      'nome' => $obTestimony->nome,
      'data' => $obTestimony->data,
      'mensagem' => $obTestimony->mensagem
    ];
  }

  public static function setNewTestimony($request)
  {
    $postVars = $request->getPostVars();

    if (!isset($postVars['nome']) || !isset($postVars['mensagem'])) {
      throw new \Exception('Dados incompletos para prosseguir', 400);
    }

    $obTestimony = new EntityTestimony;
    $obTestimony->nome = $postVars['nome'];
    $obTestimony->mensagem = $postVars['mensagem'];

    $obTestimony->cadastrar();

    return [
      'id' => $obTestimony->id,
      'nome' => $obTestimony->nome,
      'data' => $obTestimony->data,
      'mensagem' => $obTestimony->mensagem
    ];
  }

  public static function seEditTestimony($request, $id)
  {
    $postVars = $request->getPostVars();

    if (!isset($postVars['nome']) || !isset($postVars['mensagem'])) {
      throw new \Exception('Dados incompletos para prosseguir', 400);
    }

    $obTestimony = EntityTestimony::getTestimonyById($id);

    if (!$obTestimony instanceof EntityTestimony) {
      throw new \Exception("Dado não encontrado", 404);
    }

    $obTestimony->nome = $postVars['nome'];
    $obTestimony->mensagem = $postVars['mensagem'];

    $obTestimony->atualizar();

    return [
      'id' => $obTestimony->id,
      'nome' => $obTestimony->nome,
      'data' => $obTestimony->data,
      'mensagem' => $obTestimony->mensagem
    ];
  }

  public static function setDeleteTestimony($request, $id)
  {
    $postVars = $request->getPostVars();

    if (!isset($postVars['nome']) || !isset($postVars['mensagem'])) {
      throw new \Exception('Dados incompletos para prosseguir', 400);
    }

    $obTestimony = EntityTestimony::getTestimonyById($id);

    if (!$obTestimony instanceof EntityTestimony) {
      throw new \Exception("Dado não encontrado", 404);
    }

    $obTestimony->excluir();

    return [
      'success' => true
    ];
  }
}
