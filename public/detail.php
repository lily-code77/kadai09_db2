<?php
$id = $_GET["id"];

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

$files = getAllFile();

$pdo = connect();

// データ登録SQL作成
$sql = "SELECT * FROM recipe_registration WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();
// var_dump($status); //true

// データ表示
$v = "";
if ($status == false) {
    sql_error($stmt);
}

// 全データ取得
// var_dump($stmt);
$v = $stmt->fetch();
var_dump($v);
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

    <title>紡くっく | マイレシピ更新</title>
</head>

<body>
    <h1>レシピを更新する</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>
    <!-- <p>メールアドレス：<?php echo h($login_user['email']) ?></p> -->

    <h2>マイレシピ更新</h2>
    <form action="update.php" method="post" enctype="multipart/form-data">
        <div class="content">
            料理名：<br><input type="text" name="recipe_name" class="input" value="<?= $v["recipe_name"] ?>"><br>
            写真(.png、.jpg、.gifのみ対応)：<br>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" name="img" accept="image/*" value="<?= $v["img"] ?>"><br>
            材料：<br><textarea name="ingredients" class="input big" cols="70" rows="10"><?= $v["ingredients"] ?></textarea><br>
            作り方：<br><textarea name="instructions" class="input big" cols="70" rows="10"><?= $v["instructions"] ?></textarea><br>
            レシピのエピソード：<br><textarea name="episode" id="textarea" cols="70" rows="10"><?= $v["episode"] ?></textarea><br>
            <input type="hidden" name="id" value="<?= $v["id"] ?>">
        </div>
        <button class="b" type="submit">更新</button>
    </form>

    <a href="./top.php">戻る</a>

    <form action="logout.php" method="POST">
        <input class="b" type="submit" name="logout" value="ログアウト">
    </form>

</body>