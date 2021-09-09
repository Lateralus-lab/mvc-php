<?php

namespace Base;

use Config;
use PDO;
use PDOException;
use Exception;

class Database
{
  const SELECTSINGLE = 1;
  const SELECTALL = 2;
  const EXECUTE = 3;

  private $pdo;

  public function __construct()
  {
    try {
      $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
      $this->pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public function queryDB($sql, $mode, $values = [])
  {
    $stmt = $this->pdo->prepare($sql);

    foreach ($values as $valueToBind) {
      $stmt->bindValue($valueToBind[0], $valueToBind[1]);
    }

    $stmt->execute();

    if ($mode !== Database::SELECTSINGLE && $mode !== Database::SELECTALL && $mode !== Database::EXECUTE) {
      throw new Exception('Invalid Mode');
    } elseif ($mode === Database::SELECTSINGLE) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($mode === Database::SELECTALL) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  }
}
