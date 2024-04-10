<?php
$id = $_GET["id"];

session_start();
require_once '../classes/UserLogic.php';
require_once '../functions.php';
require_once '../dbconnect.php';

//DB接続します
$pdo = connect(); 

// データ登録SQL作成
$stmt = $pdo->prepare("DELETE FROM recipe_registration WHERE id=:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

// データ登録処理後
if($status==false){
    sql_error($stmt);
}else{
    header("Location: myKitchen.php");
}


