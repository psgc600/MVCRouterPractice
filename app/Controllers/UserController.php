<?php
// require_once '../Models/User.php';

// namespace App\Controllers;

use App\Models\User;

class UserController
{
  public function profile()
  {
    echo "<div>UserController::profile() called.</div>";
    // 1.モデルを使ってデータを取得
    $user = new User();
    // $userData = $user->find(1);

    // 2.ビューにデータを渡してレンダリング
    require_once __DIR__ . '/../Views/user/profile.php';
  }
}
