<?php

namespace App\Model;

use Base\AbstractModel;
use Base\Database;
use Config;

class User extends AbstractModel
{
  private $id;
  private $name;
  private $email;
  private $password;
  private $createdAt;

  public function __construct(array $data = [])
  {
    $this->name = $data['name'] ?? '';
    $this->email = $data['email'] ?? '';
    $this->password = $data['password'] ?? '';
    $this->createdAt = $data['created_at'] ?? '';
  }

  public function save()
  {
    $db = Database::getInstance();

    $res = $db->exec(
      'INSERT INTO users (name, email, password, created_at) 
      VALUES (:name, :email, :password, :created_at)',
      __FILE__,
      [
        ':name' => $this->name,
        ':email' => $this->email,
        ':password' => self::getPasswordHash($this->password),
        ':created_at' => $this->createdAt,
      ]
    );

    $this->id = $db->lastInsertId();

    return $res;
  }

  public static function getByEmail(string $email)
  {
    $db = Database::getInstance();

    $data = $db->fetchOne(
      "SELECT * fROM users WHERE email = :email",
      __METHOD__,
      [':email' => $email]
    );

    if (!$data) {
      return null;
    }

    $user = new self($data);
    $user->id = $data['id'];
    return $user;
  }

  public static function getByIds(array $userIds)
  {
    $db = Database::getInstance();

    $idsString = implode(',', $userIds);

    $data = $db->fetchAll(
      "SELECT * fROM users WHERE id IN($idsString)",
      __METHOD__
    );
    if (!$data) {
      return [];
    }

    $users = [];
    foreach ($data as $elem) {
      $user = new self($elem);
      $user->id = $elem['id'];
      $users[$user->id] = $user;
    }

    return $users;
  }

  public function getById(int $id)
  {
    $db = Database::getInstance();

    $data = $db->fetchOne("SELECT * fROM users 
                           WHERE id = :id", __METHOD__, [':id' => $id]);

    if (!$data) {
      return null;
    }

    $user = new self($data);
    $user->id = $id;
    return $user;
  }

  public static function getList(int $limit = 10, int $offset = 0): array
  {
    $db = Database::getInstance();
    $data = $db->fetchAll(
      "SELECT * fROM users LIMIT $limit OFFSET $offset",
      __METHOD__
    );
    if (!$data) {
      return [];
    }

    $users = [];
    foreach ($data as $elem) {
      $user = new self($elem);
      $user->id = $elem['id'];
      $users[] = $user;
    }

    return $users;
  }

  public static function getPasswordHash(string $password)
  {
    return sha1('gasgas.' . $password);
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @return mixed
   */
  public function getPassword()
  {
    return $this->password;
  }

  public function isAdmin(): bool
  {
    return in_array($this->id, Config::ADMIN_IDS);
  }
}
