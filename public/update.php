<?php
session_start();
require_once '../dbconnect.php';
require_once '../functions.php';
require_once '../classes/UserLogic.php';

$login_user = $_SESSION['login_user'];

// ファイル関連の取得
$file          = $_FILES['img'];
$filename      = basename($file['name']);
$tmp_path      = $file['tmp_name'];
$file_err      = $file['error'];
$filesize      = $file['size'];
// $upload_dir    = '/Applications/XAMPP/xamppfiles/htdocs/gs/kadai08_db1/public/images/';
$upload_dir    = 'images/';
$save_filename = date('YmdHis') . $filename;
$err_msgs      = array();
$save_path     = $upload_dir . $save_filename;

// それ以外のinputの取得
$login_user    = $login_user['name'];
$recipe_name   = $_POST['recipe_name'];
$ingredients   = $_POST["ingredients"];
$instructions  = $_POST["instructions"];
$episode       = $_POST["episode"];
$id            = $_POST["id"];

// ファイルのバリデーション
// ファイルのサイズが1MG未満か
if ($filesize > 1048576 || $file_err == 2) {
    array_push($err_msgs, 'ファイルサイズは1MB未満にしてください。');
}

// 拡張は画像形式か
$allow_ext = array('jpg', 'jpeg', 'png');
$file_ext = pathinfo($filename, PATHINFO_EXTENSION);

if (!in_array(strtolower($file_ext), $allow_ext)) {
    array_push($err_msgs, '画像ファイルを添付してください。');
}

if (count($err_msgs) === 0) {
    // ファイルはあるかどうか？
    if (is_uploaded_file($tmp_path)) {
        if (move_uploaded_file($tmp_path, $save_path)) {
            echo $filename . 'を' . $upload_dir . 'をアップしました。';
            //DBに保存（ファイル名、ファイルパス、キャプション）
            // $result = fileSave($login_user, $recipe_name, $filename, $save_path, $ingredients, $instructions, $episode);
            $pdo = connect();
            
            $sql = "UPDATE recipe_registration SET recipe_name=:recipe_name,file_name=:file_name,file_path=:file_path,ingredients=:ingredients,instructions=:instructions,episode=:episode WHERE id=:id";
            // var_dump($sql);
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':recipe_name', $recipe_name, PDO::PARAM_STR);
            $stmt->bindValue(':file_name', $file_name, PDO::PARAM_STR);
            $stmt->bindValue(':file_path', $file_path, PDO::PARAM_STR);
            $stmt->bindValue(':ingredients', $ingredients, PDO::PARAM_STR);
            var_dump($stmt);
            $stmt->bindValue(':instructions', $instructions, PDO::PARAM_STR);
            $stmt->bindValue(':episode', $episode, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $status = $stmt->execute();
            echo("here");
            var_dump($status);

            //データ登録処理後
            if($status==false){
                sql_error($stmt);
            }else{
                header("Location: myKitchen.php");
            }
        } else {
            echo 'ファイルが保存できませんでした。';
        }
    } else {
        echo 'ファイルが選択されていません。';
        echo '<br>';
    }
} else {
    foreach ($err_msgs as $msg) {
        echo $msg;
        echo '<br>';
    }
}

// header("Location: myKitchen.php");
