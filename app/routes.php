<?php
// ルート定義の配列
$routes = [
  '/' => ['controller' => 'Home', 'action' => 'index'],
  'user/profile' => ['controller' => 'User', 'action' => 'profile'],
  'post/view' => ['controller' => 'Post', 'action' => 'view'],
];
