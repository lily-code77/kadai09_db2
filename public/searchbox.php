<?php
session_start();
require_once '../classes/UserLogic.php';
require_once '../functions.php';
require_once '../dbconnect.php';

// ログインしているか判定し、していなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if (!$result) {
    $_SESSION['login_err'] = 'ユーザを登録してログインしてください！';
    header('Location: signup_form.php');
    return;
}

$login_user = $_SESSION['login_user'];

// 直接アクセスされたらリダイレクト
if (!isset($_POST['word'])) {
    header("Location: login_form.php");
    exit();
}

// $_POST['word']で入力値を取得 文字前後の空白除去&エスケープ処理
$word = trim(htmlspecialchars($_POST['word'], ENT_QUOTES));
// 文字列の中の「　」(全角空白)を「」(何もなし)に変換
$word = str_replace("　", "", $word);
// 対象文字列が何もなかったらキーワード指定なしとする
if ($word === "") {
    $word = "キーワード指定なし";
}

$login_user = $_SESSION['login_user'];

$pdo = connect();

// データ登録SQL作成
$sql = "SELECT * FROM recipe_registration WHERE recipe_name LIKE :word OR ingredients LIKE :word2";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':word', '%' . $word . '%', PDO::PARAM_STR);
$stmt->bindValue(':word2', '%' . $word . '%', PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
}

// 全データ取得
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($recipes);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">

    <title>紡くっく | 検索結果</title>
</head>

<body>
    <h3>検索結果</h3>
    <div>
        <?php foreach ($recipes as $recipe) { ?>
            <img src="<?php echo "{$recipe['file_path']}"; ?>" alt="" width="200px">
            <p>レシピ名：<?php echo "{$recipe['recipe_name']}"; ?></p>
            <p>作成者：<?php echo "{$recipe['user_id']}"; ?></p>
            <p>材料：<?php echo "<br>" . nl2br("{$recipe['ingredients']}"); ?></p>
            <p>作り方：<?php echo "<br>" . nl2br("{$recipe['instructions']}"); ?></p>
            <p>レシピのエピソード：<?php echo "<br>" . nl2br("{$recipe['episode']}"); ?></p>
        <?php } ?>
    </div>

    <a href="./myKitchen.php">わたしの台所</a>
    <a href="./top.php">戻る</a>
</body>

</html>