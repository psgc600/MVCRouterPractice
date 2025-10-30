<?php
/**
 * このファイルがドキュメントルートになるようにhttpd.confを設定してください。
 */

spl_autoload_register(function($class) {
  echo "<div>[debug]Want to load $class.</div>";
  require __DIR__ . "/../app/Controllers/{$class}.php";
});

// ユーザーからのリクエストURIを取得し、解析する
// リクエストURIを取得（例：/user/profile）
$requestUri = $_SERVER['REQUEST_URI'];
print'<pre>';
print_r($requestUri);
print'</pre>';

// クエリ文字列を除去してパス部分だけを抽出
$path = parse_url($requestUri, PHP_URL_PATH);
// $path = $requestUri;
print'<pre>';
print_r($path);
print'</pre>';

$path = trim($path, '/');
print'<pre>';
print_r($path);
print'</pre>';

// パスをスラッシュで分割して配列にする
// $pathParts = explode('/', trim($path, '/'));
// print'<pre>';
// print_r($pathParts);
// print'</pre>';

// 例：/user/profileにアクセスした場合
// $pathPartsは['user', 'profile']となる

// ルート定義を読み込み
require_once __DIR__ . '/../app/routes.php';
print'<pre>';
print_r(__DIR__ . '/../app/routes.php');
print'</pre>';
print'<pre>';
print_r($routes);
print'</pre>';

$foundRoute = false;
$params = [];

// ルート定義をループしてマッチング
foreach ($routes as $routePattern => $routeInfo) {
  // ルート定義のパターンを正規表現に変換
  // 例: 'user/profile/{id}' -> '^user\/profile\/([0-9]+)$'
  $regexPattern = preg_replace('/\{([a-z0-9]+)\}/', '([0-9]+)', str_replace('/', '\/', $routePattern));

  // パスの末尾にスラッシュがない場合を考慮
  $regexPattern = '/^' . $regexPattern . '$/';

  // 正規表現でマッチングを試みる
  if (preg_match($regexPattern, $path, $matches)) {
    $controllerName = $routeInfo['controller'];
    $actionName = $routeInfo['action'];

    // パラメータを抽出（matches配列の1番目以降がパラメータ）
    array_shift($matches);  // 0番目の要素（全体マッチ）を削除
    $params = $matches;

    $foundRoute = true;
    break;
  }
}

if ($foundRoute) {
  // コントローラーを実行（パラメータを渡す）
  $controllerClassName = $controllerName . 'Controller';
  $controllerFile = __DIR__ . '/../app/Controllers/' . $controllerClassName . '.php';

  if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerClassName();

    // アクションを実行し、抽出したIDを引数として渡す
    if (method_exists($controller, $actionName)) {
      $controller->$actionName(...$params); // 可変引数でパラメータを渡す
    } else {
      header("HTTP/1.0 404 Not Found");
      echo "404 Not Found (Action)";
    }
  } else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found (Controller)";
  }
} else {
  header("HTTP/1.0 404 Not Found");
  echo "404 Not Found";
}
exit();
// パスを文字列に再結合（例：'user/profile'）
$routeKey = implode('/', $pathParts);
if (empty($routeKey)) {
  $routeKey = '/';
}
echo "<div>[debug]{$routeKey}をルート定義から探します</div>";

// ルート定義にマッチするかをチェック
if (array_key_exists($routeKey, $routes)) {
  $controllerName = $routes[$routeKey]['controller'];
  echo "<div>[debug]コントローラ名は{$controllerName}です</div>";
  $actionName = $routes[$routeKey]['action'];
  echo "<div>[debug]アクション名は{$actionName}です</div>";
} else {
  // マッチしない場合は404エラー
  echo "<div>{$routeKey}というルートは登録されていない</div>";
  header('HTTP/1.0 404 Not Found');
  echo "404 Not Found";
  exit;
}

// マッチしたルート情報から、適切なコントローラーとアクションを実行する
// コントローラーのクラス名を生成（例：'UserController'）
$controllerClassName = $controllerName . 'Controller';
$controllerFile = __DIR__ . '/../app/Controllers/' . $controllerClassName . '.php';
echo "<div>[debug]コントローラーファイル{$controllerFile}を探します</div>";

// コントローラーファイルを読み込み
if (file_exists($controllerFile)) {
  echo "<div>[debug]コントローラファイルがあります。インクルードします</div>";
  // require_once $controllerFile;

  // コントローラーのインスタンスを生成
  $controller = new $controllerClassName();

  // アクションを実行
  if (method_exists($controller, $actionName)) {
    $controller->$actionName();
  } else {
    // アクションが見つからない場合
    echo "<div>[debug]アクションが見つかりませんでした</div>";
    header('HTTP/1.0 404 Not Found');
    echo "404 Not Found (Action)";
  }
} else {
  // コントローラーファイルが見つからない場合
  echo "<div>[debug]コントローラファイルが見つかりませんでした</div>";
  header('HTTP/1.0 404 Not Found');
  echo "404 Not Found (Controller)";
}
