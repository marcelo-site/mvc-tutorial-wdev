<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page
{
  public static function getHome()
  {
    $organization = new Organization();

    $content = View::render('pages/home', [
      "name"        => $organization->name,
      "description" => $organization->descripiton,
      "site" => $organization->site
    ]);

    return parent::getPage("Home", $content);
  }
}
