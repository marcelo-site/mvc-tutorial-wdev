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

  public static function getUsers(
    $where = null,
    $order = null,
    $limit = null,
    $field = '*'
  ) {
    return (new Database("usuarios"))->select($where, $order, $limit, $field);
  }

  public function cadastrar()
  {

    $this->id = (new Database("usuarios"))->insert([
      'nome' => $this->nome,
      'email' => $this->email,
      'senha' => password_hash($this->senha, PASSWORD_DEFAULT),
    ]);
  }

  public static function getUserById($id)
  {
    return self::getUsers('id = ' . $id)->fetchObject(self::class);
  }


  public function atualizar()
  {
    return (new Database("usuarios"))->update('id = ' . $this->id, [
      "nome" => $this->nome,
      "email" => $this->email
    ]);
  }

  public function excluir()
  {
    return (new Database('usuarios'))->delete('id = ' . $this->id);
  }
}
