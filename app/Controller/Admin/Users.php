<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;
use \App\Model\Entity\User as EntityUser;

class Users extends Page
{
  public static function getUsersItens($request, &$obPagination)
  {
    $itens = '';

    $total = EntityUser::getUsers(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

    $queryParams = $request->getQueryParams();

    $pageAtual = $queryParams['page'] ?? 1;

    $obPagination = new Pagination($total, $pageAtual, 5);

    $limit = $obPagination->getLimit();

    $results = EntityUser::getUsers(null, 'id DESC', $limit);

    while ($obUserItem = $results->fetchObject(EntityUser::class)) {
      $content = View::render('admin/modules/users/item', [
        'id' => $obUserItem->id,
        'nome' => $obUserItem->nome,
        'email' => $obUserItem->email,
      ]);

      $itens .= $content;
    }

    return $itens;
  }

  public static function getStatus($request)
  {
    $queryParams = $request->getQueryParams();

    if (!isset($queryParams['status'])) return '';

    switch ($queryParams['status']) {
      case 'created':
        return Alert::getSucess("Usuário criado com sucesso!");
        break;
      case 'updated':
        return Alert::getSucess("Usuário atualizado com sucesso!");
        break;
      case 'deleted':
        return Alert::getSucess(
          "Usuário deletado com sucesso!"
        );
        return Alert::getSucess("Usuário deletado com sucesso!");
        break;
    }
  }

  public static function getUsers($request)
  {
    $content = View::render('admin/modules/users/index', [
      'itens' => self::getUsersItens($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status' => self::getStatus($request),
    ]);

    return parent::getPanel("Usuários", $content, 'users');
  }

  public static function getNewUser($request)
  {
    $content = View::render('admin/modules/users/form', [
      'title' => "Cadastrar Usuario",
      'nome' => '',
      'email' => '',
      'status' => ''
    ]);

    return parent::getPanel("users", $content, 'users');
  }

  public static function setNewUser($request)
  {
    $powtVars = $request->getPostVars();

    $obUser = new EntityUser;
    $obUser->nome = $powtVars['nome'] ?? '';
    $obUser->email = $powtVars['email'] ?? '';
    $obUser->senha = $powtVars['senha']  ? $powtVars['senha'] : '';

    $obUser->cadastrar();

    $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=created');
  }


  public static function getEditUser($request, $id)
  {
    $obUser = EntityUser::getUserById($id);

    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    $content = View::render('admin/modules/users/form', [
      'title' => "Editar Usuário",
      'nome' => $obUser->nome,
      'email' => $obUser->email,
      'status' => self::getStatus($request)
    ]);

    return parent::getPanel("Editar depoimento", $content, 'users');
  }

  public static function setEditUser($request, $id)
  {
    $obUser = EntityUser::getUserById($id);

    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    $postVars = $request->getPostVars();

    $obUser->nome = $postVars['nome'] ?? $obUser->nome;
    $obUser->email = $postVars['email'] ?? $obUser->email;

    $obUser->atualizar();

    $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=updated');
  }

  public static function getDeleteUser($request, $id)
  {
    $obUser = EntityUser::getUserById($id);

    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    $content = View::render('admin/modules/users/delete', [
      'nome' => $obUser->nome,
      'email' => $obUser->email
    ]);

    return parent::getPanel("Excluir Usuario", $content, 'users');
  }

  public static function setDeleteUser($request, $id)
  {
    $obUser = EntityUser::getUserById($id);

    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }

    $deleteSucess =  $obUser->excluir();

    if ($deleteSucess) {
      $request->getRouter()->redirect('/admin/users/' . $id . '/delete?status=notDeleted');
    }

    $request->getRouter()->redirect('/admin/users?status=deleted');
  }
}
