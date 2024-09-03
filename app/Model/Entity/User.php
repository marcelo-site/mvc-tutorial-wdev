<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User
{
  public $id;
  public $nome;
  public $email;
  public $senha;

  public static function getUserByEmail($email)
  {
    $db = new Database('usuarios');
    return $db->select("email = '$email'")->fetchObject(self::class);
  }
}
