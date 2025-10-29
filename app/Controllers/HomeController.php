<?php
// namespace App\Controllers;

use App\Models\User;

class HomeController
{
  public function index()
  {
    echo "<div>HomeController::index() called.</div>";
    // 1.モデルを使ってデータを取得
    // $user = new User();
    // $userData = $user->find(1);

    // 2.ビューにデータを渡してレンダリング
    require_once __DIR__ . '/../Views/home/index.php';
  }
}
