<?php

namespace App\Model;

use Base\AbstractModel;
use Base\Database;

class User extends AbstractModel
{
  private $db;
  private $id;
  private $name;
  private $email;
  private $password;
  private $createdAt;

  public function __construct(string $name, string $email, string $password, string $createdAt)
  {
    $this->db = new Database();
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
    $this->createdAt = $createdAt;
  }

  public function save()
  {
    $sql = "INSERT INTO users (`name`, `email`, `password`, `created_at`)
            VALUES (:name, :email, :password, :created_at)";

    $values = [
      [':name',  $this->name],
      [':email',  $this->email],
      [':password',  self::getPasswordHash($this->password)],
      [':created_at',  $this->createdAt],
    ];

    return $this->db->queryDB($sql, Database::EXECUTE, $values);
  }

  public static function getPasswordHash(string $password)
  {
    return sha1('gasgas.' . $password);
  }
}
