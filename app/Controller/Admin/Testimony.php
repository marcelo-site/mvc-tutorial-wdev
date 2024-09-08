<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;
use \App\Model\Entity\Testimony as EntityTestimony;

class Testimony extends Page
{

  public static function getTestimonysItens($request, &$obPagination)
  {
    $itens = '';

    $total = EntityTestimony::getTestimonies(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

    $queryParams = $request->getQueryParams();

    $pageAtual = $queryParams['page'] ?? 1;
    $limit = $queryParams['limit'] ?? 10;

    $obPagination = new Pagination($total, $pageAtual, $limit);

    $limit = $obPagination->getLimit();

    $results = EntityTestimony::getTestimonies(null, 'id DESC', $limit);

    while ($testimonyItem = $results->fetchObject(EntityTestimony::class)) {
      $content = View::render('admin/modules/testimonies/item', [
        'id' => $testimonyItem->id,
        'nome' => $testimonyItem->nome,
        'data' => date('d/m/Y H:i:s', strtotime($testimonyItem->data)),
        'mensagem' => $testimonyItem->mensagem
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
        return Alert::getSucess("Depoimento criado com sucesso!");
        break;
      case 'updated':
        return Alert::getSucess("Depoimento atualizado com sucesso!");
        break;
      case 'deleted':
        return Alert::getSucess(
          "Depoimento deletado com sucesso!"
        );
        return Alert::getSucess("Depoimento deletado com sucesso!");
        break;
    }
  }

  public static function getTestimonies($request)
  {
    $content = View::render('admin/modules/testimonies/index', [
      'itens' => self::getTestimonysItens($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status' => self::getStatus($request),
    ]);

    return parent::getPanel("Cadastrar Depoimento", $content, 'testimonies');
  }

  public static function getNewTestimony($request)
  {
    $content = View::render('admin/modules/testimonies/form', [
      'title' => "Cadastrar Depoimento",
      'nome' => '',
      'mensagem' => '',
      'status' => ''
    ]);

    return parent::getPanel("Testimonies", $content, 'testimonies');
  }

  public static function setNewTestimony($request)
  {
    $powtVars = $request->getPostVars();

    $obTestimony = new EntityTestimony;
    $obTestimony->nome = $powtVars['nome'] ?? '';
    $obTestimony->mensagem = $powtVars['mensagem'] ?? '';
    $obTestimony->cadastrar();

    $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=created');
  }


  public static function getEditTestimony($request, $id)
  {
    $obTestimony = EntityTestimony::getTestimonyById($id);

    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    $content = View::render('admin/modules/testimonies/form', [
      'title' => "Editar Depoimento",
      'nome' => $obTestimony->nome,
      'mensagem' => $obTestimony->mensagem,
      'status' => self::getStatus($request)
    ]);

    return parent::getPanel("Editar depoimento", $content, 'testimonies');
  }

  public static function setEditTestimony($request, $id)
  {
    $obTestimony = EntityTestimony::getTestimonyById($id);

    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    $postVars = $request->getPostVars();

    $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
    $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
    $obTestimony->atualizar();

    $request->getRouter()->redirect('/admin/testimonies/' . $obTestimony->id . '/edit?status=updated');
  }

  public static function getDeleteTestimony($request, $id)
  {
    $obTestimony = EntityTestimony::getTestimonyById($id);

    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    $content = View::render('admin/modules/testimonies/delete', [
      'nome' => $obTestimony->nome,
      'mensagem' => $obTestimony->mensagem
    ]);

    return parent::getPanel("Excluir depoimento", $content, 'testimonies');
  }

  public static function setDeleteTestimony($request, $id)
  {
    $obTestimony = EntityTestimony::getTestimonyById($id);

    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }

    $deleteSucess =  $obTestimony->excluir();

    if ($deleteSucess) {
      $request->getRouter()->redirect('/admin/testimonies/' . $id . '/delete?status=notDeleted');
    }

    $request->getRouter()->redirect('/admin/testimonies?status=deleted');
  }
}
