<?php
// var_dump($_POST);
// exit();

// DB接続
include('functions.php');

if (
    !isset($_POST['username']) || $_POST['username'] == '' ||
    !isset($_POST['password']) || $_POST['password'] == ''
) {
    exit('paramError');
}

// データ受け取り
$username = $_POST["username"];
$password = $_POST["password"];

// DB接続
$pdo = connect_to_db();

// SQL実行
$sql = 'SELECT COUNT(*) FROM users_table WHERE username=:username';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
}

if ($stmt->fetchColumn() > 0) {
    echo '<p>すでに登録されているユーザです．</p>';
    echo '<a href="keiziban_login.php">login</a>';
    exit();
}

$sql = 'INSERT INTO users_table(id, username, password, is_admin, is_deleted, created_at, updated_at) VALUES(NULL, :username, :password, 0, 0, now(), now())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    header("Location:keiziban_login.php");
    exit();
}