<?php
namespace App\Models;

class User
{
  public function find($id)
  {
    $db = new \PDO("mysql:host=localhost;dbname=mvcrouterpractice", "root", "");
    $stmt = $db->prepare("SELECT id, name FROM users WHERE id = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $result[0];
  }
}