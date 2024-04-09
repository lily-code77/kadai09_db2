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

$files = getAllFile();
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

    <title>紡くっく | わたしの台所</title>
</head>

<body>
    <h1>わたしの台所</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>
    <!-- <p>メールアドレス：<?php echo h($login_user['email']) ?></p> -->

    <!-- map.phpは未実装 -->
    <a href="./map.php">あなたが紡いだ食卓を見る</a>

    <h3>マイレシピ　コレクション</h3>
    <div>
        <?php foreach ($files as $file) { ?>
            <?php if ($file["user_id"] == $login_user['name']) { ?>
                <!-- <?php var_dump($file) ?> -->
                <img src="<?php echo "{$file['file_path']}"; ?>" alt="" width="200px">
                <p>レシピ名：<?php echo "{$file['recipe_name']}"; ?></p>
                <p>材料：<?php echo "<br>" . nl2br("{$file['ingredients']}"); ?></p>
                <p>作り方：<?php echo "<br>" . nl2br("{$file['instructions']}"); ?></p>
                <p>レシピのエピソード：<?php echo "<br>" . nl2br("{$file['episode']}"); ?></p>
                <p><a href="detail.php?id=<?= h($file["id"]) ?>">更新</a></p>
                <p><a href="delete.php?id=<?= h($file["id"]) ?>">削除</a></p>
            <?php } ?>
        <?php } ?>
    </div>

    <a href="./top.php">戻る</a>

    <form action="logout.php" method="POST">
        <input class="b" type="submit" name="logout" value="ログアウト">
    </form>

</body>

</html>