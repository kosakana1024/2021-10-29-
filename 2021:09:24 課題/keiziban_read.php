<?php

// DB接続
include('functions.php');
$pdo = connect_to_db();
$user_id = $_SESSION['id'];


// SQL作成&実行
// $sql = 'SELECT * FROM keiziban_table ORDER BY created_at ASC';
$sql = 'SELECT * FROM keiziban_table LEFT OUTER JOIN (SELECT contents_id, COUNT(id) AS like_count FROM like_table GROUP BY contents_id) AS result_table ON keiziban_table.id = result_table.contents_id';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    exit('sqlError:'.$error[2]);
}   else {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = "";
    foreach ($result as $record) {
        $output .= "
            <tr>
                <td>{$record["named"]}</td>
                <td>{$record["contents"]}</td>
                <td><a href='like_create.php?user_id={$user_id}&contents_id={$record["id"]}'>like{$record["like_count"]}</a></td>
                <td>
                    <a href='keiziban_edit.php?id={$record["id"]}'>編集</a>
                </td>
                <td>
                    <a href='keiziban_delete.php?id={$record["id"]}'>削除</a>
                </td>
            </tr>
        ";
    }
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易掲示板</title>
    <style>
    </style>
</head>

<body>
    <fieldset>
        <legend>簡易掲示板（一覧画面）</legend>
        <a href="keiziban_input.php">入力画面</a>
        <table>
        <thead>
        <tr>
            <th>名前</th>
            <th>投稿内容</th>
        </tr>
        </thead>
        <tbody>
            <!-- ここに<tr> <td>named</td> <td>contents/td> <tr>の形でデータが入る -->
            <?= $output ?>
        </tbody>
        </table>
    </fieldset>
</body>
</html>