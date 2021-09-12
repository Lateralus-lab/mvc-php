<?php

namespace App\Model;

use Base\AbstractModel;
use Base\Database;

class Message extends AbstractModel
{
  private $id;
  private $text;
  private $createdAt;
  private $authorId;
  /** @var User */
  private $author;
  private $image;

  public function __construct(array $data = [])
  {
    $this->text = $data['text'];
    $this->createdAt = $data['created_at'];
    $this->authorId = $data['author_id'];
    $this->image = $data['image'] ?? '';
  }

  public function save()
  {
    $db = Database::getInstance();

    $res = $db->exec(
      'INSERT INTO messages (text, created_at, author_id, image) 
      VALUES (:text, :created_at, :author_id, :image)',
      __FILE__,
      [
        ':text' => $this->text,
        ':created_at' => $this->createdAt,
        ':author_id' => $this->authorId,
        ':image' => $this->image,
      ]
    );

    return $res;
  }

  public static function getList(int $limit = 10, int $offset = 0): array
  {
    $db = Database::getInstance();
    $data = $db->fetchAll(
      "SELECT * fROM messages LIMIT $limit OFFSET $offset",
      __METHOD__
    );
    if (!$data) {
      return [];
    }

    $messages = [];
    foreach ($data as $elem) {
      $message = new self($elem);
      $message->id = $elem['id'];
      $messages[] = $message;
    }

    return $messages;
  }

  public static function getUserMessages(int $userId, int $limit): array
  {
    $db = Database::getInstance();
    $data = $db->fetchAll(
      "SELECT * fROM messages WHERE author_id = $userId LIMIT $limit",
      __METHOD__
    );
    if (!$data) {
      return [];
    }

    $messages = [];
    foreach ($data as $elem) {
      $message = new self($elem);
      $message->id = $elem['id'];
      $messages[] = $message;
    }

    return $messages;
  }

  public function loadFile(string $file)
  {
    if (file_exists($file)) {
      $this->image = $this->genFileName();
      move_uploaded_file($file, getcwd() . '/images/' . $this->image);
    }
  }

  private function genFileName()
  {
    return sha1(microtime(1) . mt_rand(1, 10000000)) . '.jpg';
  }

  /** 
   * @return mixed
   */
  public function getImage()
  {
    return $this->image;
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
  public function getText(): string
  {
    return $this->text;
  }

  /**
   * @return mixed
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * @return mixed
   */
  public function getAuthorId()
  {
    return $this->authorId;
  }

  /**
   * @return User
   */
  public function getAuthor(): User
  {
    return $this->author;
  }

  /**
   * @param User $author
   */
  public function setAuthor(User $author): void
  {
    $this->author = $author;
  }

  public function getData()
  {
    return [
      'id' => $this->id,
      'author_id' => $this->authorId,
      'text' => $this->text,
      'created_at' => $this->createdAt,
      'image' => $this->image,
    ];
  }
}