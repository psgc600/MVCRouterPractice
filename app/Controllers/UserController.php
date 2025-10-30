<?php
// namespace App\Controllers;

require_once __DIR__ . '/../Models/User.php';

use App\Models\User;

class UserController
{
  public function profile($id)
  {
    echo "<div>UserController::profile() called.</div>";
    // 1.モデルを使ってデータを取得
    $user = new User();
    $userData = $user->find($id);

    if ($userData) {
      // 2.ビューにデータを渡してレンダリング
      require_once __DIR__ . '/../Views/user/profile.php';
    } else {
      header("HTTP/1.0 404 Not Found");
      echo "User not found";
    }
  }
}
