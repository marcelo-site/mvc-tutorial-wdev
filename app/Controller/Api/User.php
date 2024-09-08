<?php

namespace App\Controller\Api;

use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Api
{
  public static function getUsersItens($request, &$obPagination)
  {
    $itens = [];

    $total = EntityUser::getUsers(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

    $queryParams = $request->getQueryParams();

    $pageAtual = $queryParams['page'] ?? 1;
    $limit = $queryParams['limit'] ?? 10;

    $obPagination = new Pagination($total, $pageAtual, $limit);

    $limit = $obPagination->getLimit();

    $results = EntityUser::getUsers(null, 'id DESC', $limit);

    while ($obUser = $results->fetchObject(EntityUser::class)) {
      $itens[] =  [
        'id' => $obUser->id,
        'nome' => $obUser->nome,
        'email' => $obUser->email,
      ];
    }

    return $itens;
  }

  public static function getUsers($request)
  {
    return [
      'users' => self::getUsersItens($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
    ];
  }

  public static function getUser($request, $id)
  {
    if (!is_numeric($id)) {
      throw new \Exception('O id ' . $id . ' não é valido', 400);
    }
    $obUser = EntityUser::getUserById($id);

    if (!$obUser instanceof EntityUser) {
      throw new \Exception('O depoimento ' . $id . ' não foi encontrado', 404);
    }

    return [
      'id' => $obUser->id,
      'nome' => $obUser->nome,
      'email' => $obUser->email,
    ];
  }

  public static function getCurrentUser($request)
  {
    $obUser = $request->user;

    return [
      'id' => $obUser->id,
      'nome' => $obUser->nome,
      'email' => $obUser->email,
    ];
  }

  public static function setNewUser($request)
  {
    $postVars = $request->getPostVars();

    if (!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha'])) {
      throw new \Exception('Dados incompletos para prosseguir', 400);
    }

    $obUser = new EntityUser;
    $obUser->nome = $postVars['nome'];
    $obUser->email = $postVars['email'];

    $obUser->cadastrar();

    return [
      'id' => $obUser->id,
      'nome' => $obUser->nome,
      'email' => $obUser->email
    ];
  }

  public static function seEditUser($request, $id)
  {
    $postVars = $request->getPostVars();

    if (!isset($postVars['nome']) || !isset($postVars['email'])) {
      throw new \Exception('Dados incompletos para prosseguir', 400);
    }

    $obUser = EntityUser::getUserById($id);

    if (!$obUser instanceof EntityUser) {
      throw new \Exception("Dado não encontrado", 404);
    }

    $obUser->nome = $postVars['nome'];
    $obUser->email = $postVars['email'];

    $obUser->atualizar();

    return [
      'id' => $obUser->id,
      'nome' => $obUser->nome,
      'email' => $obUser->email
    ];
  }

  public static function setDeleteUser($request, $id)
  {
    $postVars = $request->getPostVars();

    if (!isset($postVars['nome']) || !isset($postVars['email'])) {
      throw new \Exception('Dados incompletos para prosseguir', 400);
    }

    $obObuser = EntityUser::getUserById($id);

    if (!$obObuser instanceof EntityUser) {
      throw new \Exception("Dado não encontrado", 404);
    }

    $obObuser->excluir();

    return [
      'success' => true
    ];
  }
}
