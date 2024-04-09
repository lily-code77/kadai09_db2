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

    <title>紡くっく | マイページ</title>
</head>

<body>
    <h1>レシピを登録する</h1>
    <p>You are：<?php echo h($login_user['name']) ?></p>
    <!-- <p>メールアドレス：<?php echo h($login_user['email']) ?></p> -->

    <h2>マイレシピ登録</h2>
    <form action="file_upload.php" method="post" enctype="multipart/form-data">
        <div class="content">
            料理名：<br><input type="text" name="recipe_name" class="input"><br>
            写真(.png、.jpg、.gifのみ対応)：<br>
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
            <input type="file" name="img" accept="image/*"><br>
            材料：<br><textarea name="ingredients" class="input big" cols="70" rows="10"></textarea><br>
            作り方：<br><textarea name="instructions" class="input big" cols="70" rows="10"></textarea><br>
            レシピのエピソード：<br><textarea name="episode" id="textarea" cols="70" rows="10"></textarea><br>
        </div>
        <button class="b" type="submit">作成</button>
    </form>
    <h3>マイレシピ　コレクション</h3>
    <div>
        <?php foreach ($files as $file) { ?>
            <?php if ($file["user_id"] == $login_user['name']) { ?>
                <img src="<?php echo "{$file['file_path']}"; ?>" alt="" width="200px">
                <p>レシピ名：<?php echo "{$file['recipe_name']}"; ?></p>
                <p>材料：<?php echo "<br>" . nl2br("{$file['ingredients']}"); ?></p>
                <p>作り方：<?php echo "<br>" . nl2br("{$file['instructions']}"); ?></p>
                <p>レシピのエピソード：<?php echo "<br>" . nl2br("{$file['episode']}"); ?></p>
                <!-- <p><?php echo "{$file['file_path']}"; ?></p> -->
            <?php } ?>
        <?php } ?>
    </div>
    <form action="logout.php" method="POST">
        <input class="b" type="submit" name="logout" value="ログアウト">
    </form>

</body>

</html>